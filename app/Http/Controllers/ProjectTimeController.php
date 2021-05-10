<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectTimeController extends Controller
{
    public function start(Request $request, $project_id)
    {
        $exist = ProjectUser::where('project_id', $project_id)->where('user_id', auth()->id())->exists();
        $current_timer = auth()->user()->users_project_time()->where('ended_at', null)->exists();
        $project = Project::find($project_id);
        $finished = $project->finished;

        $fields = $request->validate([
            'task' => 'string'
        ]);

        if ($exist && $finished == 0) {
            if (!$current_timer) {
                auth()->user()->users_project_time()->attach($project_id, ['task' => $fields['task']]);

                $response = [
                    'message' => 'Timer has started'
                ];
                return response($response, 200);
            } else {
                $response = [
                    'message' => 'User has already started timer for some project'
                ];

                return response($response, 406);
            }
        } else {
            $response = [
                'message' => 'User is not assigned to this project or project is already finished'
            ];

            return response($response, 406);
        }
    }

    public function stop($project_id)
    {
        auth()->user()->users_project_time()->where('project_id', $project_id)->where('ended_at', null)->update(['ended_at' => now()]);


        $response = [
            'message' => 'Timer has stopped'
        ];

        return response($response, 200);
    }

    public function stats($project_id)
    {
        $sort_by = request()->query('sortBy') ?? 'ASC';
        $projects_times = DB::table('project_time')
            ->select(DB::raw('timediff(ended_at, started_at) as `total_time`'))
            ->where('project_id', $project_id)
            ->orderBy('total_time', $sort_by)
            ->get();


        return response($projects_times, 200);








        // $t1 = Carbon::parse('2016-07-05 12:30:00');
        // $t2 = Carbon::parse('2016-07-05 13:30:00');
        // $totalDuration = $t1->diff($t2)->format('%H:%I:%S');
    }
}

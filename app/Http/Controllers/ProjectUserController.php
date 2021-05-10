<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectUserController extends Controller
{
    public function assign($project_id, $user_id, Request $request)
    {
        Validator::make(
            ['project_id' => $project_id, 'user_id' => $user_id],
            [
                'project_id' => [
                    'required',
                    'numeric'
                ],
                'user_id' => [
                    'required',
                    'numeric'
                ],
            ]
        )->validate();

        $project = Project::findOrFail($project_id);
        $user = User::findOrFail($user_id);

        $delete = ProjectUser::where('project_id', $project_id)->where('user_id', $user_id)->exists();



        if (!$delete) {
            

            $project->users_on_project()->attach($user);

            $response = [
                'message' => 'User successfully assigned to project',
            ];

            return response($response, 200);
        } 
        else {
            $project->users_on_project()->detach($user);
            $response = [
                'message' => 'User successfully deleted from project'
            ];

            return response($response, 200);
        }
    }

    public function list(){
        $projects_id = auth()->user()->projects->pluck('id');
        $projects = Project::find($projects_id, ['name']);

        return response($projects, 200);
    }
}

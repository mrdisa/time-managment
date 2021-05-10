<?php

namespace App\Http\Controllers;

use App\Models\Project;
use DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return response($projects, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required'
        ]);

        // Create
        $project = Project::create($request->all());

        // Message
        return response($project, 201);
    }

    /**
     * Check if project is finished or dont
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function finished($id)
    {

        $project = Project::find($id);
        if ($project->finished == 0) {
            $project->finished = 1;
            $project->finished_at = date(now());
            $project->save();

            return response($project, 200);
        } else {
            $project->finished = 0;
            $project->finished_at = null;
            $project->save();

            return response($project, 200);
        }
    }
}

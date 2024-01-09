<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $data['user_id'] = auth()->user()->id;
        Project::create($data);
        return redirect('/projects');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        abort_if(auth()->user()->id != $project->user_id,403);
        return view('projects.show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        abort_if(auth()->user()->id != $project->user_id,403);
        return view('projects.edit',compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'status' => 'sometimes|required'
        ]);
        // $project->update($data);

       $r = Project::where('id','=',$project->id)->update([
            'title' => $project->title,
            'description' => $project->description,
            'status' => $request->status
       ]);
        return redirect('/projects/'.$project->id);
      // return request()->all();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect('/projects');
    }
}

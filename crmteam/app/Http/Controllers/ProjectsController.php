<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::paginate(15);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $project = Project::create([
                'name' => ucwords($request->input('name')),
                'location' => $request->input('location'),
                'address' => $request->input('address'),
            ]);
			 if ($request->hasFile('brochure')) {
                $file = $request->file('brochure');
				//echo "<pre>";print_r($image);exit;
				$brochure = time().'.'.$file->getClientOriginalExtension();
				$destinationPath = public_path('/storage/galeryImages/');
				$file->move($destinationPath, $brochure);
                $project->brochure = $brochure;
                $project->saveQuietly();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('projects.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findorfail($id);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findorfail($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $project = Project::findorfail($id);
            $project->update([
                'name' => ucwords($request->input('name')),
                'location' => $request->input('location'),
                'address' => $request->input('address'),
            ]);
			if ($request->hasFile('brochure')) {
                // delete old photo if present
                if($project->brochure !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $project->brochure);
                    @unlink($file_path);
                }

                // now add new photo
                $file = $request->file('brochure');
				//echo "<pre>";print_r($image->getClientOriginalName());exit;
				$brochure = $file->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$file->move($destinationPath, $brochure);	
				 $project->brochure = $brochure;
                $project->saveQuietly();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('projects.show', ['id' => $project->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findorfail($id);
        $project->deletedBy()->associate(auth()->user());
        $project->saveQuietly();
        $project->delete();

        return redirect(route('projects.index'));
    }
}

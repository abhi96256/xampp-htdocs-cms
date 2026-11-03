<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadsController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Leads::paginate(15);

        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leads.create');
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
            'details' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Leads::create([
                'name' => ucwords($request->input('name')),
                'details' => $request->input('details'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('leads.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = Leads::findorfail($id);

        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lead = Leads::findorfail($id);

        return view('leads.edit', compact('lead'));
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
            'details' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lead = Leads::findorfail($id);
            $lead->update([
                'name' => ucwords($request->input('name')),
                'details' => $request->input('details'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('leads.show', ['id' => $lead->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Leads::findorfail($id);
        $lead->deletedBy()->associate(auth()->user());
        $lead->saveQuietly();
        $lead->delete();

        return redirect(route('leads.index'));
    }
}

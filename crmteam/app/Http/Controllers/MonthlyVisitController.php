<?php

namespace App\Http\Controllers;

use App\Models\MonthlyVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:site_visits access', ['only' => ['index', 'show']]);
        $this->middleware('can:site_visits create', ['only' => ['create', 'store']]);
        $this->middleware('can:site_visits edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:site_visits delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $visits = MonthlyVisit::with('user')->latest()->get();
        } else {
            $visits = MonthlyVisit::with('user')->where('user_id', $user->id)->latest()->get();
        }
        return view('monthly_visits.index', compact('visits'));
    }

    public function create()
    {
        $users = User::where('status', 1)->orderBy('name')->get();
        return view('monthly_visits.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'nullable|string|max:255',
            'email_id' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:255',
            'date_of_visit' => 'nullable|date',
            'site_visited' => 'nullable|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'date_of_visit_2' => 'nullable|date',
            'site_visited_2' => 'nullable|string|max:255',
            'room_name_2' => 'nullable|string|max:255',
            'date_of_visit_3' => 'nullable|date',
            'site_visited_3' => 'nullable|string|max:255',
            'room_name_3' => 'nullable|string|max:255',
            'date_of_visit_4' => 'nullable|date',
            'site_visited_4' => 'nullable|string|max:255',
            'room_name_4' => 'nullable|string|max:255',
            'date_of_visit_5' => 'nullable|date',
            'site_visited_5' => 'nullable|string|max:255',
            'room_name_5' => 'nullable|string|max:255',
            'date_of_visit_6' => 'nullable|date',
            'site_visited_6' => 'nullable|string|max:255',
            'room_name_6' => 'nullable|string|max:255',
            'interested_in' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'user_name' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        MonthlyVisit::create($data);

        return redirect()->route('monthly_visits.index')->with('success', 'Monthly Visit record added successfully.');
    }

    public function edit($id)
    {
        $visit = MonthlyVisit::findOrFail($id);
        // Ensure user can only edit their own, unless admin
        if (!Auth::user()->hasRole('Admin') && $visit->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::where('status', 1)->orderBy('name')->get();
        return view('monthly_visits.edit', compact('visit', 'users'));
    }

    public function update(Request $request, $id)
    {
        $visit = MonthlyVisit::findOrFail($id);
        
        if (!Auth::user()->hasRole('Admin') && $visit->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'client_name' => 'nullable|string|max:255',
            'email_id' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:255',
            'date_of_visit' => 'nullable|date',
            'site_visited' => 'nullable|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'date_of_visit_2' => 'nullable|date',
            'site_visited_2' => 'nullable|string|max:255',
            'room_name_2' => 'nullable|string|max:255',
            'date_of_visit_3' => 'nullable|date',
            'site_visited_3' => 'nullable|string|max:255',
            'room_name_3' => 'nullable|string|max:255',
            'date_of_visit_4' => 'nullable|date',
            'site_visited_4' => 'nullable|string|max:255',
            'room_name_4' => 'nullable|string|max:255',
            'date_of_visit_5' => 'nullable|date',
            'site_visited_5' => 'nullable|string|max:255',
            'room_name_5' => 'nullable|string|max:255',
            'date_of_visit_6' => 'nullable|date',
            'site_visited_6' => 'nullable|string|max:255',
            'room_name_6' => 'nullable|string|max:255',
            'interested_in' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'user_name' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        $visit->update($data);

        return redirect()->route('monthly_visits.index')->with('success', 'Visit updated successfully.');
    }

    public function destroy($id)
    {
        $visit = MonthlyVisit::findOrFail($id);
        
        if (!Auth::user()->hasRole('Admin') && $visit->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $visit->delete();
        return redirect()->back()->with('success', 'Visit removed successfully.');
    }
}

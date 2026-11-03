@extends('adminlte::page')
@section('title', 'Edit Monthly Visit')

@section('content_header')
    <h1>Edit Monthly Visit</h1>
@stop

@section('content')
<div class="card card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Update Visit Details</h3>
    </div>
    <form action="{{ route('monthly_visits.update', $visit->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <h5 class="text-primary mb-3"><i class="fas fa-user-circle"></i> Client Information</h5>
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Sno</label>
                    <input type="text" name="sno" class="form-control" placeholder="Enter Sno" value="{{ $visit->sno }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Client Name</label>
                    <input type="text" name="client_name" class="form-control" placeholder="Enter Client Name" value="{{ $visit->client_name }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Email Id</label>
                    <input type="email" name="email_id" class="form-control" placeholder="Enter Email Id" value="{{ $visit->email_id }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Contact no.</label>
                    <input type="text" name="contact_no" class="form-control" placeholder="Enter Contact No." value="{{ $visit->contact_no }}">
                </div>
            </div>
            
            <h5 class="text-primary mb-3 mt-4"><i class="fas fa-calendar-alt"></i> Initial Visit Data</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit</label>
                    <input type="date" name="e_date_of_visit" class="form-control" value="{{ $visit->e_date_of_visit }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited</label>
                    <input type="text" name="f_site_visited" class="form-control" placeholder="Enter Site" value="{{ $visit->f_site_visited }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Date of Visit</label>
                    <input type="date" name="g_date_of_visit" class="form-control" value="{{ $visit->g_date_of_visit }}">
                </div>
            </div>
            
            <hr>
            <h5 class="text-primary mb-3"><i class="fas fa-map-marker-alt"></i> Visit 1</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Developer Executive Name (1)</label>
                    <input type="text" name="developer_executive_name" class="form-control" value="{{ $visit->developer_executive_name }}" placeholder="Enter name">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (1)</label>
                    <input type="text" name="site_visited" class="form-control" value="{{ $visit->site_visited }}" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>Date of Visit (1)</label>
                    <input type="date" name="date_of_visit" class="form-control" value="{{ $visit->date_of_visit }}">
                </div>
            </div>

            <hr>
            <h5>Visit 2</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Developer Executive Name (2)</label>
                    <input type="text" name="developer_executive_name_2" class="form-control" value="{{ $visit->developer_executive_name_2 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (2)</label>
                    <input type="text" name="site_visited_2" class="form-control" value="{{ $visit->site_visited_2 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Date of Visit (2)</label>
                    <input type="date" name="date_of_visit_2" class="form-control" value="{{ $visit->date_of_visit_2 }}">
                </div>
            </div>

            <hr>
            <h5>Visit 3</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Developer Executive Name (3)</label>
                    <input type="text" name="developer_executive_name_3" class="form-control" value="{{ $visit->developer_executive_name_3 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (3)</label>
                    <input type="text" name="site_visited_3" class="form-control" value="{{ $visit->site_visited_3 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Date of Visit (3)</label>
                    <input type="date" name="date_of_visit_3" class="form-control" value="{{ $visit->date_of_visit_3 }}">
                </div>
            </div>

            <hr>
            <h5>Visit 4</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Developer Executive Name (4)</label>
                    <input type="text" name="developer_executive_name_4" class="form-control" value="{{ $visit->developer_executive_name_4 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (4)</label>
                    <input type="text" name="site_visited_4" class="form-control" value="{{ $visit->site_visited_4 }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Date of Visit (4)</label>
                    <input type="date" name="date_of_visit_4" class="form-control" value="{{ $visit->date_of_visit_4 }}">
                </div>
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any extra details...">{{ $visit->remarks }}</textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Interested In</label>
                    <input type="text" name="interested_in" class="form-control" value="{{ $visit->interested_in }}" placeholder="E.g. Commercial/Residential">
                </div>
                <div class="form-group col-md-6">
                    <label>User Name</label>
                    <select name="user_name" class="form-control">
                        <option value="">-- Select User --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->name }}" {{ ($visit->user_name ?? '') == $u->name ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Visit</button>
            <a href="{{ route('monthly_visits.index') }}" class="btn btn-default ml-2">Cancel</a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date();
        let dayOfWeek = today.getDay(); // 0 (Sun) to 6 (Sat)
        
        // Calculate the Sunday of the current week
        let firstDay = new Date(today);
        firstDay.setDate(today.getDate() - dayOfWeek);
        
        // Calculate the Saturday of the current week
        let lastDay = new Date(today);
        lastDay.setDate(today.getDate() + (6 - dayOfWeek));

        // Format to YYYY-MM-DD (handling timezones by using local parts)
        let minDateStr = firstDay.toLocaleDateString('en-CA'); // 'en-CA' outputs YYYY-MM-DD
        let maxDateStr = lastDay.toLocaleDateString('en-CA');

        // Apply restrictions to all date inputs
        let dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(function(input) {
            input.setAttribute('min', minDateStr);
            input.setAttribute('max', maxDateStr);
        });
    });
</script>
@stop

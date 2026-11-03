@extends('adminlte::page')
@section('title', 'Add Monthly Visit')

@section('content_header')
    <h1>Add Monthly Visit</h1>
@stop

@section('content')
<div class="card card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">New Visit Details</h3>
    </div>
    <form action="{{ route('monthly_visits.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <h5 class="text-primary mb-3"><i class="fas fa-user-circle"></i> Client Information</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Client Name</label>
                    <input type="text" name="client_name" class="form-control" placeholder="Enter Client Name">
                </div>
                <div class="form-group col-md-4">
                    <label>Email Id</label>
                    <input type="email" name="email_id" class="form-control" placeholder="Enter Email Id">
                </div>
                <div class="form-group col-md-4">
                    <label>Contact no.</label>
                    <input type="text" name="contact_no" class="form-control" placeholder="Enter Contact No.">
                </div>
            </div>
            
            <hr>
            <h5 class="text-primary mb-3"><i class="fas fa-map-marker-alt"></i> Visit 1</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (1)</label>
                    <input type="date" name="date_of_visit" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (1)</label>
                    <input type="text" name="site_visited" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (1)</label>
                    <input type="text" name="room_name" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <hr>
            <h5>Visit 2</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (2)</label>
                    <input type="date" name="date_of_visit_2" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (2)</label>
                    <input type="text" name="site_visited_2" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (2)</label>
                    <input type="text" name="room_name_2" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <hr>
            <h5>Visit 3</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (3)</label>
                    <input type="date" name="date_of_visit_3" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (3)</label>
                    <input type="text" name="site_visited_3" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (3)</label>
                    <input type="text" name="room_name_3" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <hr>
            <h5>Visit 4</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (4)</label>
                    <input type="date" name="date_of_visit_4" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (4)</label>
                    <input type="text" name="site_visited_4" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (4)</label>
                    <input type="text" name="room_name_4" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <hr>
            <h5>Visit 5</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (5)</label>
                    <input type="date" name="date_of_visit_5" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (5)</label>
                    <input type="text" name="site_visited_5" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (5)</label>
                    <input type="text" name="room_name_5" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <hr>
            <h5>Visit 6</h5>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date of Visit (6)</label>
                    <input type="date" name="date_of_visit_6" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Site Visited (6)</label>
                    <input type="text" name="site_visited_6" class="form-control" placeholder="E.g. A2P Location">
                </div>
                <div class="form-group col-md-4">
                    <label>RM Name (6)</label>
                    <input type="text" name="room_name_6" class="form-control" placeholder="Enter RM Name">
                </div>
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any extra details..."></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Interested In</label>
                    <input type="text" name="interested_in" class="form-control" placeholder="E.g. Commercial/Residential">
                </div>
                <div class="form-group col-md-6">
                    <label>User Name</label>
                    <select name="user_name" class="form-control">
                        <option value="">-- Select User --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Visit</button>
            <a href="{{ route('monthly_visits.index') }}" class="btn btn-default ml-2">Cancel</a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var today = new Date();

        // Min = 2 days before today
        var minDay = new Date(today);
        minDay.setDate(today.getDate() - 2);

        // Max = 4 days after today
        var maxDay = new Date(today);
        maxDay.setDate(today.getDate() + 4);

        // Format to YYYY-MM-DD
        var minDateStr = minDay.toLocaleDateString('en-CA');
        var maxDateStr = maxDay.toLocaleDateString('en-CA');

        // Apply to all date inputs on this page
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.setAttribute('min', minDateStr);
            input.setAttribute('max', maxDateStr);
        });
    });
</script>
@stop

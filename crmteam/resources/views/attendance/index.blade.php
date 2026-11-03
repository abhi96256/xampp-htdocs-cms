@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
    <h1>Attendance Record</h1>
@stop

@section('content')
    <div class="card px-3 py-3 mt-3">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="row align-items-end mb-4 bg-light p-3 rounded shadow-sm border">
                <div class="col-md-3">
                    <label class="font-weight-bold text-secondary">Select Year</label>
                    <select class="form-control shadow-sm" name="year" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @for($i = 2020; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold text-secondary">Select Month</label>
                    <select class="form-control shadow-sm" name="month" onchange="this.form.submit()">
                        <option value="">All Months</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf("%02d", $m) }}" {{ request('month') == sprintf("%02d", $m) ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                @if($isAdmin)
                <div class="col-md-3">
                    <label class="font-weight-bold text-secondary">Select User</label>
                    <select class="form-control shadow-sm" name="user_id" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach($users as $usr)
                            <option value="{{ $usr->id }}" {{ request('user_id') == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3">
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary shadow-sm"><i class="fas fa-times"></i> Clear Filter</a>
                </div>
            </div>
        </form>
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h5 class="text-primary font-weight-bold mb-0">Record List</h5>
            @can('attendance_create')
            <a href="{{ route('attendance.create') }}" class="btn btn-success border border-dark rounded shadow-sm px-4">
                <i class="fas fa-plus"></i> ADD ATTENDANCE
            </a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="attendanceTable">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Sl Nos</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Days</th>
                        <th>IN TIME</th>
                        <th>OUT TIME</th>
                        <th>Attendance type</th>
                        <th>IN Location</th>
                        <th>OUT Location</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d-M-Y') }}</td>
                            <td>{{ $attendance->days }}</td>
                            <td>{{ $attendance->in_time ? \Carbon\Carbon::parse($attendance->in_time)->format('h:i A') : '-' }}</td>
                            <td>{{ $attendance->out_time ? \Carbon\Carbon::parse($attendance->out_time)->format('h:i A') : '-' }}</td>
                            <td>
                                @if($attendance->attendance_type == 'Present')
                                    <span class="badge badge-success">Present</span>
                                @elseif($attendance->attendance_type == 'Absent')
                                    <span class="badge badge-danger">Absent</span>
                                @elseif($attendance->attendance_type == 'Short Time')
                                    <span class="badge badge-warning">Short Time</span>
                                @else
                                    <span class="badge badge-info">{{ $attendance->attendance_type }}</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->location_in)
                                    <a href="https://www.google.com/maps?q={{ $attendance->location_in }}" target="_blank" class="btn btn-sm btn-info text-nowrap">
                                        <i class="fas fa-map-marker-alt"></i> IN Map
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->location_out)
                                    <a href="https://www.google.com/maps?q={{ $attendance->location_out }}" target="_blank" class="btn btn-sm btn-warning text-nowrap">
                                        <i class="fas fa-map-marker-alt"></i> OUT Map
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(!$attendance->out_time)
                                    @can('attendance_edit')
                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-primary">Update</a>
                                    @endcan
                                @endif
                                @can('attendance_delete')
                                <form method="post" action="{{ route('attendance.destroy', $attendance->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($isAdmin)
        <div class="card mt-4 shadow-sm border-info">
            <div class="card-header bg-info text-white font-weight-bold">Summary Calculations (Based on Filter)</div>
            <div class="card-body">
                <div class="row text-center font-weight-bold h5">
                    <div class="col-md-3 text-success">Total Present %: <br> {{ $percentagePresent }}%</div>
                    <div class="col-md-3 text-danger">Total Absent %: <br> {{ $percentageAbsent }}%</div>
                    <div class="col-md-3 text-info">Total Leave %: <br> {{ $percentageLeave }}%</div>
                    <div class="col-md-3 text-warning">Total Short Time %: <br> {{ $percentageShortTime }}%</div>
                </div>
            </div>
        </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <style>
        .dataTables_length, .dataTables_filter { display: none !important; }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#attendanceTable').DataTable();
        });
    </script>
@stop

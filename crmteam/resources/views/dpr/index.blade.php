@extends('adminlte::page')

@section('title', 'DPR Reports')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="text-dark font-weight-bold"><i class="fas fa-list text-primary mr-2"></i>Daily Progress Reports</h1>
        </div>
        <div class="col-sm-6 text-right">
            <button type="button" class="btn btn-info shadow-sm mr-2" data-toggle="modal" data-target="#importDprModal">
                <i class="fas fa-file-import mr-1"></i> Import Excel
            </button>
            <a href="{{ route('dpr.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus mr-1"></i> Fill Daily DPR
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i><strong>Error:</strong> {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title text-secondary font-weight-bold mb-0"><i class="fas fa-filter mr-1"></i>Filter Reports</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dpr.index') }}" class="row">
                @if($isAdmin)
                    <div class="col-md-3 mb-3">
                        <label for="user_id" class="font-weight-normal text-muted">Select Employee</label>
                        <select name="user_id" id="user_id" class="form-control select2 shadow-sm border-light">
                            <option value="">-- All Employees --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="font-weight-normal text-muted">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control shadow-sm border-light" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="font-weight-normal text-muted">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control shadow-sm border-light" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="w-100 btn-group shadow-sm">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Filter</button>
                        <a href="{{ route('dpr.index') }}" class="btn btn-secondary"><i class="fas fa-undo mr-1"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Card -->
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title text-secondary font-weight-bold mb-0"><i class="fas fa-history mr-1"></i>Progress Log History</h5>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-success shadow-sm mr-2" data-toggle="modal" data-target="#importDprModal">
                    <i class="fas fa-file-import mr-1"></i> Import Excel
                </button>
                @if($isAdmin)
                    <a href="{{ route('dpr.export', request()->all()) }}" class="btn btn-info shadow-sm">
                        <i class="fas fa-file-excel mr-1"></i> Export to Excel
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="text-uppercase font-weight-bold" style="font-size: 12px;">
                        <tr>
                            @if($isAdmin)
                                <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">Employee</th>
                            @endif
                            <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">Date</th>
                            <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">Clock Shift</th>
                            <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">Start</th>
                            <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">End</th>
                            <th style="background: #7c3aed; color: #fff; letter-spacing:0.5px;">Flier Distributed</th>
                            <th style="background: #be123c; color: #fff; letter-spacing:0.5px;">No. Called</th>
                            <th style="background: #be123c; color: #fff; letter-spacing:0.5px;">Success Called</th>
                            <th style="background: #0369a1; color: #fff; letter-spacing:0.5px;">Blog Update</th>
                            <th style="background: #0369a1; color: #fff; letter-spacing:0.5px;">Social Lead</th>
                            <th style="background: #047857; color: #fff; letter-spacing:0.5px;">Dev Site Visit</th>
                            <th style="background: linear-gradient(135deg,#1a202c,#2d3561); color: #e2e8f0; letter-spacing:0.5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dprs as $dpr)
                            <tr>
                                @if($isAdmin)
                                    <td class="font-weight-bold text-left pl-3">{{ $dpr->user->name ?? 'N/A' }}</td>
                                @endif
                                <td class="text-primary font-weight-bold">{{ \Carbon\Carbon::parse($dpr->date)->format('d-M-Y') }}</td>
                                <td>
                                    @if($dpr->around_the_clock == 'MORNING')
                                        <span class="badge badge-warning text-uppercase px-2 py-1"><i class="fas fa-sun mr-1"></i> Morning</span>
                                    @elseif($dpr->around_the_clock == 'AFTERNOON')
                                        <span class="badge badge-orange text-white text-uppercase px-2 py-1" style="background-color: #fd7e14;"><i class="fas fa-cloud-sun mr-1"></i> Afternoon</span>
                                    @elseif($dpr->around_the_clock == 'EVENNING')
                                        <span class="badge badge-info text-uppercase px-2 py-1"><i class="fas fa-cloud-moon mr-1"></i> Evening</span>
                                    @else
                                        <span class="badge badge-dark text-uppercase px-2 py-1"><i class="fas fa-moon mr-1"></i> Late Evening</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-light border">{{ $dpr->start_time ?? '-' }}</span></td>
                                <td><span class="badge badge-light border">{{ $dpr->end_time ?? '-' }}</span></td>
                                <td style="background-color: #f3eeff;"><strong class="dpr-cell-violet">{{ $dpr->flier_distributed ?? 0 }}</strong> <small class="text-muted">({{ $dpr->location ?? '-' }})</small></td>
                                <td style="background-color: #fff0f4;"><strong class="dpr-cell-crimson">{{ $dpr->no_of_person_called ?? 0 }}</strong></td>
                                <td style="background-color: #fff0f4;"><span class="dpr-cell-crimson font-weight-bold">{{ $dpr->success_call ?? 0 }}</span></td>
                                <td style="background-color: #e8f4fc;"><strong class="dpr-cell-blue">{{ $dpr->no_blog_update_upload ?? 0 }}</strong></td>
                                <td style="background-color: #e8f4fc;"><span class="dpr-cell-blue font-weight-bold">{{ $dpr->nos_of_lead_generated_location ?? 0 }}</span></td>
                                <td style="background-color: #e8f6f1;"><strong class="dpr-cell-green">{{ $dpr->nos_of_developer ?? 0 }}</strong> <small class="text-muted">({{ $dpr->developer_name ?? '-' }})</small></td>
                                <td>
                                    <a href="{{ route('dpr.create', ['date' => $dpr->date]) }}" class="btn btn-sm btn-primary shadow-sm" title="Edit entire day's report">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 12 : 11 }}" class="py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                        <h5 class="mb-0">No Daily Progress Reports found</h5>
                                        <p class="small text-muted mt-1">Try expanding your filter criteria or fill your DPR entry for today.</p>
                                        <a href="{{ route('dpr.create') }}" class="btn btn-success btn-sm mt-2"><i class="fas fa-plus mr-1"></i> Fill DPR Now</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($dprs->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center py-3">
                {!! $dprs->links() !!}
            </div>
        @endif
    </div>

    <!-- Import DPR Modal -->
    <div class="modal fade" id="importDprModal" tabindex="-1" role="dialog" aria-labelledby="importDprModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-lg">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="importDprModalLabel">
                        <i class="fas fa-file-import mr-2"></i>Import Daily Progress Reports
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dpr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-file-excel fa-3x text-success mb-2"></i>
                            <p class="text-muted small">Upload your Daily Progress Reports spreadsheet (Excel .xlsx, .xls, or CSV) to bulk upload or sync your logs.</p>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="import_file" class="font-weight-bold text-secondary">Choose File</label>
                            <div class="custom-file shadow-sm">
                                <input type="file" class="custom-file-input" id="import_file" name="import_file" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this)">
                                <label class="custom-file-label" for="import_file">Choose spreadsheet file...</label>
                            </div>
                        </div>
                        
                        <div class="alert alert-info border-0 rounded-lg p-3 small mb-0 shadow-xs">
                            <i class="fas fa-info-circle mr-1"></i><strong>Format Info:</strong> File must contain <strong>Date</strong> and <strong>Clock Shift</strong> (Shift) columns. Shift names must be: Morning, Afternoon, Evening, or Late Evening.
                            @if($isAdmin)
                            <br><br><i class="fas fa-user-shield mr-1"></i>Admin users can specify the <strong>Employee Name</strong> column to upload on behalf of other users, otherwise it will assign to your own account.
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm"><i class="fas fa-upload mr-1"></i> Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th {
            vertical-align: middle !important;
        }
        .table td {
            vertical-align: middle !important;
            font-size: 14px;
        }
        /* DPR value cell colors */
        .dpr-cell-violet { color: #6d28d9 !important; }
        .dpr-cell-crimson { color: #be123c !important; }
        .dpr-cell-blue    { color: #0369a1 !important; }
        .dpr-cell-green   { color: #047857 !important; }

        /* Striped rows — override Bootstrap's gray with a cleaner off-white */
        .table-striped tbody tr:nth-of-type(odd) td {
            background-color: #fafafa !important;
        }
        .table-striped tbody tr:hover td {
            background-color: #f0f4ff !important;
        }
        /* Colored cells should keep their own tint on hover */
        .table-striped tbody tr:hover td[style] {
            filter: brightness(0.95);
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            border-color: #f8f9fa !important;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@stop

@section('js')
    <script>
        function updateFileName(input) {
            var fileName = input.files[0].name;
            $(input).next('.custom-file-label').html(fileName);
        }

        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%'
                });
            }
        });
    </script>
@stop

@extends('adminlte::page')

@section('title', 'Fill Daily Progress Report (DPR)')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="text-dark font-weight-bold"><i class="fas fa-calendar-check text-primary mr-2"></i>Daily Progress Report (DPR)</h1>
        </div>
        <div class="col-sm-6 text-right">
            <button type="button" class="btn btn-success shadow-sm mr-2" data-toggle="modal" data-target="#importDprModal">
                <i class="fas fa-file-import mr-1"></i> Import Excel
            </button>
            <a href="{{ route('dpr.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-history mr-1"></i> View DPR History
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

    <div class="card card-outline card-primary shadow-lg border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-white py-3">
            <div class="form-inline">
                <label for="date" class="font-weight-bold text-secondary mr-3"><i class="far fa-calendar-alt mr-1"></i>Date for DPR Entry:</label>
                <input type="text" name="date" id="dpr_date" class="form-control form-control-lg border-primary rounded shadow-sm mr-3 bg-light" 
                       value="{{ date('Y-m-d') }}" readonly>
            </div>
        </div>
        <div class="card-body p-0">
            <form method="POST" action="{{ route('dpr.store') }}">
                @csrf
                <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center align-middle" style="min-width: 1500px;">
                        <thead class="thead-dark text-uppercase font-weight-bold">
                            <!-- Main Super Headers -->
                            <tr>
                                <th rowspan="2" class="align-middle border-bottom-0 bg-success-header text-white" style="background-color: #2c3e50; min-width: 140px;">Around The Clock</th>
                                <th rowspan="2" class="align-middle border-bottom-0" style="background-color: #2c3e50; min-width: 100px;">Start Time</th>
                                <th rowspan="2" class="align-middle border-bottom-0" style="background-color: #2c3e50; min-width: 100px;">End Time</th>
                                
                                <th colspan="2" class="bg-peach text-dark py-2" style="background-color: #FDB99B; border-bottom: 2px solid #e26b0a;">Flier</th>
                                <th colspan="2" class="bg-pink text-dark py-2" style="background-color: #F2DCDB; border-bottom: 2px solid #b85a5a;">Calling</th>
                                <th colspan="6" class="bg-blue text-dark py-2" style="background-color: #00B0F0; border-bottom: 2px solid #0086c2;">Social Media</th>
                                <th colspan="2" class="bg-visit-green text-dark py-2" style="background-color: #C6EFCE; border-bottom: 2px solid #3b9c47;">Visit</th>
                                <th colspan="2" class="text-dark py-2" style="background-color: #ffc107; border-bottom: 2px solid #d39e00;">Event</th>
                            </tr>
                            <!-- Sub Headers -->
                            <tr>
                                <!-- Flier -->
                                <th style="background-color: #fed8c8; color: #000; font-size: 13px; min-width: 110px;">Flier Distributed (Nos.)</th>
                                <th style="background-color: #fed8c8; color: #000; font-size: 13px; min-width: 130px;">Location</th>
                                
                                <!-- Calling -->
                                <th style="background-color: #f7eceb; color: #000; font-size: 13px; min-width: 110px;">No of person called</th>
                                <th style="background-color: #f7eceb; color: #000; font-size: 13px; min-width: 110px;">Success Call</th>
                                
                                <!-- Social Media -->
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 110px;">No Blog Update/Upload</th>
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 115px;">Developer Site update/upload</th>
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 110px;">Update/Upload Facebook</th>
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 110px;">Update/Upload Instagram</th>
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 110px;">Update/Upload Linkedin</th>
                                <th style="background-color: #b3e7fb; color: #000; font-size: 13px; min-width: 120px;">Nos of lead Generated/Location</th>
                                
                                <!-- Visit -->
                                <th style="background-color: #e2f7e6; color: #000; font-size: 13px; min-width: 110px;">Nos of Developer</th>
                                <th style="background-color: #e2f7e6; color: #000; font-size: 13px; min-width: 150px;">Developer Name</th>
                                
                                <!-- Event -->
                                <th style="background-color: #fff3cd; color: #000; font-size: 13px; min-width: 150px;">Event Address</th>
                                <th style="background-color: #fff3cd; color: #000; font-size: 13px; min-width: 150px;">No. of Enquiry Generated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dprData as $shift => $dpr)
                                <tr class="shift-row align-middle">
                                    <!-- Shift Name label -->
                                    <td class="font-weight-bold text-uppercase align-middle bg-light text-dark font-size-15" style="border-right: 2px solid #dee2e6;">
                                        {{ $shift }}
                                    </td>
                                    
                                    <!-- Start Time -->
                                    <td class="p-1 align-middle">
                                        <input type="time" name="shifts[{{ $shift }}][start_time]" 
                                               class="form-control text-center dpr-input" 
                                               placeholder="e.g. 09:00 AM" 
                                               value="{{ old('shifts.'.$shift.'.start_time', $dpr->start_time) }}">
                                    </td>
                                    
                                    <!-- End Time -->
                                    <td class="p-1 align-middle">
                                        <input type="time" name="shifts[{{ $shift }}][end_time]" 
                                               class="form-control text-center dpr-input" 
                                               placeholder="e.g. 01:00 PM" 
                                               value="{{ old('shifts.'.$shift.'.end_time', $dpr->end_time) }}">
                                    </td>
                                    
                                    <!-- Flier Distributed -->
                                    <td class="p-1 align-middle" style="background-color: #fff9f6;">
                                        <input type="text" name="shifts[{{ $shift }}][flier_distributed]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.flier_distributed', $dpr->flier_distributed) }}">
                                    </td>
                                    
                                    <!-- Flier Location -->
                                    <td class="p-1 align-middle" style="background-color: #fff9f6;">
                                        <input type="text" name="shifts[{{ $shift }}][location]" 
                                               class="form-control text-center dpr-input" placeholder="Location"
                                               value="{{ old('shifts.'.$shift.'.location', $dpr->location) }}">
                                    </td>
                                    
                                    <!-- No of Person Called -->
                                    <td class="p-1 align-middle" style="background-color: #fdfaf9;">
                                        <input type="text" name="shifts[{{ $shift }}][no_of_person_called]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.no_of_person_called', $dpr->no_of_person_called) }}">
                                    </td>
                                    
                                    <!-- Success Call -->
                                    <td class="p-1 align-middle" style="background-color: #fdfaf9;">
                                        <input type="text" name="shifts[{{ $shift }}][success_call]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.success_call', $dpr->success_call) }}">
                                    </td>
                                    
                                    <!-- No Blog Update/Upload -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][no_blog_update_upload]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.no_blog_update_upload', $dpr->no_blog_update_upload) }}">
                                    </td>
                                    
                                    <!-- Developer Site update/upload -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][developer_site_update_upload]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.developer_site_update_upload', $dpr->developer_site_update_upload) }}">
                                    </td>
                                    
                                    <!-- Update/Upload Facebook -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][update_upload_facebook]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.update_upload_facebook', $dpr->update_upload_facebook) }}">
                                    </td>
                                    
                                    <!-- Update/Upload Instagram -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][update_upload_instagram]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.update_upload_instagram', $dpr->update_upload_instagram) }}">
                                    </td>
                                    
                                    <!-- Update/Upload Linkedin -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][update_upload_linkedin]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.update_upload_linkedin', $dpr->update_upload_linkedin) }}">
                                    </td>
                                    
                                    <!-- Nos of lead Generated/Location -->
                                    <td class="p-1 align-middle" style="background-color: #f3fbfe;">
                                        <input type="text" name="shifts[{{ $shift }}][nos_of_lead_generated_location]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.nos_of_lead_generated_location', $dpr->nos_of_lead_generated_location) }}">
                                    </td>
                                    
                                    <!-- Nos of Developer -->
                                    <td class="p-1 align-middle" style="background-color: #f6fcf8;">
                                        <input type="text" name="shifts[{{ $shift }}][nos_of_developer]" 
                                               class="form-control text-center dpr-input numeric-input"
                                               value="{{ old('shifts.'.$shift.'.nos_of_developer', $dpr->nos_of_developer) }}">
                                    </td>
                                    
                                    <!-- Developer Name -->
                                    <td class="p-1 align-middle" style="background-color: #f6fcf8;">
                                        <input type="text" name="shifts[{{ $shift }}][developer_name]" 
                                               class="form-control text-center dpr-input" placeholder="Developer Name"
                                               value="{{ old('shifts.'.$shift.'.developer_name', $dpr->developer_name) }}">
                                    </td>
                                    
                                    <!-- Event Address -->
                                    <td class="p-1 align-middle" style="background-color: #fffdf5;">
                                        <input type="text" name="shifts[{{ $shift }}][event_address]" 
                                               class="form-control text-center dpr-input" placeholder="Event Address"
                                               value="{{ old('shifts.'.$shift.'.event_address', $dpr->event_address) }}">
                                    </td>
                                    
                                    <!-- No of Enquiry Generated -->
                                    <td class="p-1 align-middle" style="background-color: #fffdf5;">
                                        <!-- No numeric-input class so both alphabet & number can be entered -->
                                        <input type="text" name="shifts[{{ $shift }}][number_of_enquiry_generated]" 
                                               class="form-control text-center dpr-input" placeholder="Enquiries"
                                               value="{{ old('shifts.'.$shift.'.number_of_enquiry_generated', $dpr->number_of_enquiry_generated) }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer bg-light py-3 d-flex justify-content-between">
                    <span class="text-muted align-self-center"><i class="fas fa-info-circle mr-1"></i> Data will be directly viewable by the administrator upon saving.</span>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow"><i class="fas fa-save mr-1"></i> Save DPR Report</button>
                </div>
            </form>
        </div>
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
                            @if(auth()->user()->hasRole('Admin'))
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
        .dpr-input {
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
            background-color: transparent;
            height: 38px;
        }
        .dpr-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background-color: #ffffff;
            outline: none;
        }
        .dpr-input::placeholder {
            font-size: 12px;
            color: #adb5bd;
        }
        .numeric-input {
            padding-left: 5px;
            padding-right: 5px;
        }
        /* Remove arrows on number inputs */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        .shift-row:hover {
            background-color: rgba(0, 0, 0, 0.025);
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .table th, .table td {
            vertical-align: middle !important;
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
            // Auto select date input on load if focused
            $('#dpr_date').on('focus', function() {
                this.select();
            });
        });
    </script>
@stop

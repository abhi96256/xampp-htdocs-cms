@extends('adminlte::page')

@section('title', 'Lead Details')

@section('content_header')
    <h1>Lead Details</h1>
    @can('enquiry_create')
    <div id="{{ $enquiry->id }}" class="my-3">
        @can('enquiry_delete')
        <button class="entry-delete-btn btn btn-danger text-uppercase float-right ml-2">
            <i class="fas fa-trash-alt fa-fw"></i>
            <span class="big-btn-text">Mark This Lead As Lost</span>
        </button>
        @endcan
        @can('enquiry_edit')
        @if($enquiry->enquiry_status->id < 4)
        <a class="btn btn-primary text-uppercase float-right ml-2" href="{{ route('enquiries.edit', ['id' => $enquiry->id]) }}">
            <i class="fas fa-edit fa-fw"></i>
            <span class="big-btn-text">Edit This Lead</span>
        </a>
        @endif
        @endcan
        @can('enquiry_create')
        <a class="btn btn-success text-uppercase float-right" href="{{ route('enquiries.create') }}">
            <i class="fas fa-plus fa-fw"></i>
            <span class="big-btn-text">Add New Lead</span>
        </a>
        @endcan
    </div>
    <br><br>
    @endcan
@stop

@section('content')

    {{-- Lead Details --}}
    <div class="card px-3 py-2">
        <div class="row">
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>#</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $enquiry->id }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Name</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Contact No.</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->contact_no }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Alternate Contact Number</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->a_contact_no }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Email</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->email }}</span>
                    </li>
					<li class="list-group-item">
                        <span>Address</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->address }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Remarks</span>:
                        <span class="pl-1 font-weight-bolder">
                           @if(isset($remarks->remarks)) {{ $remarks->remarks}} else {{''}} @endif
                        </span>
                    </li>
                </ul>

            </div>
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Project</span>:
                        <span class="pl-1 font-weight-bolder">
                            @if(isset($enquiry->project->name))
                            {{ $enquiry->project->name }}
                            @endif
                        </span>
                    </li>
                    @if(isset($enquiry->project_1->name))
                    <li class="list-group-item">
                        <span>Project 1</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->project_1->name }}</span>
                    </li>
                    @endif
                    @if(isset($enquiry->project_2->name))
                    <li class="list-group-item">
                        <span>Project 2</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->project_2->name }}</span>
                    </li>
                    @endif
                    @if(isset($enquiry->project_3->name))
                    <li class="list-group-item">
                        <span>Project 3</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->project_3->name }}</span>
                    </li>
                    @endif
                    @if(isset($enquiry->project_4->name))
                    <li class="list-group-item">
                        <span>Project 4</span>:
                        <span class="pl-1 font-weight-bolder">{{ $enquiry->project_4->name }}</span>
                    </li>
                    @endif
                    <li class="list-group-item">
                        <span>Configuration</span>:
                        <span class="pl-1 font-weight-bolder">
                            @if(isset($enquiry->configuration->name))
                            {{ $enquiry->configuration->name }}
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Budget</span>:
                        <span class="pl-1 font-weight-bolder">
                            @if(isset($enquiry->budget_range))
                            {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $enquiry->budget_range }}
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Status</span>:
						@if($enquiry->enquiry_status)
                        <span class="pl-1 font-weight-bolder {{ App\Lancer\Utilities::getEnquiryStatusStyle($enquiry->enquiry_status->id) }}">
                            {{ $enquiry->enquiry_status->status }}
                        </span>
						@endif
                    </li>
                    <li class="list-group-item">
                        <span>Assigned To</span>:
						@if($enquiry->assignedTo)
                        <span class="pl-1 font-weight-bolder">
                            {{ $enquiry->assignedTo->name }}
                        </span>
						@endif
                    </li>
                </ul>

            </div>
        </div>

    </div>

    {{-- Lead Status And Project Interest Update --}}
    <div class="card px-3 py-2">
        <h5>Update Status Or Project</h5>
        <form method="post" action="{{ route('enquiries.updateStatus', ['id' => $enquiry->id]) }}">
            @csrf
            <div class="row">
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="enquiry_status">Status</label>
                    <select class="form-control js-example-basic-single" id="enquiry_status" name="enquiry_status" @if($enquiry->enquiry_status->id > 3) disabled @endif required>
                        @foreach ($enquiry_statuses as $status)
                            <option value="{{ $status->id }}" @if(isset($enquiry)) @if($status->id == $enquiry->enquiry_status->id) selected @endif @endif>{{ $status->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="project">Project</label>
                    <select class="form-control js-example-basic-single" id="project" name="project" @if($enquiry->enquiry_status->id > 3) disabled @endif required>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @if(isset($enquiry->project->id)) @if($project->id == $enquiry->project->id) selected @endif @endif>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                @if($enquiry->enquiry_status->id < 4)
                    <input type="submit" class="btn btn-success" value="Update">
                    <a class="btn btn-danger ml-3" href="">Cancel</a>
                @endif
            </div>
        </form>
    </div>


    {{-- Follow Ups --}}
    <div class="card px-3 py-2">
        <div>
            @can('followup_create')
                <button class="add-followup-btn btn btn-success text-uppercase float-right">
                    <i class="fas fa-fw fa-plus"></i> Add Follow Up
                </button>
            @endcan
        </div>

        <div class="container">
            <h5>Follow Ups</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="content">
                                <ul class="timeline">
                                    @foreach ($followups as $followup)
                                    <li id="{{ $followup->id }}" class="event" data-date="{{ $followup->date_time->format('d-M-Y - h:i a') }}">
                                        @if($followup->outcome != null)
                                            <h3><b>Outcome: {{ $followup->outcome ?? 'None' }}</b></h3>
                                        @endif
                                        <p>{{ $followup->remark }}</p>
                                        @can('followup_edit')
                                        <button type="button" class="follow-up-edit-btn btn btn-primary">
                                            <i class="fas fa-edit fa-fw"></i>
                                        </button>
                                        <input type="hidden" id="editfollowupurl{{ $followup->id }}" value="{{ route('followups.update', ['id' => $followup->id]) }}">
                                        @endcan
                                        @can('followup_delete')
                                        <button type="button" class="follow-up-delete-btn btn btn-danger ml-2">
                                            <i class="fas fa-trash-alt fa-fw"></i>
                                        </button>
                                        <input type="hidden" id="deletefollowupurl{{ $followup->id }}" value="{{ route('followups.destroy', ['id' => $followup->id]) }}">
                                        @endcan
                                    </li>

                                    {{-- Below values are required for edit and delete operations done via fetch calls --}}
                                    <input type="hidden" id="date_time{{ $followup->id }}"
                                    value="{{ $followup->date_time->format('Y-m-d') }}T{{ $followup->date_time->format('H:m') }}">
                                    <input type="hidden" id="remark{{ $followup->id }}" value="{{ $followup->remark }}">
                                    <input type="hidden" id="outcome{{ $followup->id }}" value="{{ $followup->outcome }}">
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Required for mark delete action --}}
    <input type="hidden" id="deleteUrl{{ $enquiry->id }}" value="{{ route('enquiries.destroy', ['id' => $enquiry->id]) }}">
    <input type="hidden" id="deletedBtnText" value="Yes, mark it!">
    <input type="hidden" id="deletedTitle" value="Marked as lost!">
    <input type="hidden" id="deletedMsg" value="The selected enquiry has been successfully marked as lost.">

    {{-- Required for close deal action --}}
    <input type="hidden" id="closedRedirectUrl" value="{{ route('enquiries.index') }}">

    {{-- Required for followup related actions --}}
    <input type="hidden" id="addfollowupurl" value="{{ route('followups.store') }}">
    <input type="hidden" id="enquiryid" value="{{ $enquiry->id }}">

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/timeline.css') }}">
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/followups_swal.js') }}"></script>
@stop

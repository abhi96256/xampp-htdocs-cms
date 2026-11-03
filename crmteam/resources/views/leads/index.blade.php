@extends('adminlte::page')

@section('title', 'All Leads')

@section('content_header')
    <h1>All Leads</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('project_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('leads.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New Lead</span>
            </a>
        </div>
        @endcan
        <input type="text" id="searchBox" placeholder="🔍 Search the table below">
        <br>

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Name</th>
                        <th class="text-uppercase" scope="col">Details</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                    <tr>
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->details }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $lead->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('project_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('leads.show', ['id' => $lead->id]) }}">View</a>
                                    @endcan
                                    @can('project_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('leads.edit', ['id' => $lead->id]) }}">Edit</a>
                                    @endcan
                                    @can('project_delete')
                                    <div class="dropdown-divider"></div>
                                    @if(!$lead->is_active)
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Lead
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $lead->id }}" value="{{ route('leads.destroy', ['id' => $lead->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="Your request has been successfully completed.">

                </tbody>
            </table>
            @if (count($leads) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $leads->links() }}
        </div>

    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop

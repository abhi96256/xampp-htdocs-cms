@extends('adminlte::page')

@section('title', 'All Users')

@section('content_header')
    <h1>All Users</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('user_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('useraccounts.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New User</span>
            </a>
        </div>
        @endcan
        <br>

        <div class="table-responsive">
            <table class="table" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col" style="display:none;">#</th>
                        <th class="text-uppercase" scope="col">Name</th>
                        <th class="text-uppercase" scope="col">Email</th>
                        <th class="text-uppercase" scope="col">Photo</th>
                        <th class="text-uppercase" scope="col">Role</th>
                        <th class="text-uppercase" scope="col">Status</th>

                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td style="display:none;">{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->photo_url)
                                <a href="{{ $user->photo_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $user->photo_url }}" alt='profile photo'
                                    class="img-thumbnail" style="height: 42px; width: 42px; object-fit: cover;" />
                                </a>
                            @else
                                <span class="text-secondary">
                                    <i class="fas fa-user-circle fa-lg"></i>
                                </span>
                            @endif
                        </td>
                        <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                        <td>
                            @if($user->status == 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $user->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('useraccounts.show', ['id' => $user->id]) }}">View</a>
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('useraccounts.edit', ['id' => $user->id]) }}">Edit</a>
                                    @if(auth()->user()->hasRole('Admin') && $user->status != 1)
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-success"
                                            href="{{ route('useraccounts.accept', ['id' => $user->id]) }}"><i class="fas fa-check-circle"></i> Accept</a>
                                        <a class="dropdown-item text-danger"
                                            href="{{ route('useraccounts.decline', ['id' => $user->id]) }}"
                                            onclick="return confirm('Are you sure you want to decline and remove this user?');"><i class="fas fa-times-circle"></i> Decline</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $user->id }}" value="{{ route('useraccounts.destroy', ['id' => $user->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
					 
    <input type="hidden" id="closedRedirectUrl" value="{{ route('useraccounts.index') }}">
    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
    <input type="hidden" id="deletedTitle" value="Deleted!">
    <input type="hidden" id="deletedMsg" value="The selected user was successfully deleted.">
                   

                </tbody>
            </table>
            @if (count($users) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
@stop

@section('js')
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
    $(document).ready(function() {
        // Setup - add a text input to each header cell
        $('#example thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#example thead');

        var table = $('#example').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            paging: false, // Disable DataTables paging since we use Laravel pagination
            info: false,   // Hide info since we use Laravel pagination
            layout: {
                topStart: null
            },
            initComplete: function() {
                var api = this.api();
                
                // For each column
                api.columns().every(function() {
                    var column = this;
                    var index = column.index();
                    var cell = $('.filters th').eq(index);
                    var title = $(column.header()).text();
                    
                    // Create input element
                    var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                    cell.html(input);

                    // On keyup/change/clear, search the column
                    $(input).on('keyup change clear', function() {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
            }
        });
    });
    </script>
    <style>
        .dt-search { display: none; }
        .filters input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filters th {
            padding: 8px 5px !important;
            background-color: #f8f9fa !important;
        }

        /* Sidebar Scrollbar Force Visibility */
        .os-scrollbar-vertical {
            width: 6px !important;
            display: block !important;
        }
        .os-scrollbar-handle {
            background: #ff0000 !important;
            border-radius: 10px !important;
            opacity: 1 !important;
            height: 50px !important;
        }
        .os-theme-dark>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle {
            background: #ff0000 !important;
        }
    </style>
@stop

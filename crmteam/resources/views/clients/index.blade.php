@extends('adminlte::page')

@section('title', 'Closed Clients')

@section('content_header')
    <h1>Closed Clients</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- Removed Add New Client button as per requirement 16 --}}
        {{-- Removed generic searchbox, using DataTables --}}
        
        <div class="table-responsive">
            <table class="table" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col" style="display:none;">#</th>
                        <th class="text-uppercase" scope="col">Name</th>
                        <th class="text-uppercase" scope="col">Contact No.</th>
                        <th class="text-uppercase" scope="col">Subject</th>
                        <th class="text-uppercase" scope="col">Business Name</th>
                        <th class="text-uppercase" scope="col">Email</th>
                        <th class="text-uppercase" scope="col">Status</th>
                        <th class="text-uppercase" scope="col">Closed By</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td style="display:none;">{{ $client->id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->contact_no }}</td>
                        <td>{{ $client->subject }}</td>
                        <td>{{ $client->business_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            <span class="{{ App\Lancer\Utilities::getClientStatusStyle($client->is_active) }}">
                                {{ App\Lancer\Utilities::getClientStatus($client->is_active) }}
                            </span>
                        </td>
                        <td>
                            {{ $client->closedBy->name ?? ($client->closed_by_name ?? 'N/A') }}
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $client->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('client_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('clients.show', ['id' => $client->id]) }}">View</a>
                                    @endcan
                                    @can('client_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('clients.edit', ['id' => $client->id]) }}">Edit</a>
                                    <a class="dropdown-item text-warning"
                                        href="{{ route('clients.convertToLead', ['id' => $client->id]) }}" onclick="return confirm('Are you sure you want to convert this client back to a lead?')">Convert to Lead</a>
                                    @endcan
                                    @can('client_delete')
                                    @if(!$client->is_active)
                                        <div class="dropdown-divider"></div>
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Client
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $client->id }}" value="{{ route('clients.destroy', ['id' => $client->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="Your request has been successfully completed.">

                </tbody>
            </table>
            @if (count($clients) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $clients->links() }}
        </div>

    </div>
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
    
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    
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
            autoWidth: false,
            order: [[0, 'desc']],
            layout: {
                topEnd: {
                    buttons: ['csv', 'excel', 'pdf', 'print']
                }
            },
            initComplete: function() {
                var api = this.api();
                
                // For each column
                api.columns().every(function() {
                    var column = this;
                    var index = column.index();
                    var cell = $('.filters th').eq(index);
                    var title = $(column.header()).text().trim();
                    
                    if (title.toLowerCase() !== 'actions') {
                        // Create input element
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                        cell.html(input);

                        // On keyup/change/clear, search the column
                        $(input).on('keyup change clear', function() {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    } else {
                        cell.html('');
                    }
                });
            }
        });
    });
    </script>
    <style>
        .dt-search { display: none; }
        .dt-container { width: 100% !important; }
        .dt-layout-row { width: 100% !important; display: flex !important; justify-content: space-between !important; align-items: center !important; }
        .dt-layout-table { width: 100% !important; overflow-x: auto !important; }
        #example, table.dataTable { width: 100% !important; min-width: 100% !important; margin: 0 !important; }
        .dt-buttons { margin-bottom: 10px; }
        .filters input {
            width: 100%;
            padding: 4px 6px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        .filters th {
            padding: 6px 4px !important;
            background-color: #f8f9fa !important;
        }
    </style>
@stop

@extends('adminlte::page')

@section('title', 'All Invoices')

@section('content_header')
    <h1>All Invoices</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        <div class="row align-items-center mb-3">
            <div class="col-md-3">
              <form id="fyForm" action="{{ route('invoices.index') }}" method="GET">
                <label class="font-weight-bold">Financial Year:</label>
                <select class="form-control" name="fyFilter" id="fyFilter" onchange="document.getElementById('fyForm').submit();">
                    <option value="">All Years</option>
                    @php $currentYear = date('Y') - 5; @endphp
                    @for($i = $currentYear; $i <= 2050; $i++)
                        @php $fy = $i . '-' . substr($i+1, 2, 2); @endphp
                        <option value="{{ $fy }}" {{ request('fyFilter') == $fy ? 'selected' : '' }}>{{ $fy }}</option>
                    @endfor
                </select>
              </form>
            </div>
            
            <div class="col-md-9 text-right mt-4">
                <a href="{{ route('invoices.import') }}" class="btn btn-warning text-uppercase font-weight-bold mx-1">
                    <i class="fas fa-upload"></i> IMPORT
                </a>
                <a href="{{ route('invoices.export', request()->all()) }}" class="btn btn-primary text-uppercase font-weight-bold mx-1">
                    <i class="fas fa-file-csv"></i> EXPORT
                </a>
                @can('client_create')
                <a href="{{ route('invoices.create') }}" class="btn btn-success text-uppercase font-weight-bold mx-1">
                    <i class="fas fa-plus fa-fw"></i> ADD NEW INVOICE
                </a>
                @endcan
            </div>
        </div>

        <input type="text" class="form-control mb-3" id="searchBox" placeholder="🔍 Search the table below">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Bill No</th>
                        <th class="text-uppercase" scope="col">Bill Date</th>
                        <th class="text-uppercase" scope="col">Company/Developer</th>
                        <th class="text-uppercase" scope="col">Property Amount</th>
                        <th class="text-uppercase" scope="col">Bill Status</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->bill_no }}</td>
                        <td>{{ $client->bill_date }}</td>
                        <td>{{ $client->company_developer }}</td>
                        <td>{{ $client->property_amount }}</td>
                        <td>{{ $client->bill_status }}</td>
                       
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $client->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('client_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('invoices.download', ['id' => $client->id]) }}">Download</a>
                                    @endcan
                                    @can('client_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('invoices.edit', ['id' => $client->id]) }}">Edit</a>
                                    @endcan
                                    @can('client_delete')
                                    @if(!$client->is_active)
                                     <!--    <div class="dropdown-divider"></div>
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Client
                                        </a> -->
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
@stop

@extends('adminlte::page')
@section('title', 'Monthly Visits')

@section('content_header')
<div class="row">
    <div class="col-md-12"><h1 class="m-0 text-dark">📍 Monthly Visits</h1></div>
</div>
@stop

@section('content')
<div class="card px-3 py-2">
    <div class="my-3 clearfix sticky-visit-header">
        @can('site_visits create')
        <a href="{{ route('monthly_visits.create') }}" class="btn btn-success text-uppercase float-right ml-2">
            <i class="fas fa-plus fa-fw"></i>
            <span class="big-btn-text">Add New Visit</span>
        </a>
        @endcan
    </div>

    {{-- Date Range Filter --}}
    <div class="row mb-3 align-items-end" id="date-filter-bar">
        <div class="col-md-2">
            <label class="font-weight-bold">Filter by Date:</label>
            <select class="form-control" id="date-preset">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
        <div class="col-md-2" id="custom-from-wrap" style="display:none;">
            <label>From</label>
            <input type="date" class="form-control" id="custom-from">
        </div>
        <div class="col-md-2" id="custom-to-wrap" style="display:none;">
            <label>To</label>
            <input type="date" class="form-control" id="custom-to">
        </div>
        <div class="col-md-2" id="custom-apply-wrap" style="display:none;">
            <label>&nbsp;</label>
            <button class="btn btn-primary form-control" id="apply-custom">Apply</button>
        </div>
    </div>
    <br>

    <div class="table-responsive">
        <table class="table table-striped table-valign-middle" id="example">
            <thead class="thead-dark">
                <tr>
                    <th class="text-uppercase" scope="col">#</th>
                    <th class="text-uppercase" scope="col">User</th>
                    <th class="text-uppercase" scope="col">Client Info</th>
                    <th class="text-uppercase" scope="col">RM Names</th>
                    <th class="text-uppercase" scope="col">Sites Visited</th>
                    <th class="text-uppercase" scope="col">Dates</th>
                    <th class="text-uppercase" scope="col">Remarks</th>
                    <th class="text-uppercase" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $visit)
                <tr class="visit-row"
                    data-date="{{ $visit->created_at ? $visit->created_at->format('Y-m-d') : '' }}">
                    <td>{{ $visit->id }}</td>
                    <td><strong>{{ collect(explode(' ', $visit->user->name ?? ''))->first() }}</strong></td>
                    
                    <td>
                        @if($visit->client_name)<strong>{{ $visit->client_name }}</strong><br>@endif
                        @if($visit->email_id)<small class="text-muted"><i class="fas fa-envelope"></i> {{ $visit->email_id }}</small><br>@endif
                        @if($visit->contact_no)<small class="text-muted"><i class="fas fa-phone"></i> {{ $visit->contact_no }}</small>@endif
                    </td>
                    
                    <td>
                        @if($visit->room_name) <div class="badge badge-info">1</div> {{ $visit->room_name }} <br> @endif
                        @if($visit->room_name_2) <div class="badge badge-info mt-1">2</div> {{ $visit->room_name_2 }} <br> @endif
                        @if($visit->room_name_3) <div class="badge badge-info mt-1">3</div> {{ $visit->room_name_3 }} <br> @endif
                        @if($visit->room_name_4) <div class="badge badge-info mt-1">4</div> {{ $visit->room_name_4 }} <br> @endif
                        @if($visit->room_name_5) <div class="badge badge-info mt-1">5</div> {{ $visit->room_name_5 }} <br> @endif
                        @if($visit->room_name_6) <div class="badge badge-info mt-1">6</div> {{ $visit->room_name_6 }} @endif
                    </td>
                    <td>
                        @if($visit->site_visited) <div class="badge badge-warning">1</div> {{ $visit->site_visited }} <br> @endif
                        @if($visit->site_visited_2) <div class="badge badge-warning mt-1">2</div> {{ $visit->site_visited_2 }} <br> @endif
                        @if($visit->site_visited_3) <div class="badge badge-warning mt-1">3</div> {{ $visit->site_visited_3 }} <br> @endif
                        @if($visit->site_visited_4) <div class="badge badge-warning mt-1">4</div> {{ $visit->site_visited_4 }} <br> @endif
                        @if($visit->site_visited_5) <div class="badge badge-warning mt-1">5</div> {{ $visit->site_visited_5 }} <br> @endif
                        @if($visit->site_visited_6) <div class="badge badge-warning mt-1">6</div> {{ $visit->site_visited_6 }} @endif
                    </td>
                    <td>
                        @if($visit->date_of_visit) <div class="badge badge-secondary">1</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit)->format('d-M-Y') }}</small> <br> @endif
                        @if($visit->date_of_visit_2) <div class="badge badge-secondary mt-1">2</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit_2)->format('d-M-Y') }}</small> <br> @endif
                        @if($visit->date_of_visit_3) <div class="badge badge-secondary mt-1">3</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit_3)->format('d-M-Y') }}</small> <br> @endif
                        @if($visit->date_of_visit_4) <div class="badge badge-secondary mt-1">4</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit_4)->format('d-M-Y') }}</small> <br> @endif
                        @if($visit->date_of_visit_5) <div class="badge badge-secondary mt-1">5</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit_5)->format('d-M-Y') }}</small> <br> @endif
                        @if($visit->date_of_visit_6) <div class="badge badge-secondary mt-1">6</div> <small>{{ \Carbon\Carbon::parse($visit->date_of_visit_6)->format('d-M-Y') }}</small> @endif
                    </td>
                    <td>{{ $visit->remarks }}</td>
                    
                    <td>
                        @can('site_visits edit')
                        <a href="{{ route('monthly_visits.edit', $visit->id) }}" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        @can('site_visits delete')
                        <form action="{{ route('monthly_visits.destroy', $visit->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this visit?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if(count($visits) < 1)
            <div class="px-4 py-5 mx-auto text-secondary text-center">
                No visits recorded yet.
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
<style>
    .dt-search { display: none; }
    .dt-layout-cell { overflow-x: auto; }
    .dt-buttons { float: right; margin-bottom: 10px; }
    .dt-buttons .dt-button {
        background-color: #000 !important;
        color: #fff !important;
        padding: 5px 12px !important;
        border-radius: 4px !important;
        border: none !important;
        transition: all 0.3s !important;
    }
    .dt-buttons .dt-button:hover {
        background-color: #333 !important;
        transform: translateY(-1px);
    }
    .filters input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s ease-in-out;
    }
    .filters input:focus {
        outline: none !important;
        border: 2px solid #007bff !important;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.4) !important;
        transform: scale(1.05);
        background-color: #f0f8ff !important;
        z-index: 10;
        position: relative;
    }
    .filters th {
        padding: 8px 5px !important;
        background-color: #f8f9fa !important;
    }
    .sticky-visit-header {
        position: -webkit-sticky;
        position: sticky;
        top: 55px;
        z-index: 1020;
        background-color: #fff;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 6px -6px rgba(0,0,0,0.3);
    }
</style>
@stop

@section('js')
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
        order: [],
        layout: {
            topEnd: {
                buttons: ['csv', 'excel', 'pdf', 'print']
            }
        },
        initComplete: function() {
            var api = this.api();
            
            api.columns().every(function() {
                var column = this;
                var index = column.index();
                var cell = $('.filters th').eq(index);
                var title = $(column.header()).text().trim();
                
                if (title.toLowerCase() !== 'actions' && title.toLowerCase() !== 'images') {
                    var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                    cell.html(input);

                    $(input).on('keyup change clear', function() {
                        var val = this.value;
                        if (title.toLowerCase() === 'user' || title.toLowerCase() === 'username') {
                            val = $.fn.dataTable.util.escapeRegex(val);
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        } else {
                            if (column.search() !== val) {
                                column.search(val).draw();
                            }
                        }
                    });
                } else {
                    cell.html('');
                }
            });
        }
    });

    // ─── DATE RANGE FILTER ─────────────────────────────────────────
    var filterFromDate = null;
    var filterToDate = null;

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex, rowData, counter) {
            // we attached data-date on the tr, let's get it via table's node
            var rowNode = table.row(dataIndex).node();
            var rowDateStr = $(rowNode).data('date');

            if (!filterFromDate && !filterToDate) {
                return true;
            }

            if (!rowDateStr) return true; // if no date, maybe keep it or hide it? Let's keep it

            var parts = rowDateStr.split('-');
            var itemDate = new Date(parts[0], parts[1] - 1, parts[2]);

            if (filterFromDate && !filterToDate) {
                return itemDate >= filterFromDate;
            } else if (!filterFromDate && filterToDate) {
                return itemDate <= filterToDate;
            } else {
                return itemDate >= filterFromDate && itemDate <= filterToDate;
            }
        }
    );

    function applyDateFilter(from, to) {
        filterFromDate = from;
        filterToDate = to;
        table.draw();
    }

    function getPresetDates(preset) {
        var now = new Date();
        var from, to;
        to = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

        if (preset === 'today') {
            from = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        } else if (preset === 'week') {
            var day = now.getDay();
            from = new Date(now.getFullYear(), now.getMonth(), now.getDate() - day);
        } else if (preset === 'month') {
            from = new Date(now.getFullYear(), now.getMonth(), 1);
        } else if (preset === 'year') {
            from = new Date(now.getFullYear(), 0, 1);
        } else {
            from = null; to = null;
        }
        return { from: from, to: to };
    }

    $('#date-preset').on('change', function() {
        var val = $(this).val();
        if (val === 'custom') {
            $('#custom-from-wrap, #custom-to-wrap, #custom-apply-wrap').show();
        } else {
            $('#custom-from-wrap, #custom-to-wrap, #custom-apply-wrap').hide();
            var dates = getPresetDates(val);
            applyDateFilter(dates.from, dates.to);
        }
    });

    $('#apply-custom').on('click', function() {
        var from = $('#custom-from').val() ? new Date($('#custom-from').val()) : null;
        var to   = $('#custom-to').val()   ? new Date($('#custom-to').val() + 'T23:59:59') : null;
        applyDateFilter(from, to);
    });
});
</script>
@stop

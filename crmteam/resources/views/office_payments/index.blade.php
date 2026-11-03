@extends('adminlte::page')

@section('title', 'All Office Payments')

@section('content_header')
    <h1>All Office Payments</h1>
@stop

@section('content')
    <div class="card px-3 py-3 mt-3">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="font-weight-bold">Financial Year:</label>
                <select class="form-control" id="fyFilter">
                    <option value="">All Years</option>
                    @php $currentYear = date('Y') - 5; @endphp
                    @for($i = $currentYear; $i <= 2050; $i++)
                        @php $fy = $i . '-' . substr($i+1, 2, 2); @endphp
                        <option value="{{ $fy }}">{{ $fy }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-9 text-right">
                <a href="{{ route('office_payment.import') }}" class="btn btn-warning mt-4">
                    <i class="fas fa-upload"></i> IMPORT
                </a>
                <a href="{{ route('office_payment.export') }}" class="btn btn-primary mt-4">
                    <i class="fas fa-file-csv"></i> EXPORT
                </a>
                <a href="{{ route('office_payment.create') }}" class="btn btn-success mt-4">
                    <i class="fas fa-plus"></i> ADD NEW OFFICE PAYMENT
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="officePaymentsTable">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>#</th>
                        <th>DATE</th>
                        <th>FINANCIAL YEAR</th>
                        <th>LOCATION</th>
                        <th>CASH</th>
                        <th>CHEQUE</th>
                        <th>ONLINE</th>
                        <th>TOPOLOGY</th>
                        <th>IN FAVOUR OF</th>
                        <th>REMARKS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d-M-Y') : '' }}</td>
                            <td>{{ $payment->financial_year }}</td>
                            <td>{{ $payment->location }}</td>
                            <td>{{ $payment->cash_amount ? App\Lancer\Utilities::CURRENCY_SYMBOL . $payment->cash_amount : '-' }}</td>
                            <td>
                                @if($payment->cheque_amount)
                                    <div style="font-size: 0.9rem;">
                                        <div class="mb-1 text-nowrap"><i class="fas fa-money-bill-wave text-success mr-1"></i><b>Amount:</b> {{ App\Lancer\Utilities::CURRENCY_SYMBOL . $payment->cheque_amount }}</div>
                                        <div class="mb-1 text-nowrap"><i class="fas fa-money-check text-info mr-1"></i><b>Cheque No:</b> <span class="text-muted">{{ $payment->cheque_nos ?: 'N/A' }}</span></div>
                                        <div class="text-nowrap"><i class="far fa-calendar-alt text-primary mr-1"></i><b>Date:</b> <span class="text-muted">{{ $payment->cheque_date ? \Carbon\Carbon::parse($payment->cheque_date)->format('d-M-Y') : 'N/A' }}</span></div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->online_amount)
                                    <div style="font-size: 0.9rem;">
                                        <div class="mb-1 text-nowrap"><i class="fas fa-money-bill-wave text-success mr-1"></i><b>Amount:</b> {{ App\Lancer\Utilities::CURRENCY_SYMBOL . $payment->online_amount }}</div>
                                        <div class="text-nowrap"><i class="fas fa-receipt text-warning mr-1"></i><b>UTR:</b> <span class="text-muted">{{ $payment->utr_nos ?: 'N/A' }}</span></div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $payment->topology }}</td>
                            <td>{{ $payment->in_favour_of }}</td>
                            <td>{{ $payment->remarks }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ACTIONS
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('office_payment.edit', $payment->id) }}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="post" action="{{ route('office_payment.destroy', $payment->id) }}" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <style>
        .dataTables_filter { display: none; }
        .dataTables_length { display: none; }
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
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup - add a text input to each header cell
            $('#officePaymentsTable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#officePaymentsTable thead');

            var table = $('#officePaymentsTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[0, "asc"]],
                "initComplete": function() {
                    var api = this.api();
                    
                    // For each column
                    api.columns().every(function() {
                        var column = this;
                        var index = column.index();
                        var cell = $('.filters th').eq(index);
                        var title = $(column.header()).text().trim();
                        
                        if (title.toLowerCase() !== 'actions') {
                            var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                            cell.html(input);

                            // On keyup/change/clear, search the column
                            $('input', cell).on('keyup change clear', function() {
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

            // Financial Year global filter
            $('#fyFilter').on('change', function() {
                // Column index 2 is 'FINANCIAL YEAR'
                table.column(2).search(this.value).draw();
            });
        });
    </script>
@stop

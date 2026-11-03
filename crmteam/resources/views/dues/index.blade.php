@extends('adminlte::page')

@section('title', 'All Dues')

@section('content_header')
    <h1>All Dues</h1>
@stop

@section('content')
    <!-- Top Stats Row -->
    <div class="row mb-3">
        <div class="col-12 text-right">
            <span id="total-dues-amount" class="btn font-weight-bold text-white shadow-sm" style="cursor: default; background-color: #f5222d; border-color: #f5222d; font-size: 1.1rem; padding: 10px 20px;">
                TOTAL DUES AMOUNT : {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ number_format($dues->sum('amount_paid'), 0) }}
            </span>
        </div>
    </div>

    <div class="card px-3 py-2">
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col" style="display:none;">#</th>
                        <th class="text-uppercase" scope="col">Username</th>
                        <th class="text-uppercase" scope="col">Expense Category</th>
                        <th class="text-uppercase" scope="col">Payee</th>
                        <th class="text-uppercase" scope="col">Amount</th>
                        <th class="text-uppercase" scope="col">Bill No & Date</th>
                        <th class="text-uppercase" scope="col">Attachments</th>
                        <th class="text-uppercase" scope="col">User Status</th>
                        <th class="text-uppercase" scope="col">Admin Status</th>
                        <th class="text-uppercase" scope="col">Action Details</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dues as $expense)
                    <tr style="background-color: {{ $expense->status_by_admin === 'Rejected' ? '#ffebee' : '#fff3e0' }}">
                        <td style="display:none;">{{ $expense->id }}</td>
                        <td>{{ $expense->createdBy ? $expense->createdBy->name : 'N/A' }}</td>
                        <td>{{ $expense->expense_category ?? 'N/A' }}</td>
                        <td>{{ $expense->payee }}</td>
                        <td data-search="{{ $expense->amount_paid }}">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ number_format($expense->amount_paid, 0) }}</td>
                        <td data-search="{{ $expense->bill_no ?? 'N/A' }} {{ $expense->date_of_payment ? \Carbon\Carbon::parse($expense->date_of_payment)->format('d-M-Y') : '' }}">
                            Bill No: {{ $expense->bill_no ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $expense->date_of_payment ? \Carbon\Carbon::parse($expense->date_of_payment)->format('d-M-Y') : '' }}</small>
                        </td>
                        <td>
                            @if($expense->bill_attachment)
                                <a href="{{ asset('storage/galeryImages/' . $expense->bill_attachment) }}" target="_blank" class="btn btn-sm btn-info">View Bill</a>
                            @else
                                <span class="text-muted">No Attachment</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $expense->status === 'Paid' ? 'badge-success' : ($expense->status === 'Rejected' ? 'badge-danger' : 'badge-warning') }}">{{ $expense->status ?? 'Pending' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $expense->status_by_admin === 'Rejected' ? 'badge-danger' : 'badge-warning' }}">{{ $expense->status_by_admin }}</span>
                        </td>
                        <td>
                            @if($expense->status_by_admin === 'Rejected')
                                <small><b>Rejected On:</b><br>{{ $expense->admin_rejected_date ? \Carbon\Carbon::parse($expense->admin_rejected_date)->format('d-M-Y h:i A') : 'N/A' }}</small>
                            @elseif($expense->status_by_admin === 'Pending')
                                <small><b>Pending Since:</b><br>{{ $expense->updated_at ? \Carbon\Carbon::parse($expense->updated_at)->format('d-M-Y h:i A') : 'N/A' }}</small>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('expenses.edit', ['id' => $expense->id]) }}">Review Expense</a>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($dues) < 1)
                    <tr>
                        <td colspan="11" class="text-center text-secondary py-4">No rejected or pending expenses found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
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
        }
        .filters th {
            padding: 8px 5px !important;
            background-color: #f8f9fa !important;
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
        $('#example thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#example thead');

        var table = $('#example').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            layout: {
                topStart: {
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
                    
                    if (title.toLowerCase() !== 'actions') {
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                        cell.html(input);

                        $(input).on('keyup change clear', function() {
                            var val = this.value;
                            var titleLower = title.toLowerCase();
                            
                            // Exact match for Username, Amount, and Payee
                            if (titleLower === 'username' || titleLower === 'amount' || titleLower === 'payee' || titleLower === 'expense category') {
                                val = $.fn.dataTable.util.escapeRegex(val);
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            } else {
                                // For other columns, use word boundary or partial match
                                if (column.search() !== val) {
                                    val = $.fn.dataTable.util.escapeRegex(val);
                                    column.search(val ? '\\b' + val + '\\b' : '', true, false).draw();
                                }
                            }
                        });
                    } else {
                        cell.html('');
                    }
                });
            }
        });

        // Recalculate Total Amount logic
        function calculateTotal() {
            var total = 0;
            // Check if any row is selected
            var selectedRows = table.rows('.selected-row', { search: 'applied' }).nodes();
            
            if (selectedRows.length > 0) {
                // Sum only selected rows
                selectedRows.each(function (row) {
                    var amountStr = $(row).find('td').eq(4).attr('data-search');
                    var amount = parseFloat(amountStr) || 0;
                    total += amount;
                });
            } else {
                // Sum all visible rows
                table.rows({ search: 'applied' }).nodes().each(function (row) {
                    var amountStr = $(row).find('td').eq(4).attr('data-search');
                    var amount = parseFloat(amountStr) || 0;
                    total += amount;
                });
            }
            var formattedTotal = total.toLocaleString('en-IN');
            $('#total-dues-amount').html('TOTAL DUES AMOUNT : {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} ' + formattedTotal);
        }

        table.on('draw', function () {
            calculateTotal();
        });



    });
    </script>
@stop

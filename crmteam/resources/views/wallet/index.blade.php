@extends('adminlte::page')

@section('title', 'Wallet')

@section('content_header')
    <h1>Wallet</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        <div class="my-3 clearfix">
            @can('payment_create')
            <a class="btn btn-success text-uppercase float-right ml-2" href="{{ route('wallet.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add Money</span>
            </a>
            @endcan
            <div class="btn text-uppercase float-right wallet-balance" id="wallet-display" style="background-color: #ff0000; color: #fff; font-weight: 600; text-align:right;">
                <span class="big-btn-text">Wallet Balance : {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} <span id="wallet-amount">{{ $total_wallet_balance }}</span></span>
                <span id="wallet-user-label" style="font-size:11px; display:block; opacity:0.85;"></span>
            </div>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col" style="display:none;">#</th>
                        <th class="text-uppercase" scope="col">User</th>
                        <th class="text-uppercase" scope="col">Amount</th>
                        <th class="text-uppercase" scope="col">Date</th>
                        <th class="text-uppercase" scope="col">Payee</th>
                        <th class="text-uppercase" scope="col">Added on </th>
                        <th class="text-uppercase" scope="col">Balance</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wallet as $due)
                    <tr class="wallet-row" data-username="{{ strtolower($due['name']) }}" data-user-id="{{ $due['user_id'] }}">
                        <td style="display:none;">{{ $due['id'] }}</td>
                        <td>{{ $due['name'] }}</td>
                        <td data-search="{{ $due['amount'] }}">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $due['amount'] }}</td>
                        <td>{{ $due['date'] ? \Carbon\Carbon::parse($due['date'])->format('d-M-Y') : 'N/A' }}</td>
                        <td>{{ $due['payee'] ?? 'N/A' }}</td>
                        <td>{{ $due['created_at'] }}</td>
                        <td data-search="{{ $due['balance'] }}">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $due['balance'] }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $due['id'] }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('payment_edit')
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('wallet.edit', ['id' => $due['id']]) }}">Edit</a>
                                    @endcan
                                    @can('payment_delete')
                                    <div class="dropdown-divider"></div>
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" data-id="{{ $due['id'] }}">
                                            Delete Balance
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $due['id'] }}" value="{{ route('wallet.destroy', ['id' => $due['id']]) }}">
                    @endforeach
                    
                    {{-- Required for delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="The selected balance entry was successfully deleted.">

                </tbody>
            </table>
            @if (count($wallet) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Hidden wallet data per user --}}
        <div id="user-wallet-data" style="display:none;" data-wallets='{{ json_encode($userWallets) }}'></div>

        {{-- Pagination links --}}
        <div class="mt-4">
            
        </div>

        <input type="hidden" id="closedRedirectUrl" value="{{ route('wallet.index') }}">
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/due_payment.js') }}"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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
            layout: {
                topStart: {
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

                        $(input).on('keyup change clear', function() {
                            var val = this.value;
                            var titleLower = title.toLowerCase();
                            // Exact match for User, Username, Amount, Balance, and Payee
                            if (titleLower === 'user' || titleLower === 'username' || titleLower === 'amount' || titleLower === 'balance' || titleLower === 'payee') {
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

        // Recalculate Total Wallet logic
        function calculateTotal() {
            var total = 0;
            // Check if any row is selected
            var selectedRows = table.rows('.selected-row', { search: 'applied' }).nodes();
            
            if (selectedRows.length > 0) {
                // Sum only selected rows (Column 2 is Amount)
                selectedRows.each(function (row) {
                    var amountStr = $(row).find('td').eq(2).attr('data-search');
                    var amount = parseFloat(amountStr) || 0;
                    total += amount;
                });
            } else {
                // Sum all visible rows
                table.rows({ search: 'applied' }).nodes().each(function (row) {
                    var amountStr = $(row).find('td').eq(2).attr('data-search');
                    var amount = parseFloat(amountStr) || 0;
                    total += amount;
                });
            }
            var formattedTotal = total.toLocaleString('en-IN');
            $('#wallet-amount').html(formattedTotal);
            $('#wallet-user-label').text('');
        }

        table.on('draw', function () {
            calculateTotal();
        });


    });
    </script>
    <style>
        .dt-search { display: none; }
        .dt-layout-row { text-align: right; }
        .dt-layout-row button {
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: none;
            transition: all 0.3s;
        }
        .dt-layout-row button:hover {
            background-color: #333;
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

@extends('adminlte::page')

@section('title', 'All Expenses')

@section('content_header')
<div class="row">
    <div class="col-md-12"><h1>All Expenses</h1></div>
</div>
@stop

@section('content')
    <div class="card px-3 py-2">
        <div class="my-3 clearfix sticky-expense-header">
            @can('payment_create')
            <a class="btn btn-success text-uppercase float-right ml-2" href="{{ route('expenses.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New Expense</span>
            </a>
            @endcan
            @if($wallet != 'No Wallet')
            <div class="btn wallet-balance text-uppercase float-right" id="wallet-display">
                <span class="big-btn-text">Wallet Balance : {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} <span id="wallet-amount">{{ $wallet }}</span></span>
                <span id="wallet-user-label" style="font-size:11px; display:block; opacity:0.85;"></span>
            </div>
            @endif
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
            <table class="table" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col" style="display:none;">#</th>
                        <th class="text-uppercase" scope="col">Username</th>
                        <th class="text-uppercase" scope="col">Expense Category</th>
                        <th class="text-uppercase" scope="col">Payee</th>
                        <th class="text-uppercase" scope="col">Location</th>
                        <th class="text-uppercase" scope="col">Amount Paid</th>
                        <th class="text-uppercase" scope="col">Date Of Payment</th>
                        <th class="text-uppercase" scope="col">Status (User)</th>
                        <th class="text-uppercase" scope="col">Paid Date (User)</th>
                        <th class="text-uppercase" scope="col">Rejected Date (User)</th>
                        <th class="text-uppercase" scope="col">Status (Admin)</th>
                        <th class="text-uppercase" scope="col">Paid Date (Admin)</th>
                        <th class="text-uppercase" scope="col">Rejected Date (Admin)</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr class="expense-row @if(strtolower($expense->status) == 'pending') table-warning @elseif(strtolower($expense->status) == 'paid') table-success @elseif(strtolower($expense->status) == 'rejected') table-danger @endif"
                        data-date="{{ $expense->date_of_payment->format('Y-m-d') }}"
                        data-username="{{ strtolower($expense->createdBy->name ?? '') }}"
                        data-user-id="{{ $expense->created_by }}"
                        style="cursor:pointer;">
                        <td style="display:none;">{{ $expense->id }}</td>
                        <td>{{ $expense->createdBy->name ?? 'N/A' }}</td>
                        <td>{{ $expense->expense_category ?? 'N/A' }}</td>
                        <td>{{ $expense->payee }}</td>
						<td>{{ $expense->address ?? 'N/A' }}</td>
                        <td data-search="{{ $expense->amount_paid }}">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $expense->amount_paid }}</td>
                        <td>{{ $expense->date_of_payment->format('d-M-Y') }}</td>
                        <td>{{ $expense->status ?? 'N/A' }}</td>
                        <td>{{ $expense->paid_date ? \Carbon\Carbon::parse($expense->paid_date)->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>{{ $expense->rejected_date ? \Carbon\Carbon::parse($expense->rejected_date)->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>{{ $expense->status_by_admin ?? 'N/A' }}</td>
                        <td>{{ $expense->admin_paid_date ? \Carbon\Carbon::parse($expense->admin_paid_date)->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>{{ $expense->admin_rejected_date ? \Carbon\Carbon::parse($expense->admin_rejected_date)->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $expense->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('payment_edit')
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('expenses.edit', ['id' => $expense->id]) }}">Edit</a>
                                    @endcan
                                    @can('payment_delete')
                                    <div class="dropdown-divider"></div>
                                    @if(!$expense->is_active)
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="cursor: pointer;"
                                           data-url="{{ route('expenses.destroy', ['id' => $expense->id]) }}">
                                            Delete This Expense
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    @endforeach

                    {{-- Required for delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="The selected expense was successfully deleted.">

                </tbody>
            </table>
            @if (count($expenses) < 1)
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

        <input type="hidden" id="closedRedirectUrl" value="{{ route('expenses.index') }}">
    </div>
@stop

@section('css')
@stop
<style>
.wallet-balance {
    background-color: #ff0000 !important;
    color: #fff !important;
    font-weight: 600 !important;
}
</style>
@section('js')
    <script src="{{ asset('js/table_utils.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js?v=1.1') }}"></script>
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
                    
                    if (title.toLowerCase() !== 'actions') {
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                        cell.html(input);

                        $(input).on('keyup change clear', function() {
                            var val = this.value;
                            var titleLower = title.toLowerCase();
                            
                            // Exact match for Username, Amount Paid, Payee, and Expense Category
                            if (titleLower === 'username' || titleLower === 'amount paid' || titleLower === 'payee' || titleLower === 'expense category') {
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

        // ─── DATE RANGE FILTER ─────────────────────────────────────────
        var allRows = [];
        $('#example tbody tr').each(function() {
            var dateStr = $(this).data('date');
            allRows.push({ row: this, date: dateStr ? new Date(dateStr) : null });
        });

        function applyDateFilter(from, to) {
            allRows.forEach(function(item) {
                if (!item.date) { $(item.row).show(); return; }
                var show = (!from || item.date >= from) && (!to || item.date <= to);
                // Use DataTables row show/hide via native row
                if (show) {
                    $(item.row).show();
                } else {
                    $(item.row).hide();
                }
            });
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
            updateWalletDisplay();
        });

        // ─── WALLET BALANCE TOTAL LOGIC ────────────────────────────────
        function updateWalletDisplay() {
            // 1. If row is selected via click, show specific user's balance
            if (selectedUserId !== null) {
                var found = null;
                var username = $('.expense-row[data-user-id="' + selectedUserId + '"]').first().data('username');
                $.each(walletsData, function(key, val) {
                    if (key === username) { found = val; return false; }
                });

                if (found) {
                    $('#wallet-amount').text(found.balance);
                    $('#wallet-user-label').text('(' + found.name + ' - Wallet Balance)');
                } else {
                    $('#wallet-amount').text('0');
                    $('#wallet-user-label').text('(' + (username || 'User') + ' - no wallet)');
                }
                return;
            }

            // 2. Are filters applied? (search or date)
            var isFiltered = false;
            if (table.search()) isFiltered = true;
            table.columns().every(function() { if (this.search()) isFiltered = true; });
            if ($('#date-preset').val() && $('#date-preset').val() !== '') {
                isFiltered = true;
            }

            // 3. If filtered, show sum of visible "Amount Paid"
            if (isFiltered) {
                var total = 0;
                table.rows({ search: 'applied' }).nodes().each(function (row) {
                    if ($(row).is(':visible')) {
                        // Only add to total if Status (User) is Paid
                        var statusText = $(row).find('td').eq(7).text().trim().toLowerCase();
                        if (statusText === 'paid') {
                            var amountStr = $(row).find('td').eq(5).attr('data-search');
                            var amount = parseFloat(amountStr) || 0;
                            total += amount;
                        }
                    }
                });
                $('#wallet-amount').text(total.toLocaleString('en-IN') + ' (Paid Only)');
                $('#wallet-user-label').text('(Filtered Amount Paid Total)');
            } else {
                // No filters, default behavior
                $('#wallet-amount').text(defaultWallet);
                $('#wallet-user-label').text('');
            }
        }

        table.on('draw', function() {
            updateWalletDisplay();
        });

        var walletsData = {};
        try {
            walletsData = JSON.parse($('#user-wallet-data').attr('data-wallets') || '{}');
        } catch(e) {}

        var defaultWallet   = $('#wallet-amount').text();
        var selectedUserId  = null;

        $('#date-preset').on('change', function() {
            setTimeout(updateWalletDisplay, 100);
        });

        $(document).on('click', '.expense-row', function() {
            var username = $(this).data('username');
            var userId   = $(this).data('user-id');

            // If clicking already-selected user: reset to total
            if (selectedUserId === userId) {
                selectedUserId = null;
                $('.expense-row').css('outline', '');
                updateWalletDisplay();
                return;
            }

            selectedUserId = userId;
            $('.expense-row').css('outline', '');
            $(this).css('outline', '4px solid red');
            
            updateWalletDisplay();
        });
    });
    </script>
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
        .sticky-expense-header {
            position: -webkit-sticky;
            position: sticky;
            top: 55px; /* Adjust based on navbar height, AdminLTE is usually 57px */
            z-index: 1020;
            background-color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px -6px rgba(0,0,0,0.3);
        }
    </style>
@stop

@extends('adminlte::page')

@section('title', 'Expenses Reports')

@section('content_header')
    <h1>Expenses Reports</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        <!--<div class="my-3">
			<div class="float-right ml-2">
			<form method="post" action="{{ route('enquiries.date_range') }}">
			@csrf
			<input type="submit" class="btn btn-success text-uppercase float-right ml-2" name="Filter"  />
			<input type="text" class=" float-right" name="daterange" Placeholder="Date Range Filter" />
			</div>
			<div class="float-right ml-2"> <label>Date Range</label>
			</form>
			</div>
           
        </div>
        <br>
-->
        <div class="table-responsive">
            <table class="table" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Payee</th>
                        <th class="text-uppercase" scope="col">Created By</th>
                        <th class="text-uppercase" scope="col">Amount</th>
                        <th class="text-uppercase" scope="col">Date of Payment </th>
                        <th class="text-uppercase" scope="col">Bill no</th>
                        <th class="text-uppercase" scope="col">Status Updated By</th>
                        <th class="text-uppercase" scope="col">Status</th>
                        <th class="text-uppercase" scope="col">Paid Date</th>
                        <th class="text-uppercase" scope="col">Rejected Date</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php 
				
				 $i= 0;?>
				@foreach($data as $d)
				<?php 
				 $i++;?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$d['payee']}}</td>
                        <td>{{$d['generated_by']}}</td>
                        <td>{{$d['amount']}}</td>
                        <td>{{$d['date_of_payment']}}</td>
                        <td>{{$d['bill_no']}}</td>
                        <td>@if(strtolower($d['status']) == 'paid' || strtolower($d['status']) == 'rejected' ){{$d['cleared_by']}} @endif</td>
                        <td>{{$d['status']}}</td>
                        <td>{{ !empty($d['paid_date']) ? \Carbon\Carbon::parse($d['paid_date'])->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>{{ !empty($d['rejected_date']) ? \Carbon\Carbon::parse($d['rejected_date'])->format('d-M-Y h:i A') : 'N/A' }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $d['id'] }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('payment_edit')
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('expenses.edit', ['id' => $d['id']]) }}">Edit</a>
                                    @endcan
                                    @can('payment_delete')
                                        <div class="dropdown-divider"></div>
                                        <a role="button" class="report-delete-btn dropdown-item text-danger"
                                            data-url="{{ route('expenses.destroy', ['id' => $d['id']]) }}">
                                            Delete This Expense
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                        
              @endforeach

                </tbody>
            </table>
          
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
          
        </div>

    </div>
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js?v=1.1') }}"></script>
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
                    var title = $(column.header()).text();
                    
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

        jQuery(function($){
            $('input[name="daterange"]').daterangepicker({
                locale: { format: 'YYYY-MM-DD' },
                startDate: '2024-05-01',
                endDate: '2024-05-31'
            });
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

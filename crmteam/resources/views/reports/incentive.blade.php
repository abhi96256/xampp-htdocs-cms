@extends('adminlte::page')

@section('title', 'Incentive Reports')

@section('content_header')
    <h1>Incentive Reports</h1>
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
                        <th class="text-uppercase" scope="col">Client name</th>
                        <th class="text-uppercase" scope="col">Project Name</th>
                        <th class="text-uppercase" scope="col">Agent Name</th>
                        <th class="text-uppercase" scope="col">Closed By</th>
                        <th class="text-uppercase" scope="col">Company Incentive %</th>
                        <th class="text-uppercase" scope="col">Incentive Amount</th>
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
                        <td>{{$d['client_name']}}</td>
                        <td>{{$d['lead_id']}}</td>
                        <td><strong style="color:#1a73e8;">{{$d['user_name']}}</strong></td>
                        <td>{{$d['closed_by']}}</td>
                        <td>{{$d['incentive_percentage']}}%</td>
                        <td><strong>₹ {{number_format($d['incentive_amount'], 0)}}</strong></td>
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
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js') }}"></script>
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

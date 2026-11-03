@extends('adminlte::page')

@section('title', 'Activities')

@section('content_header')
    <h1>All Activities</h1>
@stop

@section('content')

<div class="activity-feed">
@foreach($activities as $a)
  <div class="feed-item">
    <div class="date">{{ \Carbon\Carbon::parse($a->created_by, 'UTC')->setTimezone('Asia/Kolkata')->format('d-M-Y h:i A') }}</div>
    <div class="text">{{$a->activity_text}}</div>
  </div>
@endforeach
 
</div>

@stop
<style>

.activity-feed {
  padding: 15px;
}
.activity-feed .feed-item {
  position: relative;
  padding-bottom: 20px;
  padding-left: 30px;
  border-left: 2px solid #e4e8eb;
}
.activity-feed .feed-item:last-child {
  border-color: transparent;
}
.activity-feed .feed-item:after {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: -6px;
  width: 10px;
  height: 10px;
  border-radius: 6px;
  background: #fff;
  border: 1px solid #f37167;
}
.activity-feed .feed-item .date {
  position: relative;
  top: -5px;
  color: #8c96a3;
  text-transform: uppercase;
  font-size: 13px;
}
.activity-feed .feed-item .text {
  position: relative;
  top: -3px;
}

</style>
@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js') }}"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<script>
	
	jQuery(function($){
		$('input[name="daterange"]').daterangepicker(
		{
			locale: {
			  format: 'YYYY-MM-DD'
			},
			startDate: '2024-05-01',
			endDate: '2024-05-31'
		}
		);
		
		 
   
	});
</script>
@stop

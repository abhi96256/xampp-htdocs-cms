@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <h1> My Notifications</h1>
    @if ($errors->any())
        <div class="border border-danger text-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="border border-danger text-danger">
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
    @endif
@stop

@section('content')


<div class="row notification-container">
   @if($notifications)
	   <div class="col-md-12 text-right">
  <p class="dismiss text-right"><form method="post"  enctype="multipart/form-data">
            @csrf<input type="submit" id="dismiss-all" class="btn btn-danger" name="mark_read" value="Mark all Read"></form></p>
			@if(auth()->user()->hasRole('Admin') == false)
			<a class="btn btn-success float-right ml-2 assigned_to"  data-toggle="modal" data-target="#advance_search_filter" id="assign_to" >
                    <i class="fas fa-chevron-plus"></i>
                    <span class="big-btn-text">Send Notification</span>
			</a>
			@endif
			</div> 
			
  @foreach($notifications as $n)
  @php 
      $cardClass = 'notification-invitation'; // Default
      if(isset($n->notification_type)) {
          switch($n->notification_type) {
              case 'payment': $cardClass = 'notification-payment'; break;
              case 'wallet': $cardClass = 'notification-wallet'; break;
              case 'client': $cardClass = 'notification-client'; break;
              case 'lead': $cardClass = 'notification-lead'; break;
          }
      }
  @endphp
  <div class="card notification-card {{ $cardClass }}">
    <div class="card-body">
      <table>
        <tr>
          <td style="width:100%">
             @if($n->created_at)
                 <div class="text-muted" style="font-size: 0.75rem; text-align: right;">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</div>
             @endif
             <div class="card-title">{{ $n->notification_text}}</div>
          </td>
        </tr>
      </table>
    </div>
  </div>
  @endforeach
  @else
	  <div class="border border-danger text-danger">
            <ul>
                
                <li>No New Notifications</li>
               
            </ul>
        </div>
  @endif
</div>
<div class="modal fade" id="advance_search_filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	   <form method="post" action="{{ route('myaccount.send_notification') }}">
	  @csrf
      <div class="modal-body">
        <div class="form-group row">
		 <div class="form-group col-sm-12">
        <label class="text-capitalize" for="name">Notification description</label>
        <textarea name="notification[text]" class="form-control" placeholder="Enter Notification text"></textarea>
    </div>
	 	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-danger" value="Send">
      </div>
	  </form>
    </div>
  </div>
</div>


    

    </div>
	<style>
body{
  background-color: #fcfcfc;
}

.row{
  margin:auto;
  padding: 30px;
  width: 80%;
  display: flex;
  flex-flow: column;
  .card{
    width: 100%;
    margin-bottom: 5px;
    display: block;
    transition: opacity 0.3s;
  }
}


.card-body{
  padding: 0.5rem;
  table{
    width: 100%;
    tr{
      display:flex;
      td{
        a.btn{
          font-size: 0.8rem;
          padding: 3px;
        }
      }
      td:nth-child(2){
        text-align:right;
        justify-content: space-around;
      }
    }
  }
  
}

.card-title:before{
  display:inline-block;
  font-family: 'Font Awesome\ 5 Free';
  font-weight:900;
  font-size: 1.1rem;
  text-align: center;
  border: 2px solid grey;
  border-radius: 100px;
  width: 30px;
  height: 30px;
  padding-bottom: 3px;
  margin-right: 10px;
}

.notification-invitation {
  .card-body {
    .card-title:before {
      color: #90CAF9;
      border-color: #90CAF9;
      content: "\f007";
    }
  }
}

.notification-warning {
  .card-body {
    .card-title:before {
      color: #FFE082;
      border-color: #FFE082;
      content: "\f071";
    }
  }
}

.notification-danger {
  .card-body {
    .card-title:before {
      color: #FFAB91;
      border-color: #FFAB91;
      content: "\f00d";
    }
  }
}

.notification-reminder {
  .card-body {
    .card-title:before {
      color: #CE93D8;
      border-color: #CE93D8;
      content: "\f017";
    }
  }
}

.notification-payment {
  .card-body {
    .card-title:before {
      color: #81C784;
      border-color: #81C784;
      content: "\f0d6"; /* Money / Dollar */
    }
  }
}

.notification-wallet {
  .card-body {
    .card-title:before {
      color: #4DD0E1;
      border-color: #4DD0E1;
      content: "\f555"; /* Wallet */
    }
  }
}

.notification-client {
  .card-body {
    .card-title:before {
      color: #FFB74D;
      border-color: #FFB74D;
      content: "\f5b8"; /* Handshake / Client */
    }
  }
}

.card.display-none{
  display: none;
  transition: opacity 2s;
}


	</style>
@stop

@section('css')
@stop

@section('js')
@stop

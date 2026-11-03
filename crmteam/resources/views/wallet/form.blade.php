@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payer">User</label>
       <select class="form-control js-example-basic-single" id="user_id" name="user_id" style="width: 100%;" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @if(isset($due) && $due->user_id == $user->id) selected @endif>
                    {{ $user->name }} @if(count($user->getRoleNames()) > 0) ({{ implode(', ', $user->getRoleNames()->toArray()) }}) @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="amount">Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control text-capitalize" id="amount" name="amount"
        value="@if(isset($due)){{ $due->amount }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" name="payment_mode" style="width: 100%;" onchange="otherSelect(event)" required>
            @foreach ($payment_modes as $payment_mode)
                <option value="{{ $payment_mode->id }}" @if(isset($due) && $payment_mode->id == $due->payment_method) selected @endif>
                    {{ $payment_mode->name }}
                </option>
            @endforeach
        </select>
		<div id="otherBox" style="visibility: @if(isset($due) && $due->cheque_no) visible @else hidden @endif;">
		 <input name="cheque_no" type="text" placeholder="Enter Cheque no" class="form-control" value="@if(isset($due)){{ $due->cheque_no }}@endif" /> 
		</div>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" required value="@if(isset($due) && $due->date){{ \Carbon\Carbon::parse($due->date)->format('Y-m-d') }}@else{{ date('Y-m-d') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payee">Payer Name</label>
        <input type="text" class="form-control text-capitalize" id="payee" name="payee" placeholder="Payer name" value="@if(isset($due)){{ $due->payee }}@endif" required>
    </div>
</div>



<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($due)) Update @else Add @endif">
    <a class="btn btn-danger ml-3" href="{{ route('wallet.index') }}">Cancel</a>
</div>

<script>
function otherSelect(event) {
	//alert($( "#payment_mode  option:selected" ).text());
 var other = document.getElementById("otherBox");
 if ($( "#payment_mode  option:selected" ).text().trim() == "Cheque") {
 other.style.visibility = "visible";
 }
 else {
 other.style.visibility = "hidden";
 }
 }
</script>

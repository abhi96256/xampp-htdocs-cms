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
        <label class="text-capitalize" for="payer">Payer</label>
        <input type="text" class="form-control text-capitalize" id="payer" name="payer" placeholder="Payer name"
        value="@if(isset($payment)){{ $payment->payer }}@else{{ old('payer') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="amount">Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control text-capitalize" id="amount" name="amount"
        value="@if(isset($payment)){{ $payment->amount }}@else{{ old('amount') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="date_of_payment">Date Of Payment</label>
        <input type="date" class="form-control" id="date_of_payment" name="date_of_payment"
        value="@if(isset($payment)){{ $payment->date_of_payment->format('Y-m-d') }}@else{{ old('date_of_payment') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" name="payment_mode" onchange="otherSelect(event)" required>
            @foreach ($payment_modes as $payment_mode)
                <option value="{{ $payment_mode->id }}" @if(isset($due)) @if($payment_mode->id == $due->payment_mode->id) selected @endif @endif>
                    {{ $payment_mode->name }}
                </option>
            @endforeach
        </select>
		<div id="otherBox" style="visibility: hidden;">
		 <input name="cheque_no" type="text" placeholder="Enter Cheque no" class="form-control" /> 
		</div>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="remark">Remark</label>
        <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark"
        value="@if(isset($payment)){{ $payment->remark }}@else{{ old('remark') }}@endif">
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($payment)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('payments.index') }}">Cancel</a>
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

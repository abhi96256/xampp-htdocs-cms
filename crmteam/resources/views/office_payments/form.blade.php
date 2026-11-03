@if ($errors->any())
    <div class="border border-danger text-danger mb-3 p-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-md-3">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date"
               value="@if(isset($office_payment) && $office_payment->date){{ \Carbon\Carbon::parse($office_payment->date)->format('Y-m-d') }}@else{{ date('Y-m-d') }}@endif">
    </div>

    <div class="form-group col-md-3">
        <label for="financial_year">Financial Year</label>
        <select class="form-control" id="financial_year" name="financial_year">
            <option value="">Select Year</option>
            @php $currentYear = date('Y') - 5; @endphp
            @for($i = $currentYear; $i <= 2050; $i++)
                @php $fy = $i . '-' . substr($i+1, 2, 2); @endphp
                <option value="{{ $fy }}" {{ old('financial_year', $office_payment->financial_year ?? '') == $fy ? 'selected' : '' }}>{{ $fy }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="location">Location</label>
        <input type="text" class="form-control" id="location" name="location"
               value="{{ old('location', $office_payment->location ?? '') }}" placeholder="Location">
    </div>

    <div class="form-group col-md-3">
        <label for="cash_amount">Cash (Amount)</label>
        <input type="number" step="0.01" class="form-control" id="cash_amount" name="cash_amount"
               value="{{ old('cash_amount', $office_payment->cash_amount ?? '') }}" placeholder="Cash Amount">
    </div>
</div>

<div class="card bg-light mt-3">
    <div class="card-header font-weight-bold">Cheque Details</div>
    <div class="card-body row">
        <div class="form-group col-md-4">
            <label for="cheque_amount">Amount</label>
            <input type="number" step="0.01" class="form-control" id="cheque_amount" name="cheque_amount"
                   value="{{ old('cheque_amount', $office_payment->cheque_amount ?? '') }}" placeholder="Cheque Amount">
        </div>
        <div class="form-group col-md-4">
            <label for="cheque_nos">Cheque Nos</label>
            <input type="text" class="form-control" id="cheque_nos" name="cheque_nos"
                   value="{{ old('cheque_nos', $office_payment->cheque_nos ?? '') }}" placeholder="Cheque Nos">
        </div>
        <div class="form-group col-md-4">
            <label for="cheque_date">Cheque Date</label>
            <input type="date" class="form-control" id="cheque_date" name="cheque_date"
                   value="@if(isset($office_payment) && $office_payment->cheque_date){{ \Carbon\Carbon::parse($office_payment->cheque_date)->format('Y-m-d') }}@endif">
        </div>
    </div>
</div>

<div class="card bg-light mt-3">
    <div class="card-header font-weight-bold">Online Details</div>
    <div class="card-body row">
        <div class="form-group col-md-6">
            <label for="online_amount">Amount</label>
            <input type="number" step="0.01" class="form-control" id="online_amount" name="online_amount"
                   value="{{ old('online_amount', $office_payment->online_amount ?? '') }}" placeholder="Online Amount">
        </div>
        <div class="form-group col-md-6">
            <label for="utr_nos">UTR Nos</label>
            <input type="text" class="form-control" id="utr_nos" name="utr_nos"
                   value="{{ old('utr_nos', $office_payment->utr_nos ?? '') }}" placeholder="UTR Nos">
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="form-group col-md-4">
        <label for="topology">Topology</label>
        <input type="text" class="form-control" id="topology" name="topology"
               value="{{ old('topology', $office_payment->topology ?? '') }}" placeholder="Topology">
    </div>

    <div class="form-group col-md-4">
        <label for="in_favour_of">In Favour of</label>
        <input type="text" class="form-control" id="in_favour_of" name="in_favour_of"
               value="{{ old('in_favour_of', $office_payment->in_favour_of ?? '') }}" placeholder="In Favour of">
    </div>

    <div class="form-group col-md-4">
        <label for="remarks">Remarks</label>
        <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Remarks">{{ old('remarks', $office_payment->remarks ?? '') }}</textarea>
    </div>
</div>

<div class="form-group mt-4">
    <button type="submit" class="btn btn-success">
        @if(isset($office_payment)) Update @else Create @endif
    </button>
    <a class="btn btn-danger ml-3" href="{{ route('office_payment.index') }}">Cancel</a>
</div>

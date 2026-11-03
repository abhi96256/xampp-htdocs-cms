@if ($errors->any())
    <div class="alert alert-danger mb-3 p-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    @if(auth()->user()->hasRole('Admin'))
    <div class="form-group col-md-4">
        <label for="user_id">User</label>
        <select class="form-control" name="user_id" required {{ isset($attendance) && $attendance->in_time ? 'style=pointer-events:none;background-color:#e9ecef; tabindex=-1 readonly' : '' }}>
            <option value="">Select User</option>
            @foreach($users as $usr)
                <option value="{{ $usr->id }}" {{ old('user_id', $attendance->user_id ?? '') == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
            @endforeach
        </select>
    </div>
    @else
        {{-- Non-admin: auto-assign to self --}}
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
    @endif

    <div class="form-group col-md-4">
        <label for="date">Date</label>
        <input type="text" class="form-control bg-light" id="date" name="date" required readonly
               value="@if(isset($attendance) && $attendance->date){{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}@else{{ date('Y-m-d') }}@endif">
    </div>

    <div class="form-group col-md-4">
        <label for="attendance_type">Attendance Type</label>
        <select class="form-control" name="attendance_type" required {{ isset($attendance) && $attendance->in_time ? 'style=pointer-events:none;background-color:#e9ecef; tabindex=-1 readonly' : '' }}>
            <option value="Present" {{ old('attendance_type', $attendance->attendance_type ?? '') == 'Present' ? 'selected' : '' }}>Present</option>
            <option value="Absent" {{ old('attendance_type', $attendance->attendance_type ?? '') == 'Absent' ? 'selected' : '' }}>Absent</option>
            <option value="Short Time" {{ old('attendance_type', $attendance->attendance_type ?? '') == 'Short Time' ? 'selected' : '' }}>Short Time</option>
            <option value="Leave" {{ old('attendance_type', $attendance->attendance_type ?? '') == 'Leave' ? 'selected' : '' }}>Leave</option>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="form-group col-md-6">
        <label for="in_time">IN TIME</label>
        <div class="input-group">
            <input type="time" class="form-control" id="in_time" name="in_time"
                   value="{{ old('in_time', $attendance->in_time ?? '') }}" readonly>
            <div class="input-group-append">
                <button type="button" class="btn btn-primary" id="btn_mark_in" {{ isset($attendance) && $attendance->in_time ? 'disabled' : '' }}>Mark IN</button>
            </div>
        </div>
    </div>

    <div class="form-group col-md-6">
        <label for="out_time">OUT TIME (Optional initially)</label>
        <div class="input-group">
            <input type="time" class="form-control" id="out_time" name="out_time"
                   value="{{ old('out_time', $attendance->out_time ?? '') }}" readonly>
            <div class="input-group-append">
                <button type="button" class="btn btn-warning" id="btn_mark_out">Mark OUT</button>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-4 text-center">
    <input type="hidden" name="location_in" id="location_in" value="{{ $attendance->location_in ?? '' }}">
    <input type="hidden" name="location_out" id="location_out" value="{{ $attendance->location_out ?? '' }}">
    <button type="submit" class="btn btn-success px-5">
        @if(isset($attendance)) Update @else Save Attendance @endif
    </button>
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary px-5 ml-2">Cancel</a>
</div>

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Live Time logic
        function getLiveTime() {
            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            return h + ':' + m;
        }

        document.getElementById('btn_mark_in').addEventListener('click', function() {
            if (navigator.geolocation) {
                var btn = this;
                btn.innerHTML = 'Getting...';
                // Disable OUT button
                document.getElementById('btn_mark_out').disabled = true;
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    document.getElementById('location_in').value = lat + ", " + lon;
                    document.getElementById('in_time').value = getLiveTime();
                    btn.innerHTML = 'Mark IN';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                }, function(error) {
                    alert("Please allow location access to mark IN.");
                    btn.innerHTML = 'Mark IN';
                    document.getElementById('btn_mark_out').disabled = false;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        document.getElementById('btn_mark_out').addEventListener('click', function() {
            if (navigator.geolocation) {
                var btn = this;
                btn.innerHTML = 'Getting...';
                // Disable IN button
                document.getElementById('btn_mark_in').disabled = true;
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    document.getElementById('location_out').value = lat + ", " + lon;
                    document.getElementById('out_time').value = getLiveTime();
                    btn.innerHTML = 'Mark OUT';
                    btn.classList.remove('btn-warning');
                    btn.classList.add('btn-success');
                }, function(error) {
                    alert("Please allow location access to mark OUT.");
                    btn.innerHTML = 'Mark OUT';
                    document.getElementById('btn_mark_in').disabled = false;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        // Mandatory Location check on form submit
        var submitBtns = document.querySelectorAll('button[type="submit"].btn-success');
        submitBtns.forEach(function(btn) {
            var exactForm = btn.closest('form');
            if(exactForm && exactForm.action && exactForm.action.includes('attendance')) {
                exactForm.addEventListener('submit', function(e) {
                    var locIn = document.getElementById('location_in').value;
                    var locOut = document.getElementById('location_out').value;
                    
                    if((!locIn || locIn.trim() === '') && (!locOut || locOut.trim() === '')) {
                        e.preventDefault();
                        alert("Please click 'Mark IN' or 'Mark OUT' and allow location access to save attendance.");
                    }
                });
            }
        });
    });
</script>
@endpush

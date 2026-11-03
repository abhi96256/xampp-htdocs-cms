@extends('adminlte::page')

@section('title', 'Edit Roles')

@section('content_header')
    <h1>Edit Roles</h1>
@stop

@section('content')
   
    @if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="post" action="{{ route('roles.edit', ['id' => $roles->id]) }}">  
        @csrf

        <div class="row">
            <div class="form-group col-sm-8">
                <label class="text-capitalize" for="name">Name</label>
                <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Role Name"
                value="{{$roles->name}}" required>
            </div>
        </div>

        <label class="text-capitalize"><strong>Permissions</strong></label>

        @php
            // Group permissions by module
            $grouped = [];
            $moduleLabels = [
                'leads'            => '🔵 Leads',
                'enquiry'          => '🔵 Enquiry (Leads)',
                'followup'         => '📞 Follow Up',
                'client'           => '😊 Happy Client',
                'incentive'        => '💎 Incentive (Company & Employee)',
                'project'          => '📁 Projects',
                'dpr'              => '📋 DPR',
                'attendance'       => '🕐 Attendance',
                'dues'             => '💸 Dues',
                'wallet'           => '💳 Wallet',
                'expenses'         => '📉 Expenses',
                'office_payment'   => '🏢 Office Payment',
                'report'           => '📊 Reports',
                'transaction'      => '💳 Transactions',
                'role'             => '🛡️ Roles',
                'permission'       => '🔐 Permissions',
                'user_management'  => '👥 User Management',
                'user'             => '👤 Users',
                'teams'            => '🤝 Teams',
                'other'            => '⚙️ Other',
            ];

            foreach ($permissions as $p) {
                $name = $p->name;
                $matched = false;
                foreach (array_keys($moduleLabels) as $key) {
                    if (str_starts_with($name, $key . '_') || $name === $key . '_access' || $name === $key) {
                        $grouped[$key][] = $p;
                        $matched = true;
                        break;
                    }
                }
                if (!$matched) {
                    $grouped['other'][] = $p;
                }
            }
        @endphp

        @foreach ($grouped as $module => $perms)
            @if(!empty($perms))
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#2d3748; color:#fff;">
                    <strong>{{ $moduleLabels[$module] ?? ucfirst($module) }}</strong>
                    <button type="button" class="btn btn-sm btn-outline-light float-right toggle-all" data-module="{{ $module }}">Select All</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($perms as $p)
                            @php
                                $parts = explode('_', $p->name);
                                $action = array_pop($parts);
                                $actionDescription = '';
                                switch(strtolower($action)) {
                                    case 'access': $actionDescription = "View list & access module"; break;
                                    case 'create': $actionDescription = "Create new records"; break;
                                    case 'edit':   $actionDescription = "Edit existing records"; break;
                                    case 'show':   $actionDescription = "View detail of records"; break;
                                    case 'delete': $actionDescription = "Delete records"; break;
                                    case 'import': $actionDescription = "Import data"; break;
                                    case 'export': $actionDescription = "Export data"; break;
                                    default:       $actionDescription = ucfirst($action); break;
                                }
                                $title = ucwords(str_replace('_', ' ', $p->name));
                            @endphp
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input module-check-{{ $module }}" 
                                           type="checkbox" name='permissions[]' 
                                           value="{{ $p->id }}" 
                                           id="permission_{{ $p->id }}"  
                                           @if($p->id == $p->permission_id) checked @endif>
                                    <label class="form-check-label" for="permission_{{ $p->id }}">
                                        <strong>{{ $title }}</strong><br>
                                        <small class="text-muted">{{ $actionDescription }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        <div class="form-group mt-3">
            <input type="submit" class="btn btn-success btn-lg" value="Update">
            <a class="btn btn-danger btn-lg ml-3" href="{{ route('roles.index') }}">Cancel</a>
        </div>
    </form>

@stop

@section('css')
<style>
    .card-header { border-radius: 4px 4px 0 0; }
    .form-check-input { margin-top: 4px; }
    .card { border: 1px solid #dee2e6; }
</style>
@stop

@section('js')
<script>
    // "Select All" toggle for each module group
    document.querySelectorAll('.toggle-all').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var module = this.getAttribute('data-module');
            var checkboxes = document.querySelectorAll('.module-check-' + module);
            var allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(function(cb) {
                cb.checked = !allChecked;
            });
            btn.textContent = allChecked ? 'Select All' : 'Deselect All';
        });
    });
</script>
@stop

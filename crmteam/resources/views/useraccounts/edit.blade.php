@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <div class="row">
        <div class="col-sm-10"><h1>Edit User</h1></div>
	@can('user_create')
        <div class="col-sm-2">
            <a class="btn btn-success text-right ml-2 assigned_to"  data-toggle="modal" data-target="#advance_search_filter" id="assign_to" >
                <span class="big-btn-text">Update Password</span>
            </a>
        </div>
        @endcan
	</div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
@stop

@section('content')

    <form method="post" action="{{ route('useraccounts.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
        @include('useraccounts.form')
    </form>

    {{-- ===== DIRECT PERMISSIONS SECTION ===== --}}
    <div class="card mt-4">
        <div class="card-header" style="background:#2d3748; color:#fff;">
            <strong>🔑 Direct Permissions for {{ $user->name }}</strong>
            <small class="ml-2" style="opacity:0.75;">(These override or supplement the user's role permissions)</small>
        </div>
        <div class="card-body">
            @php
                $moduleLabels = [
                    'enquiry'          => ['icon'=>'🔵', 'label'=>'Enquiry (Leads)'],
                    'leads'            => ['icon'=>'🔵', 'label'=>'Leads'],
                    'followup'         => ['icon'=>'📞', 'label'=>'Follow Up'],
                    'client'           => ['icon'=>'😊', 'label'=>'Happy Client'],
                    'project'          => ['icon'=>'📁', 'label'=>'Projects'],
                    'payment'          => ['icon'=>'💰', 'label'=>'Payments'],
                    'dues'             => ['icon'=>'💸', 'label'=>'Dues'],
                    'wallet'           => ['icon'=>'💳', 'label'=>'Wallet'],
                    'expenses'         => ['icon'=>'📉', 'label'=>'Expenses'],
                    'office_payment'   => ['icon'=>'🏢', 'label'=>'Office Payment'],
                    'attendance'       => ['icon'=>'🕐', 'label'=>'Attendance'],
                    'dpr'              => ['icon'=>'📋', 'label'=>'DPR'],
                    'report'           => ['icon'=>'📊', 'label'=>'Reports'],
                    'transaction'      => ['icon'=>'🔄', 'label'=>'Transactions'],
                    'incentive'        => ['icon'=>'💎', 'label'=>'Incentive'],
                    'role'             => ['icon'=>'🛡️', 'label'=>'Roles'],
                    'permission'       => ['icon'=>'🔐', 'label'=>'Permissions'],
                    'user_management'  => ['icon'=>'👥', 'label'=>'User Management'],
                    'user'             => ['icon'=>'👤', 'label'=>'Users'],
                    'teams'            => ['icon'=>'🤝', 'label'=>'Teams'],
                ];
                $grouped = [];
                foreach ($permissions as $p) {
                    $matched = false;
                    foreach (array_keys($moduleLabels) as $key) {
                        if (str_starts_with($p->name, $key . '_') || $p->name === $key) {
                            $grouped[$key][] = $p;
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) $grouped['other'][] = $p;
                }
                // Get permissions user has via role (to show inherited)
                $rolePermIds = $user->getPermissionsViaRoles()->pluck('id')->toArray();
            @endphp

            <div class="alert alert-info py-2 mb-3">
                <strong>🔵 Blue</strong> = inherited from role &nbsp;|&nbsp; <strong>🟡 Yellow</strong> = directly assigned to this user
            </div>

            <form method="POST" action="{{ route('useraccounts.update_permissions', $user->id) }}">
                @csrf
                @foreach($grouped as $module => $perms)
                @if(!empty($perms))
                <div class="card mb-2">
                    <div class="card-header py-1 d-flex justify-content-between align-items-center" style="background:#3d4858; color:#fff;">
                        <strong>{{ ($moduleLabels[$module]['icon'] ?? '⚙️') . ' ' . ($moduleLabels[$module]['label'] ?? ucfirst($module)) }}</strong>
                        <button type="button" class="btn btn-sm btn-outline-light toggle-module-{{ $module }}" onclick="toggleModule('{{ $module }}')">Select All</button>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            @foreach($perms as $p)
                            @php
                                $revoked = $user->revoked_permissions ? json_decode($user->revoked_permissions, true) : [];
                                $isRevoked = in_array($p->name, $revoked);
                                $isFromRole = in_array($p->id, $rolePermIds);
                                $isDirect = in_array($p->id, $userDirectPermissions);
                                $title = ucwords(str_replace('_', ' ', $p->name));
                                $parts = explode('_', $p->name);
                                $action = array_pop($parts);
                                $desc = ['access'=>'View list','create'=>'Create','edit'=>'Edit','show'=>'View detail','delete'=>'Delete','import'=>'Import','export'=>'Export'][$action] ?? $action;
                            @endphp
                            <div class="col-md-4 col-sm-6 mb-1">
                                <div class="form-check" style="{{ $isFromRole ? 'background:#dbeafe; border-radius:4px; padding:4px 8px;' : ($isDirect ? 'background:#fef9c3; border-radius:4px; padding:4px 8px;' : 'padding:4px 8px;') }}">
                                    <input class="form-check-input perm-check-{{ $module }}"
                                           type="checkbox" name="permissions[]"
                                           value="{{ $p->name }}"
                                           id="dp_{{ $p->id }}"
                                           {{ ($isDirect || ($isFromRole && !$isRevoked)) ? 'checked' : '' }}
                                           {{ $isFromRole ? 'data-inherited="1"' : '' }}>
                                    <label class="form-check-label" for="dp_{{ $p->id }}" style="font-size:0.85rem;">
                                        <strong>{{ $title }}</strong><br>
                                        <small class="text-muted">{{ $desc }}</small>
                                        @if($isFromRole) <span class="badge badge-primary" style="font-size:0.65rem;">Role</span> @endif
                                        @if($isDirect) <span class="badge badge-warning text-dark" style="font-size:0.65rem;">Direct</span> @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Save Direct Permissions
                    </button>
                    <small class="text-muted ml-3">Note: Role permissions (blue) will remain; only direct permissions are updated here.</small>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
    <script>
        function toggleModule(module) {
            var checkboxes = document.querySelectorAll('.perm-check-' + module);
            var allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => { cb.checked = !allChecked; });
            var btn = document.querySelector('.toggle-module-' + module);
            if(btn) btn.textContent = allChecked ? 'Select All' : 'Deselect All';
        }
    </script>
@stop
<div class="modal fade" id="advance_search_filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	   <form method="post" action="{{ route('useraccounts.update_password', ['id' => $user->id]) }}">
	  @csrf
      <div class="modal-body">
        <div class="form-group row">
		 <div class="form-group col-sm-12">
            <label class="text-capitalize" for="assigned_to">New Password</label>
             <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="">
        </div>
		<div class="form-group col-sm-12">
            <label class="text-capitalize" for="assigned_to">Confirm Password</label>
             <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password" value="">
        </div>
	 	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-danger" value="Update">
      </div>
	  </form>
    </div>
  </div>
</div>
</div>

@extends('adminlte::page')

@section('title', 'Manage Team: ' . $team->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Manage Team: {{ $team->name }}</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('teams.hierarchy', $team->id) }}" class="btn btn-info">
                <i class="fas fa-sitemap"></i> View Hierarchy
            </a>
            <a href="{{ route('teams.index') }}" class="btn btn-default">Back to List</a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Team Settings -->
        <div class="col-md-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Team Settings</h3>
                </div>
                <form action="{{ route('teams.update', $team->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Team Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $team->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ $team->description }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update Team</button>
                    </div>
                </form>
            </div>

            <!-- Role Permission Management -->
            <div class="card card-outline card-warning shadow-sm mt-4">
                <div class="card-header">
                    <h3 class="card-title">Role Permissions</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Update permissions for roles used in this team.</p>
                    <form action="{{ route('teams.updateRolePermissions') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Select Role</label>
                            <select name="role_id" id="role_selector" class="form-control select2" style="width: 100%;">
                                <option value="">-- Choose Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="permissions_container" style="display:none; max-height: 300px; overflow-y: auto; border: 1px solid #eee; padding: 10px; border-radius: 5px;">
                            <label>Permissions</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                            <label for="perm_{{ $permission->id }}" class="custom-control-label small">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-sm btn-warning" id="update_perms_btn" disabled>Sync Role Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Member Management -->
        <div class="col-md-8">
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Add Member</h3>
                </div>
                <form action="{{ route('teams.addMember', $team->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>User</label>
                                    <select name="user_id" class="form-control select2" required style="width: 100%;">
                                        <option value="">-- Select User --</option>
                                        @foreach($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Team Role</label>
                                    <select name="role_id" class="form-control select2" required style="width: 100%;">
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Reporting To (Optional)</label>
                                    <select name="parent_id" class="form-control select2" style="width: 100%;">
                                        <option value="">-- No Direct Lead --</option>
                                        @foreach($team->users as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Add to Team</button>
                    </div>
                </form>
            </div>

            <div class="card card-outline card-info shadow-sm mt-4">
                <div class="card-header">
                    <h3 class="card-title">Team Members</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Reports To</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($team->users as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>
                                        @php 
                                            $role = $roles->firstWhere('id', $member->pivot->role_id);
                                        @endphp
                                        <span class="badge badge-primary">{{ $role->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @php 
                                            $parent = $team->users->firstWhere('id', $member->pivot->parent_id);
                                        @endphp
                                        {{ $parent->name ?? 'None' }}
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary edit-team-member" 
                                                data-user-id="{{ $member->id }}" 
                                                data-role-id="{{ $member->pivot->role_id }}" 
                                                data-parent-id="{{ $member->pivot->parent_id }}" 
                                                data-user-name="{{ $member->name }}"
                                                title="Edit Member Role & Reporting">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning manage-user-perms" 
                                                data-user-id="{{ $member->id }}" 
                                                data-url="{{ route('teams.getUserPermissions', $member->id) }}"
                                                title="Manage Individual Permissions">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <form action="{{ route('teams.removeMember', $team->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this member?')">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No members added to this team yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Permissions Modal -->
    <div class="modal fade" id="userPermsModal" tabindex="-1" role="dialog" aria-labelledby="userPermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="userPermsModalLabel">Manage Permissions: <span id="target_user_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teams.updateUserPermissions') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="target_user_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info py-2">
                                    <i class="fas fa-info-circle"></i> 
                                    <b>Blue</b> checkboxes are permissions inherited from roles (Old/Current). 
                                    <b>Yellow</b> checkboxes are permissions given specifically to this user.
                                </div>
                            </div>
                        </div>
                        <div class="row" style="max-height: 400px; overflow-y: auto;">
                            @foreach($permissions as $permission)
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-checkbox p-2 border rounded permission-box" id="box_{{ $permission->id }}">
                                        <input class="custom-control-input user-perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="user_perm_{{ $permission->id }}">
                                        <label for="user_perm_{{ $permission->id }}" class="custom-control-label small" style="cursor: pointer;">
                                            {{ str_replace('_', ' ', $permission->name) }}
                                        </label>
                                        <span class="badge badge-info float-right d-none role-badge" id="role_badge_{{ $permission->id }}">Role</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Team Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="editMemberModalLabel">Edit Member: <span id="edit_member_name"></span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teams.updateMember', $team->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="edit_member_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Team Role</label>
                            <select name="role_id" id="edit_role_id" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $roleOptions)
                                    <option value="{{ $roleOptions->id }}">{{ $roleOptions->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reporting To (Optional)</label>
                            <select name="parent_id" id="edit_parent_id" class="form-control select2" style="width: 100%;">
                                <option value="">-- No Direct Lead --</option>
                                @foreach($team->users as $memberOptions)
                                    <option value="{{ $memberOptions->id }}">{{ $memberOptions->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#role_selector').on('change', function() {
                var roleId = $(this).val();
                if (roleId) {
                    $('#permissions_container').show();
                    $('#update_perms_btn').prop('disabled', false);
                    
                    $('.perm-checkbox').prop('checked', false);
                    
                    // Fetch existing role permissions dynamically
                    $.get('/teams/role-permissions/' + roleId, function(data) {
                        if (data && data.permissions) {
                            data.permissions.forEach(function(permName) {
                                $('.perm-checkbox[value="' + permName + '"]').prop('checked', true);
                            });
                        }
                    });
                } else {
                    $('#permissions_container').hide();
                    $('#update_perms_btn').prop('disabled', true);
                }
            });

            // Edit Team Member
            $('.edit-team-member').on('click', function() {
                var userId = $(this).data('user-id');
                var roleId = $(this).data('role-id');
                var parentId = $(this).data('parent-id');
                var userName = $(this).data('user-name');
                
                $('#edit_member_id').val(userId);
                $('#edit_member_name').text(userName);
                
                $('#edit_role_id').val(roleId).trigger('change');
                $('#edit_parent_id').val(parentId).trigger('change');
                
                $('#editMemberModal').modal('show');
            });

            // Individual User Permissions
            $('.manage-user-perms').on('click', function() {
                var userId = $(this).data('user-id');
                var fetchUrl = $(this).data('url');
                $('#target_user_id').val(userId);
                
                // Clear state
                $('.user-perm-checkbox').prop('checked', false).prop('disabled', false).css('cursor', 'pointer');
                $('.custom-control-label').css('cursor', 'pointer');
                $('.role-badge').addClass('d-none');
                $('.permission-box').removeClass('border-info bg-light-info border-warning bg-light-warning');
                
                $.get(fetchUrl, function(data) {
                    $('#target_user_name').text(data.user_name);
                    
                    // Mark Role Permissions
                    data.role_permissions.forEach(function(permName) {
                        var checkbox = $('.user-perm-checkbox[value="' + permName + '"]');
                        if (checkbox.length) {
                            if (!data.revoked_permissions.includes(permName)) {
                                checkbox.prop('checked', true);
                            }
                            // checkbox.prop('disabled', true); // Removed to allow editing
                            // checkbox.css('cursor', 'not-allowed'); // Removed to allow editing
                            
                            var id = checkbox.attr('id').split('_').pop();
                            // $('#user_perm_' + id).next('label').css('cursor', 'not-allowed'); // Removed to allow editing
                            $('#role_badge_' + id).removeClass('d-none');
                            $('#box_' + id).addClass('border-info').css('background-color', '#e7f3ff');
                        }
                    });
                    
                    // Mark Direct Permissions
                    data.direct_permissions.forEach(function(permName) {
                        var checkbox = $('.user-perm-checkbox[value="' + permName + '"]');
                        if (checkbox.length) {
                            checkbox.prop('checked', true);
                            var id = checkbox.attr('id').split('_').pop();
                            $('#box_' + id).addClass('border-warning').css('background-color', '#fff9e6');
                        }
                    });
                    
                    $('#userPermsModal').modal('show');
                });
            });
        });
    </script>
@stop

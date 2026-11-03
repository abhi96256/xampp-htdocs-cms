@php
    $children = $allMembers->where('parent_id', $member->user_id);
    $hasChildren = $children->count() > 0;
@endphp

<div class="hierarchy-node level-{{ $level }}">
    <div class="node-content d-flex align-items-center @if($hasChildren) has-children @endif">
        <span class="toggle-icon mr-2">
            @if($hasChildren)
                <i class="fas fa-chevron-right"></i>
            @else
                <i class="fas fa-user-tie"></i>
            @endif
        </span>
        <div class="user-info d-flex align-items-center flex-grow-1">
            <span class="user-name font-weight-bold mr-2">{{ $member->user->name }}</span>
            
            <span class="badge badge-pill badge-outline-primary role-badge border border-primary text-primary">
                {{ $member->role->name ?? 'Member' }}
            </span>
            
            <span class="level-badge ml-2">L{{ $level }}</span>
        </div>
    </div>

    @if($hasChildren)
        <div class="node-children" style="display: none; padding-left: 20px;">
            @foreach($children as $child)
                @include('teams._hierarchy_node', ['member' => $child, 'allMembers' => $allMembers, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>

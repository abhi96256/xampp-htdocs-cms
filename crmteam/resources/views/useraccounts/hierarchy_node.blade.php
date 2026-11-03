@php
    $directChildren = [];
    if (isset($users) && $users->count() > 0) {
        foreach ($users as $u) {
            // Level 1: Users who are TL1 (parentId is null)
            if ($currentLevel == 1) {
                // For root level, we only show users who are TL1s (they don't have any TL above them)
                if ($u->hasRole('Team Lead') && $u->tl_1 == null) {
                    $directChildren[] = $u;
                }
                continue;
            }

            // For deeper levels, find users who have the parent as their immediate superior
            $tlField = 'tl_' . ($currentLevel - 1);
            if (isset($u->$tlField) && $u->$tlField == $parentId) {
                // Check if this user is a "direct child" (meaning they don't have a deeper TL assigned yet)
                $isDirect = true;
                for ($k = $currentLevel; $k <= 5; $k++) {
                    $nextTlField = 'tl_' . $k;
                    if (isset($u->$nextTlField) && $u->$nextTlField != null) {
                        $isDirect = false;
                        break;
                    }
                }
                if ($isDirect) {
                    $directChildren[] = $u;
                }
            }
        }
    }
    $directChildren = collect($directChildren);
@endphp

@if($directChildren->count() > 0)
    <ul class="hierarchy-list">
        @foreach($directChildren as $child)
            <li class="hierarchy-node" data-id="{{ $child->id }}" data-level="{{ $currentLevel }}">
                <div class="node-content d-flex align-items-center">
                    <span class="toggle-icon mr-2">
                        @if($currentLevel < 6)
                            <i class="fas fa-chevron-right"></i>
                        @else
                            <i class="fas fa-user-tie"></i>
                        @endif
                    </span>
                    <div class="user-info">
                        <a href="{{ route('useraccounts.show', ['id' => $child->id]) }}" class="user-name font-weight-bold text-dark">
                            {{ $child->name }}
                        </a>
                        <span class="badge badge-pill badge-outline-primary ml-2 border border-primary text-primary" style="font-size: 0.7rem;">
                            {{ implode(', ', $child->roles->pluck('name')->toArray()) }}
                        </span>
                        <span class="badge badge-dark ml-1" style="font-size: 0.6rem;">L{{ $currentLevel }}</span>
                    </div>
                </div>
                
                @if($currentLevel < 6)
                <div class="node-children" style="display: none; border-left: 1px dashed #ccc; margin-left: 10px; padding-left: 15px;">
                    @include('useraccounts.hierarchy_node', [
                        'users' => $users,
                        'parentId' => $child->id,
                        'currentLevel' => $currentLevel + 1
                    ])
                </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif

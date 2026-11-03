@extends('adminlte::page')

@section('title', 'Team Hierarchy: ' . $team->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Hierarchy: {{ $team->name }}</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Manage Team
            </a>
            <a href="{{ route('teams.index') }}" class="btn btn-default">Back to List</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">
                <i class="fas fa-sitemap mr-1"></i> Team Hierarchy ({{ $team->name }})
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="hierarchy-wrapper p-3">
                @if($rootMembers->count() > 0)
                    <div class="hierarchy-container">
                        @foreach($rootMembers as $root)
                            @include('teams._hierarchy_node', ['member' => $root, 'allMembers' => $members, 'level' => 1])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hierarchy established. Add members and set "Reporting To" in Team Management.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .hierarchy-container ul {
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }
        .hierarchy-node {
            margin-bottom: 10px;
            position: relative;
        }
        .node-content {
            background: #fff;
            padding: 12px 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
            cursor: pointer;
            margin-bottom: 5px;
        }
        .node-content:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }
        .toggle-icon {
            color: #6c757d;
            width: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .user-name {
            font-size: 1rem;
            color: #333;
        }
        .role-badge {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 15px;
        }
        .level-badge {
            font-size: 0.6rem;
            background: #343a40;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .node-children {
            border-left: 2px solid #e9ecef;
            margin-left: 10px;
        }
        .node-content.expanded .toggle-icon i {
            transform: rotate(90deg);
        }
        
        /* Premium left border colors for levels */
        .level-1 > .node-content { border-left: 4px solid #007bff; }
        .level-2 > .node-content { border-left: 4px solid #28a745; }
        .level-3 > .node-content { border-left: 4px solid #ffc107; }
        .level-4 > .node-content { border-left: 4px solid #17a2b8; }
        .level-5 > .node-content { border-left: 4px solid #dc3545; }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.node-content').on('click', function() {
                var $this = $(this);
                var $children = $this.siblings('.node-children');
                
                if ($children.length > 0) {
                    $children.slideToggle(200);
                    $this.toggleClass('expanded');
                    
                    var $icon = $this.find('.toggle-icon i');
                    if ($this.hasClass('expanded')) {
                        $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    } else {
                        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    }
                }
            });
        });
    </script>
@stop

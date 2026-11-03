@extends('adminlte::page')

@section('title', 'Team Management')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Team Management</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('teams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Team
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header border-0 pb-0">
            <h3 class="card-title font-weight-bold">Team Hierarchy List</h3>
            <div class="card-tools">
                <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create New Team
                </a>
            </div>
        </div>
        <div class="card-body p-0 mt-3">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Team Name</th>
                        <th>Description</th>
                        <th>Members</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="font-weight-bold">{{ $team->name }}</span>
                            </td>
                            <td>{{ Str::limit($team->description, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $team->users_count }} Members</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('teams.hierarchy', $team->id) }}" class="btn btn-sm btn-info" title="View Hierarchy">
                                    <i class="fas fa-sitemap"></i>
                                </a>
                                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-primary" title="Edit/Manage Members">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this team? All members will be removed from this hierarchy.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Team">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No teams found. <a href="{{ route('teams.create') }}">Create one now.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table thead th { border-top: 0; }
    </style>
@stop

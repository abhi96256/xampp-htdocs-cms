@extends('adminlte::page')

@section('title', 'All Leads')

@section('content_header')
    <h1>All Leads</h1>
@stop

@section('content')

{{-- ===== PREMIUM LEADS TABLE STYLES ===== --}}
<style>
    /* Root tokens */
    :root {
        --leads-red:    #c00415;
        --leads-red2:   #9e0310;
        --leads-border: #e2e8f0;
        --leads-text:   #1e293b;
        --leads-muted:  #64748b;
    }

    /* Wrapper card */
    .leads-page-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 24px 24px 28px;
    }

    /* ── Toolbar ── */
    .crm-toolbar-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--leads-border);
        margin-bottom: 16px;
    }
    .crm-toolbar-left, .crm-toolbar-right {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .crm-toolbar-container .btn {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        padding: 7px 14px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.22s ease;
        white-space: nowrap;
    }
    .crm-toolbar-container .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.18);
    }
    .crm-toolbar-container .btn-success  { background: linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .crm-toolbar-container .btn-primary  { background: linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
    .crm-toolbar-container .btn-warning  { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .crm-toolbar-container .btn-info     { background: linear-gradient(135deg,#0891b2,#0e7490); color:#fff; }

    /* ── Filter bar ── */
    .crm-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .crm-filter-bar .dropdown > .btn {
        background: #f1f5f9;
        color: var(--leads-text);
        border: 1px solid var(--leads-border);
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 7px 14px;
        transition: all 0.2s;
    }
    .crm-filter-bar .dropdown > .btn:hover {
        background: var(--leads-red);
        color: #fff;
        border-color: var(--leads-red);
    }
    .crm-daterange-form .input-group-text {
        background: #f8fafc;
        border: 1px solid var(--leads-border);
        border-right: none;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--leads-muted);
        border-radius: 8px 0 0 8px !important;
    }
    .crm-daterange-form .form-control {
        border: 1px solid var(--leads-border);
        border-radius: 0 !important;
        font-size: 0.79rem;
        font-weight: 500;
        min-width: 200px;
    }
    .crm-daterange-form .form-control:focus {
        border-color: var(--leads-red);
        box-shadow: 0 0 0 3px rgba(192,4,21,0.1);
    }
    .crm-daterange-form .btn-success {
        background: linear-gradient(135deg,var(--leads-red),var(--leads-red2)) !important;
        border: none !important;
        border-radius: 0 8px 8px 0 !important;
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        padding: 7px 14px !important;
        color: #fff !important;
    }

    /* ── DataTable overrides ── */
    .dt-search { display: none !important; }
    .dt-container { width: 100% !important; }
    .dt-layout-row {
        width: 100% !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: wrap;
        gap: 8px;
    }
    .dt-layout-table { width: 100% !important; overflow-x: auto !important; }
    #escalation, table.dataTable { width: 100% !important; margin: 0 !important; }

    /* DT buttons */
    .dt-buttons { margin-bottom: 8px; }
    .dt-button {
        background: #f1f5f9 !important;
        color: var(--leads-text) !important;
        border: 1px solid var(--leads-border) !important;
        border-radius: 7px !important;
        padding: 5px 12px !important;
        font-size: 0.74rem !important;
        font-weight: 600 !important;
        transition: all 0.2s !important;
        margin-right: 4px !important;
    }
    .dt-button:hover {
        background: var(--leads-red) !important;
        color: #fff !important;
        border-color: var(--leads-red) !important;
        box-shadow: 0 3px 10px rgba(192,4,21,0.25) !important;
        transform: translateY(-1px);
    }
    .dt-paging .dt-paging-button.current,
    .dt-paging .dt-paging-button.current:hover {
        background: var(--leads-red) !important;
        color: #fff !important;
        border-color: var(--leads-red) !important;
    }

    /* ── Table header ── */
    #escalation thead.thead-dark tr:first-child th {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #e2e8f0;
        font-size: 0.69rem;
        font-weight: 700;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        padding: 13px 14px;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }
    #escalation thead.thead-dark tr:first-child th:first-child { border-radius: 10px 0 0 0; }
    #escalation thead.thead-dark tr:first-child th:last-child  { border-radius: 0 10px 0 0; }

    /* ── Filter row ── */
    .filters th {
        background: #f8fafc !important;
        padding: 7px 8px !important;
        border-bottom: 2px solid var(--leads-border) !important;
    }
    .filters input {
        width: 100%;
        padding: 5px 8px;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 500;
        color: var(--leads-text);
        background: #fff;
        transition: border 0.2s, box-shadow 0.2s;
    }
    .filters input:focus {
        outline: none;
        border-color: var(--leads-red);
        box-shadow: 0 0 0 3px rgba(192,4,21,0.08);
    }
    .filters input::placeholder { color: #94a3b8; }

    /* ── Body rows ── */
    #escalation tbody tr {
        transition: background 0.18s, box-shadow 0.18s;
        border-bottom: 1px solid #f1f5f9;
    }
    #escalation tbody tr:hover {
        background: #fdf2f4 !important;
        box-shadow: inset 3px 0 0 var(--leads-red);
    }
    #escalation tbody td {
        padding: 12px 14px;
        font-size: 0.80rem;
        font-weight: 500;
        color: var(--leads-text);
        border: none;
        vertical-align: middle;
    }

    /* ── Table Badges (Status, Duplicates) ── */
    #escalation .badge {
        font-size: 0.70rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px;
        padding: 5px 10px !important;
        border-radius: 20px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    #escalation .badge-warning {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }
    #escalation .badge-success {
        background-color: #dcfce7 !important;
        color: #15803d !important;
    }
    #escalation .badge-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
    }
    #escalation .badge-info {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
    }
    #escalation .badge-primary {
        background-color: #ede9fe !important;
        color: #6d28d9 !important;
    }
    #escalation .badge-secondary {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
    }

    /* ── Deal badges ── */
    .leads-deal-closed {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.70rem;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .leads-deal-pending {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.70rem;
        font-weight: 700;
        background: #fef3c7;
        color: #92400e;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    /* ── Actions button ── */
    .leads-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg,#1e293b,#0f172a);
        color: #e2e8f0 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 0.73rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.4px;
        transition: all 0.2s !important;
        white-space: nowrap;
    }
    .leads-action-btn:hover {
        background: linear-gradient(135deg,var(--leads-red),var(--leads-red2)) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(192,4,21,0.3) !important;
        transform: translateY(-1px);
    }

    /* ── Dropdown menu ── */
    .leads-action-menu {
        border: 1px solid var(--leads-border) !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 28px rgba(0,0,0,0.12) !important;
        padding: 6px !important;
        min-width: 140px !important;
    }
    .leads-action-menu .dropdown-item {
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 500;
        padding: 7px 12px;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .leads-action-menu .dropdown-item:hover { background: #f1f5f9; }
    .leads-action-menu .dropdown-item.text-danger:hover  { background: #fee2e2; }
    .leads-action-menu .dropdown-item.text-success:hover { background: #dcfce7; }
    .leads-action-menu .dropdown-divider { margin: 5px 0; border-color: var(--leads-border); }

    /* ── Modal headers ── */
    .leads-modal-header {
        background: linear-gradient(135deg,#1e293b,#0f172a);
        border-radius: 12px 12px 0 0;
        border: none;
        padding: 16px 20px;
    }
    .leads-modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    /* ── Pagination ── */
    .leads-pagination .pagination { justify-content: flex-end; gap: 3px; }
    .leads-pagination .page-link {
        border-radius: 7px !important;
        border: 1px solid var(--leads-border);
        color: var(--leads-text);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 12px;
        transition: all 0.18s;
    }
    .leads-pagination .page-item.active .page-link,
    .leads-pagination .page-link:hover {
        background: var(--leads-red);
        border-color: var(--leads-red);
        color: #fff;
    }

    /* ── Checkbox accent ── */
    .check, .selected_enquiries { accent-color: var(--leads-red); width:16px; height:16px; cursor:pointer; }

    /* ── Empty state ── */
    .leads-empty { text-align:center; padding:48px 20px; color:var(--leads-muted); font-size:0.9rem; }
    .leads-empty i { font-size:2rem; margin-bottom:10px; display:block; opacity:0.35; }

    @media (max-width: 768px) {
        .crm-toolbar-container { flex-direction: column; align-items: flex-start; }
        .leads-page-card { padding: 14px; }
    }
</style>

<div class="leads-page-card">

    {{-- ── Toolbar ── --}}
    <div class="crm-toolbar-container mb-3">
        <div class="crm-toolbar-left">
            @can('leads_create')
            <a class="btn btn-success text-uppercase" href="{{ route('enquiries.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New Lead</span>
            </a>
            @endcan

            <a class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#advance_search_filter" id="btn_advance_search">
                <i class="fas fa-search-plus"></i>
                <span class="big-btn-text">Advance Search Filter</span>
            </a>

            <a class="btn btn-warning text-uppercase" data-toggle="modal" data-target="#assign_leads_modal" id="assign_to" style="display:none;">
                <i class="fas fa-user-check"></i>
                <span class="big-btn-text">Assign Leads</span>
            </a>
        </div>

        <div class="crm-toolbar-right">
            @can('leads_import')
            <a class="btn btn-info text-uppercase" href="{{ route('enquiries.import_template') }}">
                <i class="fas fa-file-download"></i>
                <span class="big-btn-text">Import Template</span>
            </a>
            <a class="btn btn-success text-uppercase" href="{{ route('enquiries.import') }}">
                <i class="fas fa-file-import"></i>
                <span class="big-btn-text">Import Leads</span>
            </a>
            @endcan

            @if(auth()->user()->hasRole('Admin') || auth()->user()->export == 1)
            <a class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#export_leads_modal">
                <i class="fas fa-file-export"></i>
                <span class="big-btn-text">Export Leads</span>
            </a>
            @endif
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="crm-filter-bar mb-3">
        <div class="crm-filter-left">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle text-uppercase" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter mr-1"></i> Status: <strong>{{ isset($filter) ? ($filter === 'all' ? 'All' : ($filter === 'active' ? 'Active' : 'Lost')) : 'All' }}</strong>
                </button>
                <div class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item {{ (!isset($filter) || $filter === 'all') ? 'active font-weight-bold' : '' }}" href="{{ route('enquiries.filter', ['filter' => 'all']) }}">All</a>
                    <a class="dropdown-item {{ (isset($filter) && $filter === 'active') ? 'active font-weight-bold' : '' }}" href="{{ route('enquiries.filter', ['filter' => 'active']) }}">Active</a>
                    <a class="dropdown-item {{ (isset($filter) && $filter === 'lost') ? 'active font-weight-bold' : '' }}" href="{{ route('enquiries.filter', ['filter' => 'lost']) }}">Lost</a>
                </div>
            </div>
        </div>

        <div class="crm-filter-right">
            <form method="post" action="{{ route('enquiries.date_range') }}" class="crm-daterange-form">
                @csrf
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt mr-1 text-danger"></i> Date Range
                        </span>
                    </div>
                    <input type="text" class="form-control" name="daterange" placeholder="YYYY-MM-DD - YYYY-MM-DD" style="min-width:210px;" />
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success text-uppercase">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="table-responsive">
        <table class="table" id="escalation">
            <thead class="thead-dark">
                <tr>
                    @can('client_create')
                    <th class="text-uppercase" scope="col">
                        <input type="checkbox" class="check" id="checkAll">
                    </th>
                    @endcan
                    <th class="text-uppercase" scope="col" style="display:none;">#</th>
                    <th class="text-uppercase" scope="col">Name</th>
                    <th class="text-uppercase" scope="col">Contact No.</th>
                    <th class="text-uppercase" scope="col">Date</th>
                    <th class="text-uppercase" scope="col">Assigned To</th>
                    <th class="text-uppercase" scope="col">Status</th>
                    <th class="text-uppercase" scope="col">Deal</th>
                    <th class="text-uppercase" scope="col">Reference By</th>
                    <th class="text-uppercase" scope="col">Created Time</th>
                    <th class="text-uppercase" scope="col">Created By</th>
                    <th class="text-uppercase" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enquiries as $enquiry)
                @php
                    $totalDups = $duplicate_counts[trim($enquiry->contact_no)] ?? 0;
                    $rank = $duplicate_ranks[$enquiry->id] ?? ($totalDups > 1 ? 1 : 0);
                    $rowStyle = '';
                    $textClass = '';
                    $badgeClass = '';
                    if($totalDups > 1 && $rank > 0) {
                        if($rank == 1) {
                            $rowStyle = 'background-color: #E8F5E9;';
                            $textClass = 'text-success font-weight-bold';
                            $badgeClass = 'badge-success';
                        } elseif($rank == 2) {
                            $rowStyle = 'background-color: #E0F7FA;';
                            $textClass = 'text-info font-weight-bold';
                            $badgeClass = 'badge-info';
                        } elseif($rank == 3) {
                            $rowStyle = 'background-color: #FFF9C4;';
                            $textClass = 'text-warning font-weight-bold';
                            $badgeClass = 'badge-warning';
                        } elseif($rank == 4) {
                            $rowStyle = 'background-color: #FFE0B2;';
                            $textClass = 'text-orange font-weight-bold';
                            $badgeClass = 'badge-warning';
                        } elseif($rank >= 5) {
                            $rowStyle = 'background-color: #FFCDD2;';
                            $textClass = 'text-danger font-weight-bold';
                            $badgeClass = 'badge-danger';
                        }
                    }
                @endphp
                <tr style="{{ $rowStyle }}">
                    @can('client_create')
                    <td><input type="checkbox" class="selected_enquiries check" name="selected_enquiries[]" value="{{ $enquiry->id }}"></td>
                    @endcan
                    <td style="display:none;">{{ ($enquiries->currentPage() - 1) * $enquiries->perPage() + $loop->iteration }}</td>

                    {{-- Name --}}
                    <td style="font-weight:700; color:#0f172a;">{{ $enquiry->name }}</td>

                    {{-- Contact --}}
                    <td class="{{ $textClass }}">
                        {{ $enquiry->contact_no }}
                        @if($totalDups > 1 && $rank > 0)
                            <span class="badge {{ $badgeClass }}">Duplicate ({{ $rank }})</span>
                        @endif
                        <?php
                            $whatsapp_no = $enquiry->contact_no;
                            if(str_starts_with($whatsapp_no, '+')) {
                                $whatsapp_no = substr($whatsapp_no, 1);
                            }
                        ?>
                        <a href="https://wa.me/{{ $whatsapp_no }}"
                           class="btn btn-success btn-sm mx-2 d-xs-block d-sm-block d-md-none">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="tel:{{ $enquiry->contact_no }}"
                           class="btn btn-primary btn-sm mx-2 d-xs-block d-sm-block d-md-none">
                            <i class="fas fa-phone"></i>
                        </a>
                    </td>

                    {{-- Date --}}
                    <td>{{ $enquiry->created_at->format('d-M-Y') }}</td>

                    {{-- Assigned To --}}
                    <td>@if($enquiry->assignedTo) {{ $enquiry->assignedTo->displayName() }} @endif</td>

                    {{-- Status --}}
                    <td>
                        @if($enquiry->enquiry_status)
                        <span class="{{ App\Lancer\Utilities::getEnquiryStatusStyle($enquiry->enquiry_status->id) }}">
                            {{ $enquiry->enquiry_status->status }}
                        </span>
                        @endif
                    </td>

                    {{-- Deal --}}
                    <td>
                        @if($enquiry->enquiry_status_id == 5)
                            <span class="leads-deal-closed">
                                <i class="fas fa-check-circle" style="font-size:0.68rem;"></i> Closed Deal
                            </span>
                        @else
                            <span class="leads-deal-pending">
                                <i class="fas fa-hourglass-half" style="font-size:0.68rem;"></i> Pending
                            </span>
                        @endif
                    </td>

                    {{-- Reference By --}}
                    <td>{{ $enquiry->reference_by ?? '-' }}</td>

                    {{-- Created Time --}}
                    <td>{{ $enquiry->created_at->format('h:i A') }}</td>

                    {{-- Created By --}}
                    <td>{{ $enquiry->createdBy->name ?? 'N/A' }}</td>

                    {{-- Actions --}}
                    <td>
                        <div class="dropdown">
                            <a class="leads-action-btn dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                <i class="fas fa-cog" style="font-size:0.7rem;"></i> ACTIONS
                            </a>
                            <div id="{{ $enquiry->id }}" class="dropdown-menu leads-action-menu" aria-labelledby="dropdownMenuLink">
                                @can('leads_show')
                                <a class="dropdown-item text-primary"
                                    href="{{ route('enquiries.show', ['id' => $enquiry->id]) }}">
                                    <i class="fas fa-eye fa-fw"></i> View
                                </a>
                                @endcan
                                @if(auth()->user()->can('leads_edit') || $enquiry->assigned_to == auth()->user()->id)
                                <a class="dropdown-item text-primary"
                                    href="{{ route('enquiries.edit', ['id' => $enquiry->id]) }}">
                                    <i class="fas fa-edit fa-fw"></i> Edit
                                </a>
                                @endif
                                @if($enquiry->enquiry_status && !$enquiry->is_lost && $enquiry->enquiry_status->id < 4)
                                <a class="dropdown-item text-success"
                                    href="{{ route('enquiries.close', ['id' => $enquiry->id]) }}">
                                    <i class="fas fa-handshake fa-fw"></i> Close Deal
                                </a>
                                @endif
                                @can('leads_delete')
                                <div class="dropdown-divider"></div>
                                <a role="button" class="enquiry-lost-btn dropdown-item text-danger" style="cursor: pointer;"
                                   data-url="{{ route('enquiries.destroy', ['id' => $enquiry->id]) }}">
                                    <i class="fas fa-times-circle fa-fw"></i> Mark As Lost
                                </a>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach

                {{-- Required for mark delete action --}}
                <input type="hidden" id="deletedBtnText" value="Yes, mark it!">
                <input type="hidden" id="deletedTitle" value="Marked as lost!">
                <input type="hidden" id="deletedMsg" value="The selected enquiry has been successfully marked as lost.">
            </tbody>
        </table>

        @if (count($enquiries) < 1)
            <div class="leads-empty">
                <i class="fas fa-inbox"></i>
                No results found!
            </div>
        @endif
    </div>

    {{-- ══ Assign Leads Modal ══ --}}
    <div class="modal fade" id="assign_leads_modal" tabindex="-1" role="dialog" aria-labelledby="assignLeadsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content leads-modal-content">
                <div class="modal-header leads-modal-header">
                    <h5 class="modal-title text-white" id="assignLeadsLabel">
                        <i class="fas fa-user-check mr-2 text-warning"></i>Assign Leads
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ Url('/').'/enquiries' }}">
                    @csrf
                    <div class="modal-body py-4">
                        @if(auth()->user()->hasRole('Admin'))
                        <div class="form-group col-sm-12">
                            <label class="text-capitalize font-weight-bold" for="assigned_to">Assign To</label>
                            <select class="form-control js-example-basic-single" id="assigned_to" name="assigned_to">
                                <option value="">Select</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" class="selected_leads" name="selected_leads" value="">
                        @endif
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e2e8f0;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-danger" value="Assign">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ Export Leads Modal ══ --}}
    <div class="modal fade" id="export_leads_modal" tabindex="-1" role="dialog" aria-labelledby="exportLeadsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content leads-modal-content">
                <div class="modal-header leads-modal-header">
                    <h5 class="modal-title text-white" id="exportLeadsLabel">
                        <i class="fas fa-file-export mr-2 text-info"></i>Export Leads
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="get" action="{{ route('enquiries.export') }}">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="form-group col-sm-12">
                            <label class="text-capitalize font-weight-bold mb-3">Select Export Format</label><br>
                            <div style="display:flex; gap:10px; margin-top:8px;">
                                <input type="submit" class="btn btn-success px-4" name="file_type" value="CSV">
                                <input type="submit" class="btn btn-success px-4" name="file_type" value="Excel">
                                <input type="submit" class="btn btn-success px-4" name="file_type" value="Pdf">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e2e8f0;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ Advance Search Modal ══ --}}
    <div class="modal fade" id="advance_search_filter" tabindex="-1" role="dialog" aria-labelledby="advSearchLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content leads-modal-content">
                <div class="modal-header leads-modal-header">
                    <h5 class="modal-title text-white" id="advSearchLabel">
                        <i class="fas fa-search-plus mr-2"></i>Advance Search Filter
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('enquiries.advance_search') }}">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="form-group row">
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="name">Name</label>
                                <input type="text" class="form-control text-capitalize" id="name" name="advance[name]" placeholder="Full Name" value="">
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="advance[email]" placeholder="user@example.com" value="">
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="contact_no">Contact Number</label>
                                <input type="tel" class="form-control" id="contact_no" name="advance[contact_no]" placeholder="+910123456789" value="">
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="configuration">Configuration</label>
                                <select class="form-control js-example-basic-single" id="configuration" name="advance[configuration]">
                                    <option value="">Select</option>
                                    @foreach ($configurations as $configuration)
                                        <option value="{{ $configuration->id }}">{{ $configuration->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="assigned_to">Assign To</label>
                                <select class="form-control js-example-basic-single" id="assigned_to" name="advance[assigned_to]">
                                    <option value="">Select</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="text-capitalize" for="project">Project</label>
                                <select class="form-control js-example-basic-single" id="project" name="advance[project]">
                                    <option value="">Select</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e2e8f0;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-danger" value="Search">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 leads-pagination">
        {{ $enquiries->links() }}
    </div>

</div>
@stop


@section('js')

    <script src="{{ asset('js/table_utils.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js?v=1.1') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
    jQuery(function($){
        // Setup - add a text input to each header cell
        $('#escalation thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#escalation thead');

        var table = $('#escalation').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            autoWidth: false,
            layout: {
                topEnd: {
                    buttons: ['csv', 'excel', 'pdf', 'print']
                }
            },
            initComplete: function() {
                var api = this.api();

                api.columns().every(function() {
                    var column = this;
                    var index = column.index();
                    var cell = $('.filters th').eq(index);
                    var title = $(column.header()).text().trim();

                    if (title.toLowerCase() !== 'actions' && title !== '') {
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search" />');
                        cell.html(input);

                        $(input).on('keyup change clear', function() {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    } else {
                        cell.html('');
                    }
                });
            }
        });

        $('input[name="daterange"]').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            startDate: '2024-05-01',
            endDate: '2024-05-31'
        });

        var data = [];
        $('#assign_to').click(function(event) {
            $('.selected_enquiries:checked').each(function() {
                data.push(this.value);
            });
            $('.selected_leads').val(data);
        });
    });

    $('#assign_to').hide();
    $('.selected_enquiries').click(function(event) {
        $('#assign_to').show();
    });
    jQuery("#checkAll").click(function () {
        $('#assign_to').show();
        jQuery(".check").prop('checked', jQuery(this).prop('checked'));
    });
    </script>
@stop

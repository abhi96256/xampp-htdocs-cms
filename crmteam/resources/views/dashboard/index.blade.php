@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 style="font-size:1.1rem; margin-bottom:0;">Dashboard</h1>
@stop

@section('content')
<div class="dashboard-wrapper">

    {{-- Row 1: Stats Cards (2x2 on mobile, 4 in a row on desktop) --}}
    <div class="row mb-2">
        <div class="col-6 col-md-3 mb-2 mb-md-0">
            <a href="{{ route('enquiries.index') }}" class="text-decoration-none text-dark">
                <div class="card stat-card px-3 py-2 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Leads</div>
                            <div class="stat-value">{{ $total_leads }}</div>
                        </div>
                        <div class="icon-shape icon-blue"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-2 mb-md-0">
            <a href="{{ route('monthly_visits.index') }}" class="text-decoration-none text-dark">
                <div class="card stat-card px-3 py-2 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Site Visits</div>
                            <div class="stat-value">{{ $monthly_visits }}</div>
                        </div>
                        <div class="icon-shape icon-green"><i class="fas fa-route"></i></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-2 mb-md-0">
            <a href="{{ route('expenses.index') }}" class="text-decoration-none text-dark">
                <div class="card stat-card px-3 py-2 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Monthly Expenses</div>
                            <div class="stat-value">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $expense_paid_this_month }}</div>
                        </div>
                        <div class="icon-shape icon-red"><i class="fas fa-money-bill"></i></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-2 mb-md-0">
            <a href="{{ route('clients.index') }}" class="text-decoration-none text-dark">
                <div class="card stat-card px-3 py-2 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Happy Clients</div>
                            <div class="stat-value">{{ $total_happy_clients }}</div>
                        </div>
                        <div class="icon-shape icon-yellow"><i class="fas fa-smile"></i></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Row 2: Followups + Dues --}}
    <div class="row mb-2 dashboard-mid-row">

        {{-- Followups --}}
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <div class="card compact-card h-100">
                <div class="card-header-compact">
                    <span><i class="fas fa-bell text-primary mr-1"></i> Followups</span>
                    <a href="{{ route('enquiries.index') }}" class="btn btn-primary btn-xs float-right">View All</a>
                </div>
                <ul class="nav nav-tabs nav-sm d-flex flex-row flex-nowrap" style="border-bottom: none;" id="followupsTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" role="tab">Today</a></li>
                    <li class="nav-item"><a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" role="tab">Tomorrow</a></li>
                    <li class="nav-item"><a class="nav-link" id="week-tab" data-toggle="tab" href="#week" role="tab">Week</a></li>
                    <li class="nav-item"><a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab">Month</a></li>
                </ul>
                <div class="tab-content compact-table-wrap" id="followupsTabContent">
                    <div class="tab-pane fade show active" id="today" role="tabpanel">
                        <table class="table table-sm compact-table">
                            <thead><tr><th>Name</th><th>Remark</th><th>Time</th><th></th></tr></thead>
                            <tbody>
                            @if(count($followups_today) > 0)
                                @foreach($followups_today as $followup)
                                <tr>
                                    <td>{{ $followup->enquiry->name }}</td>
                                    <td>{{ Str::limit($followup->remark, 15) }}</td>
                                    <td>{{ $followup->date_time->format('h:i a') }}</td>
                                    <td><a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-xs">View</a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="4" class="text-center text-muted py-2">No followups found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="tomorrow" role="tabpanel">
                        <table class="table table-sm compact-table">
                            <thead><tr><th>Name</th><th>Remark</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                            @if(count($followups_tomorrow) > 0)
                                @foreach($followups_tomorrow as $followup)
                                <tr>
                                    <td>{{ $followup->enquiry->name }}</td>
                                    <td>{{ Str::limit($followup->remark, 15) }}</td>
                                    <td>{{ $followup->date_time->format('d-M h:i a') }}</td>
                                    <td><a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-xs">View</a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="4" class="text-center text-muted py-2">No followups found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="week" role="tabpanel">
                        <table class="table table-sm compact-table">
                            <thead><tr><th>Name</th><th>Remark</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                            @if(count($followups_week) > 0)
                                @foreach($followups_week as $followup)
                                <tr>
                                    <td>{{ $followup->enquiry->name }}</td>
                                    <td>{{ Str::limit($followup->remark, 15) }}</td>
                                    <td>{{ $followup->date_time->format('d-M h:i a') }}</td>
                                    <td><a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-xs">View</a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="4" class="text-center text-muted py-2">No followups found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="month" role="tabpanel">
                        <table class="table table-sm compact-table">
                            <thead><tr><th>Name</th><th>Remark</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                            @if(count($followups_month) > 0)
                                @foreach($followups_month as $followup)
                                <tr>
                                    <td>{{ $followup->enquiry->name }}</td>
                                    <td>{{ Str::limit($followup->remark, 15) }}</td>
                                    <td>{{ $followup->date_time->format('d-M h:i a') }}</td>
                                    <td><a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-xs">View</a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="4" class="text-center text-muted py-2">No followups found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dues --}}
        <div class="col-12 col-md-6">
            <div class="card compact-card h-100">
                <div class="card-header-compact">
                    <span><i class="fas fa-rupee-sign text-danger mr-1"></i> Dues</span>
                    @if(auth()->user()->hasRole('Admin'))
                        <a href="{{ route('dues.index') }}" class="btn btn-primary btn-xs float-right">View All</a>
                    @endif
                </div>
                <ul class="nav nav-tabs nav-sm d-flex flex-row flex-nowrap" style="border-bottom: none;" id="duesTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="dues-all-tab" data-toggle="tab" href="#dues-all" role="tab">All Pending/Rejected</a></li>
                </ul>
                <div class="tab-content compact-table-wrap" id="duesTabContent">
                    <div class="tab-pane fade show active" id="dues-all" role="tabpanel">
                        <table class="table table-sm compact-table">
                            <thead><tr><th>Name</th><th>Payee</th><th>Amount</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                            @if(isset($dues_all) && count($dues_all) > 0)
                                @foreach($dues_all as $due)
                                <tr>
                                    <td>{{ Str::limit($due->createdBy->name ?? 'N/A', 12) }}</td>
                                    <td>{{ Str::limit($due->payee, 12) }}</td>
                                    <td class="text-danger font-weight-bold">{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ number_format($due->amount_paid, 0) }}</td>
                                    <td>
                                        <span class="badge {{ $due->status_by_admin === 'Rejected' ? 'badge-danger' : 'badge-warning' }}">{{ $due->status_by_admin }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.edit', ['id' => $due->id]) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5" class="text-center text-muted py-2">No dues found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Row 3: Earnings & Expenses Chart - Full Width --}}
    @php
        $total_6m_earnings = array_sum($monthly_earnings_chart);
        $total_6m_expenses = array_sum($monthly_expenses_chart);
        $net_6m_balance = $total_6m_earnings - $total_6m_expenses;
        
        $max_expense_val = 0;
        $max_expense_month = 'N/A';
        if (!empty($monthly_expenses_chart)) {
            $max_expense_val = max($monthly_expenses_chart);
            $max_idx = array_search($max_expense_val, $monthly_expenses_chart);
            $max_expense_month = $months[$max_idx] ?? 'N/A';
        }
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card premium-analytics-card">
                {{-- Header --}}
                <div class="analytics-card-header">
                    <div class="analytics-header-title-box">
                        <div class="analytics-icon-gradient">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="analytics-main-title">
                                Financial Performance &amp; Cash Flow
                                <span class="analytics-live-tag"><span class="pulse-dot"></span> Live 6M</span>
                            </div>
                            <div class="analytics-sub-title">Interactive comparison of revenue collections vs operating expenses</div>
                        </div>
                    </div>
                    <div class="analytics-header-controls">
                        <div class="chart-toggle-group" id="chartSeriesToggles">
                            <button type="button" class="btn-chart-toggle active" data-series="all">
                                <i class="fas fa-layer-group mr-1"></i> Both
                            </button>
                            <button type="button" class="btn-chart-toggle" data-series="earnings">
                                <span class="toggle-dot dot-emerald"></span> Inflow
                            </button>
                            <button type="button" class="btn-chart-toggle" data-series="expenses">
                                <span class="toggle-dot dot-rose"></span> Outflow
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick KPI Highlights Shelf --}}
                <div class="analytics-kpi-shelf">
                    <div class="kpi-mini-card kpi-card-inflow">
                        <div class="kpi-mini-icon"><i class="fas fa-arrow-down"></i></div>
                        <div class="kpi-mini-info">
                            <span class="kpi-mini-label">Total Inflow (6M)</span>
                            <span class="kpi-mini-val text-emerald">₹{{ number_format($total_6m_earnings) }}</span>
                        </div>
                        <span class="kpi-mini-sub-badge bg-emerald-sub"><i class="fas fa-plus mr-1"></i>Collections</span>
                    </div>

                    <div class="kpi-mini-card kpi-card-outflow">
                        <div class="kpi-mini-icon"><i class="fas fa-arrow-up"></i></div>
                        <div class="kpi-mini-info">
                            <span class="kpi-mini-label">Total Outflow (6M)</span>
                            <span class="kpi-mini-val text-rose">₹{{ number_format($total_6m_expenses) }}</span>
                        </div>
                        <span class="kpi-mini-sub-badge bg-rose-sub"><i class="fas fa-receipt mr-1"></i>Expenses</span>
                    </div>

                    <div class="kpi-mini-card kpi-card-net">
                        <div class="kpi-mini-icon"><i class="fas fa-scale-balanced"></i></div>
                        <div class="kpi-mini-info">
                            <span class="kpi-mini-label">Net Balance</span>
                            <span class="kpi-mini-val {{ $net_6m_balance >= 0 ? 'text-emerald' : 'text-rose' }}">
                                {{ $net_6m_balance >= 0 ? '+' : '' }}₹{{ number_format($net_6m_balance) }}
                            </span>
                        </div>
                        <span class="kpi-mini-sub-badge {{ $net_6m_balance >= 0 ? 'bg-emerald-sub' : 'bg-rose-sub' }}">
                            {{ $net_6m_balance >= 0 ? 'Surplus' : 'Deficit' }}
                        </span>
                    </div>

                    <div class="kpi-mini-card kpi-card-burn">
                        <div class="kpi-mini-icon"><i class="fas fa-fire-alt"></i></div>
                        <div class="kpi-mini-info">
                            <span class="kpi-mini-label">Peak Expense Month</span>
                            <span class="kpi-mini-val text-slate">{{ $max_expense_month }}</span>
                        </div>
                        <span class="kpi-mini-sub-badge bg-slate-sub">₹{{ number_format($max_expense_val) }}</span>
                    </div>
                </div>

                {{-- Chart Area --}}
                <div class="analytics-chart-canvas-wrap">
                    <div id="earningsChart"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
/* Remove AdminLTE default content padding */
.content-wrapper > .content {
    padding: 8px 12px !important;
}
.content-header {
    padding: 6px 12px !important;
}

.dashboard-wrapper {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

@media (min-width: 992px) {
    .dashboard-wrapper {
        min-height: calc(100vh - 100px);
    }
    .dashboard-mid-row {
        height: 200px;
    }
}

@media (max-width: 991.98px) {
    .dashboard-wrapper {
        height: auto !important;
        min-height: auto !important;
    }
    .compact-table-wrap {
        max-height: 180px !important;
    }
}

/* Stat Cards */
.stat-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    background: #fff;
    transition: transform 0.2s ease;
}
.stat-card:hover { transform: translateY(-3px); }
.stat-label { font-size: 0.72rem; color: #6c757d; font-weight: 600; }
.stat-value { font-size: 1.2rem; font-weight: 700; color: #212529; margin-top: 2px; }

.icon-shape {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.icon-blue  { background: rgba(0,123,255,0.1);  color: #007bff; }
.icon-green { background: rgba(40,167,69,0.1);  color: #28a745; }
.icon-red   { background: rgba(220,53,69,0.1);  color: #dc3545; }
.icon-yellow{ background: rgba(255,193,7,0.1);  color: #ffc107; }

/* Compact Cards */
.compact-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    background: #fff;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.card-header-compact {
    padding: 6px 10px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #495057;
    border-bottom: 1px solid #f0f0f0;
    background: #f8f9fa;
    flex-shrink: 0;
}
.card-body-compact {
    flex: 1;
    overflow: hidden;
    padding: 4px;
}

/* Compact Tables */
.compact-table-wrap {
    flex: 1;
    min-height: 0;
    max-height: 130px;
    overflow-y: auto;
    overflow-x: hidden;
}
.compact-table {
    font-size: 0.72rem;
    margin-bottom: 0;
}
.compact-table thead th {
    font-size: 0.68rem;
    padding: 4px 6px;
    background: #343a40;
    color: #fff;
    border: none;
    white-space: nowrap;
}
.compact-table tbody td {
    padding: 3px 6px;
    vertical-align: middle;
    border-color: #f0f0f0;
}
.compact-table tbody tr:hover { background: #f8f9fa; }

/* Nav tabs compact */
.nav-sm .nav-link {
    padding: 3px 8px;
    font-size: 0.72rem;
}
.nav-sm .nav-link.active {
    font-weight: 700;
    color: #007bff;
}

/* XS Button */
.btn-xs {
    padding: 1px 6px;
    font-size: 0.68rem;
    border-radius: 4px;
}

/* Scrollbar Customization for Tables */
.compact-table-wrap::-webkit-scrollbar { width: 8px; }
.compact-table-wrap::-webkit-scrollbar-track { background: #f8f9fa; border-radius: 4px; border: 1px solid #e9ecef; }
.compact-table-wrap::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
.compact-table-wrap::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

/* ========================================================
   ULTRA-PREMIUM REALISTIC FINANCIAL ANALYTICS STYLING
   ======================================================== */
.premium-analytics-card {
    border-radius: 14px;
    border: 1px solid rgba(226, 232, 240, 0.9);
    box-shadow: 0 6px 20px -3px rgba(15, 23, 42, 0.07), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
    background: #ffffff;
    overflow: hidden;
    margin-bottom: 0;
    transition: all 0.3s ease;
}
.premium-analytics-card:hover {
    box-shadow: 0 10px 28px -4px rgba(15, 23, 42, 0.1), 0 4px 10px -2px rgba(15, 23, 42, 0.05);
}

.analytics-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px 16px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border-bottom: 1px solid #edf2f7;
}

.analytics-header-title-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.analytics-icon-gradient {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    flex-shrink: 0;
}

.analytics-main-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.2;
}

.analytics-live-tag {
    font-size: 0.65rem;
    font-weight: 700;
    background: #eef2ff;
    color: #4f46e5;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    border: 1px solid #e0e7ff;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulseGlow 2s infinite;
}

@keyframes pulseGlow {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.analytics-sub-title {
    font-size: 0.72rem;
    color: #64748b;
    margin-top: 2px;
    font-weight: 500;
}

/* Interactive Filter Toggle Group */
.chart-toggle-group {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 8px;
    gap: 3px;
    border: 1px solid #e2e8f0;
}

.btn-chart-toggle {
    border: none;
    background: transparent;
    padding: 3px 10px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    outline: none !important;
}

.btn-chart-toggle:hover {
    color: #1e293b;
}

.btn-chart-toggle.active {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.toggle-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
}

.dot-emerald {
    background: #10b981;
    box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
}

.dot-rose {
    background: #f43f5e;
    box-shadow: 0 0 6px rgba(244, 63, 94, 0.6);
}

/* Quick KPI Highlights Shelf */
.analytics-kpi-shelf {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 8px 16px 4px 16px;
    background: #ffffff;
}

.kpi-mini-card {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 9px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    transition: all 0.2s ease;
}

.kpi-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    background: #ffffff;
    border-color: #e2e8f0;
}

.kpi-mini-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
    margin-right: 8px;
}

.kpi-card-inflow .kpi-mini-icon {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
}

.kpi-card-outflow .kpi-mini-icon {
    background: rgba(244, 63, 94, 0.12);
    color: #f43f5e;
}

.kpi-card-net .kpi-mini-icon {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
}

.kpi-card-burn .kpi-mini-icon {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
}

.kpi-mini-info {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.kpi-mini-label {
    font-size: 0.65rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.kpi-mini-val {
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.2;
    margin-top: 1px;
}

.text-emerald { color: #059669 !important; }
.text-rose { color: #e11d48 !important; }
.text-slate { color: #334155 !important; }

.kpi-mini-sub-badge {
    font-size: 0.62rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 5px;
    flex-shrink: 0;
}

.bg-emerald-sub {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
}

.bg-rose-sub {
    background: rgba(244, 63, 94, 0.1);
    color: #be123c;
}

.bg-slate-sub {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

/* Canvas Wrap */
.analytics-chart-canvas-wrap {
    padding: 0 10px 8px 10px;
    background: #ffffff;
    position: relative;
}

/* Custom Ultra-Crisp Glassmorphic Tooltip */
.custom-chart-tooltip {
    background: rgba(15, 23, 42, 0.94);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.28);
    color: #ffffff;
    overflow: hidden;
    min-width: 170px;
}

.c-tt-head {
    background: rgba(255, 255, 255, 0.07);
    padding: 6px 12px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    color: #cbd5e1;
}

.c-tt-body {
    padding: 8px 12px;
}

.c-tt-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 4px;
    font-size: 0.73rem;
}

.c-tt-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #94a3b8;
    font-weight: 500;
}

.c-tt-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.c-tt-val {
    font-weight: 700;
}

.c-tt-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 6px 0;
}

.c-tt-net-row {
    font-size: 0.75rem;
    font-weight: 700;
}
.c-tt-net-row.net-pos .c-tt-val { color: #34d399; }
.c-tt-net-row.net-neg .c-tt-val { color: #fb7185; }

.c-tt-pill {
    font-size: 0.6rem;
    padding: 1px 5px;
    border-radius: 4px;
    margin-left: 4px;
}
.net-pos .c-tt-pill { background: rgba(52, 211, 153, 0.2); color: #34d399; }
.net-neg .c-tt-pill { background: rgba(251, 113, 133, 0.2); color: #fb7185; }

@media (max-width: 991.98px) {
    .analytics-kpi-shelf {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .analytics-card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .analytics-header-controls {
        width: 100%;
    }
    .chart-toggle-group {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .btn-chart-toggle {
        flex: 1;
        justify-content: center;
    }
}
</style>
@stop   

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Dynamic responsive height calculation
    var chartH = Math.max(220, Math.min(300, Math.floor(window.innerHeight * 0.35)));

    // Modern High-End Colors
    var colorEarnings = '#10B981'; // Vibrant Emerald
    var colorExpenses = '#F43F5E'; // Modern Coral Rose

    var monthsData = {!! json_encode($months) !!};
    var earningsData = {!! json_encode($monthly_earnings_chart) !!};
    var expensesData = {!! json_encode($monthly_expenses_chart) !!};

    // ApexCharts Realistic Configuration
    var options = {
        series: [
            { 
                name: 'Earnings', 
                data: earningsData 
            },
            { 
                name: 'Expenses', 
                data: expensesData 
            }
        ],
        chart: { 
            type: 'area', 
            height: chartH, 
            fontFamily: 'Inter, system-ui, -apple-system, sans-serif',
            toolbar: { 
                show: false 
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: [0, 1],
                top: 5,
                left: 0,
                blur: 7,
                color: [colorEarnings, colorExpenses],
                opacity: 0.22
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 850,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        colors: [colorEarnings, colorExpenses],
        dataLabels: { 
            enabled: false 
        },
        stroke: { 
            curve: 'smooth', 
            width: [3.5, 3.5],
            lineCap: 'round'
        },
        fill: { 
            type: 'gradient', 
            gradient: { 
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.5, 
                opacityFrom: 0.32, 
                opacityTo: 0.01, 
                stops: [0, 85, 100] 
            } 
        },
        markers: {
            size: 0,
            strokeColors: '#ffffff',
            strokeWidth: 2.5,
            hover: {
                size: 6.5,
                sizeOffset: 2
            }
        },
        grid: {
            show: true,
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 15,
                bottom: 0,
                left: 10
            }
        },
        xaxis: { 
            categories: monthsData, 
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: { 
                style: { 
                    fontSize: '11px',
                    fontWeight: 600,
                    colors: '#64748b'
                } 
            } 
        },
        yaxis: { 
            labels: { 
                style: { 
                    fontSize: '11px',
                    fontWeight: 600,
                    colors: '#64748b'
                }, 
                formatter: function(val) {
                    if (val >= 100000) {
                        return "₹" + (val / 100000).toFixed(1) + "L";
                    } else if (val >= 1000) {
                        return "₹" + (val / 1000).toFixed(0) + "K";
                    }
                    return "₹" + val;
                }
            } 
        },
        legend: { 
            position: 'top', 
            horizontalAlign: 'right',
            fontSize: '11.5px', 
            fontFamily: 'inherit',
            fontWeight: 600,
            offsetY: -5,
            markers: {
                width: 9,
                height: 9,
                radius: 12,
                offsetX: -3
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        },
        tooltip: { 
            custom: function({series, seriesIndex, dataPointIndex, w}) {
                var month = monthsData[dataPointIndex] || '';
                var earning = series[0][dataPointIndex] || 0;
                var expense = series[1][dataPointIndex] || 0;
                var net = earning - expense;
                var netSign = net >= 0 ? '+' : '';
                var netClass = net >= 0 ? 'net-pos' : 'net-neg';
                var netBadge = net >= 0 ? 'Surplus' : 'Deficit';

                return '<div class="custom-chart-tooltip">' +
                    '<div class="c-tt-head">' +
                        '<i class="far fa-calendar-alt mr-1"></i> ' + month +
                    '</div>' +
                    '<div class="c-tt-body">' +
                        '<div class="c-tt-row">' +
                            '<div class="c-tt-item"><span class="c-tt-dot bg-emerald"></span> Earnings:</div>' +
                            '<div class="c-tt-val text-emerald">₹' + earning.toLocaleString('en-IN') + '</div>' +
                        '</div>' +
                        '<div class="c-tt-row">' +
                            '<div class="c-tt-item"><span class="c-tt-dot bg-rose"></span> Expenses:</div>' +
                            '<div class="c-tt-val text-rose">₹' + expense.toLocaleString('en-IN') + '</div>' +
                        '</div>' +
                        '<div class="c-tt-divider"></div>' +
                        '<div class="c-tt-row c-tt-net-row ' + netClass + '">' +
                            '<div class="c-tt-item"><i class="fas fa-coins mr-1"></i> Net Flow:</div>' +
                            '<div class="c-tt-val">' + netSign + '₹' + net.toLocaleString('en-IN') + ' <span class="c-tt-pill">' + netBadge + '</span></div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }
        }
    };

    var earningsChart = new ApexCharts(document.querySelector("#earningsChart"), options);
    earningsChart.render();

    // Interactive Series Toggles
    document.querySelectorAll('.btn-chart-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-chart-toggle').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            var seriesType = this.getAttribute('data-series');

            if (seriesType === 'all') {
                earningsChart.showSeries('Earnings');
                earningsChart.showSeries('Expenses');
            } else if (seriesType === 'earnings') {
                earningsChart.showSeries('Earnings');
                earningsChart.hideSeries('Expenses');
            } else if (seriesType === 'expenses') {
                earningsChart.hideSeries('Earnings');
                earningsChart.showSeries('Expenses');
            }
        });
    });
});
</script>
@stop

<?php

namespace App\Http\Controllers;

use App\Lancer\Utilities;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Expense;
use App\Models\FollowUp;
use App\Models\MonthlyVisit;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');

        $enquiryQuery = Enquiry::query();
        $clientQuery = Client::query();
        $paymentQuery = Payment::query();
        $expenseQuery = Expense::query();
        
        $followups_today_query = FollowUp::whereDate('date_time', Carbon::today());
        $followups_tomorrow_query = FollowUp::whereDate('date_time', Carbon::tomorrow());
        $followups_week_query = FollowUp::whereBetween('date_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        $followups_month_query = FollowUp::whereMonth('date_time', Carbon::now()->month)->whereYear('date_time', Carbon::now()->year);

        $monthlyVisitQuery = MonthlyVisit::query();

        if (!$isAdmin) {
            $enquiryQuery->where('assigned_to', $user->id);
            $clientQuery->where('closed_by', $user->id);
            $paymentQuery->whereHas('client', function($q) use ($user) {
                $q->where('closed_by', $user->id);
            });
            $expenseQuery->where('created_by', $user->id);

            $followups_today_query->whereHas('enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $followups_tomorrow_query->whereHas('enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $followups_week_query->whereHas('enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $followups_month_query->whereHas('enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $monthlyVisitQuery->where('user_id', $user->id);
        }

        // for top card shelf
        $total_leads = (clone $enquiryQuery)->count();
        $monthly_visits = (clone $monthlyVisitQuery)->whereMonth('date_of_visit', Carbon::now()->month)->whereYear('date_of_visit', Carbon::now()->year)->count();

        $expense_paid_this_month = (clone $expenseQuery)
            ->whereMonth('date_of_payment', Carbon::now()->month)
            ->whereYear('date_of_payment', Carbon::now()->year)
            ->where('status', 'paid')
            ->sum('amount_paid');
        $expense_paid_this_month = Utilities::numberReadableIndianFormat($expense_paid_this_month);

        $total_happy_clients = (clone $clientQuery)->count();

        // for followups and dues
        $followups_today    = $followups_today_query->orderBy('date_time', 'asc')->get();
        $followups_tomorrow = $followups_tomorrow_query->orderBy('date_time', 'asc')->get();
        $followups_week     = $followups_week_query->orderBy('date_time', 'asc')->get();
        $followups_month    = $followups_month_query->orderBy('date_time', 'asc')->get();

        // Dues are now defined as Pending or Rejected Expenses by Admin
        $dues_all = [];
        if($isAdmin) {
            $dues_all = (clone $expenseQuery)->whereIn('status_by_admin', ['Pending', 'Rejected'])->orderBy('updated_at', 'desc')->get();
        }

        // Chart Data Generation
        $months = [];
        $monthly_earnings_chart = [];
        $monthly_expenses_chart = [];
        $monthly_enquiries_chart = [];
        $monthly_clients_chart = [];
        
        for($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $earned = (clone $paymentQuery)->whereNotNull('date_of_payment')
                                           ->whereYear('date_of_payment', $date->year)
                                           ->whereMonth('date_of_payment', $date->month)
                                           ->sum('amount');
            $monthly_earnings_chart[] = $earned;

            $expense = (clone $expenseQuery)
                ->whereYear('date_of_payment', $date->year)
                ->whereMonth('date_of_payment', $date->month)
                ->where('status', 'paid')
                ->sum('amount_paid');
            $monthly_expenses_chart[] = $expense;
            
            $enqs = (clone $enquiryQuery)->whereYear('created_at', $date->year)
                                         ->whereMonth('created_at', $date->month)
                                         ->count();
            $monthly_enquiries_chart[] = $enqs;

            $clients = (clone $clientQuery)->whereYear('created_at', $date->year)
                                           ->whereMonth('created_at', $date->month)
                                           ->count();
             $monthly_clients_chart[] = $clients;
        }

        $paid_dues_count = (clone $paymentQuery)->whereNotNull('date_of_payment')->count();
        $unpaid_dues_count = (clone $paymentQuery)->whereNull('date_of_payment')->count();

        return view('dashboard.index', compact(
            'total_leads', 'monthly_visits', 'expense_paid_this_month', 'total_happy_clients',  
            'followups_today', 'followups_tomorrow', 'followups_week', 'followups_month', 'dues_all',
            'months', 'monthly_earnings_chart', 'monthly_expenses_chart', 'paid_dues_count', 'unpaid_dues_count',
            'monthly_enquiries_chart', 'monthly_clients_chart'
        ));
    }
}

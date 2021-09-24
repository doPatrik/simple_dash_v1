<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Notification\NotificationController;
use App\Models\NewBill;
use App\Models\User;
use App\Notifications\BillPaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $incomingBills = [];
        $now = Carbon::now();
        $currentYear = $now->copy()->year;

        $bills = $user->bills();
        $allBills = $bills->select([
            DB::raw("SUM(price) as price"),
            DB::raw("COUNT(id_bill) as db")
        ])->where('is_paid', false)->first();

        $monthlyBills = $bills->select([
            DB::raw("SUM(price) as price"),
            DB::raw("COUNT(id_bill) as db")
        ])
            ->where('is_paid', false)
            ->whereMonth('dead_line', Carbon::now()->month)->first();

        $nextBill = $user->bills()->orderBy('dead_line')->where('is_paid', false)->first();

        $incomingBillsData = $user->bills()->with('provider')
            ->whereBetween('dead_line', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])
            ->where('is_paid', false)
            ->orderBy('dead_line')
            ->get();

        $now = Carbon::now();
        $incomingBillsData->each(function ($item) use (&$incomingBills, $now) {
                $deadLine = Carbon::parse($item->dead_line);
                $diff = $now->diff($deadLine)->days;
                $remainingDay = $now > $deadLine
                    ? '0'
                    : $diff +1;

                $incomingBills[] = [
                    'id_bill' => $item->id_bill,
                    'dead_line' => $item->dead_line,
                    'price' => $item->price,
                    'label' => $item->provider->label,
                    'name' => $item->provider->name,
                    'color_code' => $item->provider->color_code,
                    'remainingDay' => $remainingDay,
                ];
            });


        return view('home', compact('allBills', 'monthlyBills', 'nextBill', 'currentYear', 'incomingBills'));
    }
}

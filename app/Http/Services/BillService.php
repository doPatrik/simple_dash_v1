<?php


namespace App\Http\Services;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillService
{
    public function getBillsByMonth(Collection $collection)
    {

        $bills = $collection;
        $data = [];
        $now = Carbon::now();

        $bills->each(function ($item) use(&$data, $now) {
            $deadLine = Carbon::parse($item->dead_line);
            $diff = $now->diff($deadLine)->days;
            $remainingDay = $now > $deadLine
                ? '0'
                : $diff +1;

            $data[$item->month][] = [
                'id_bill' => $item->id_bill,
                'id_provider' => $item->provider->id_provider,
                'dead_line' => $item->dead_line,
                'price' => $item->price,
                'label' => $item->provider->label,
                'name' => $item->provider->name,
                'color_code' => $item->provider->color_code,
                'remainingDay' => $remainingDay,
                'is_paid' => $item->is_paid,
                'updated_at' => $item->paid_date,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        });

        return $data;
    }

    public function getBills()
    {
        /** @var User $user */
        $user = Auth::user();
        $bills = $user->bills()->with('provider')->select([
            'id_bill',
            'id_user',
            'price',
            'dead_line',
            'id_provider',
            'is_paid',
            'start_date',
            'end_date',
            DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d') as paid_date"),
        ])
            ->orderBy('dead_line')->get();

        $data = [];
        $now = Carbon::now();

        $bills->each(function ($item) use(&$data, $now) {
            $deadLine = Carbon::parse($item->dead_line);
            $diff = $now->diff($deadLine)->days;
            $remainingDay = $now > $deadLine
                ? '0'
                : $diff +1;

            $data[] = [
                'id_bill' => $item->id_bill,
                'id_provider' => $item->provider->id_provider,
                'dead_line' => $item->dead_line,
                'price' => $item->price,
                'label' => $item->provider->label,
                'name' => $item->provider->name,
                'color_code' => $item->provider->color_code,
                'remainingDay' => $remainingDay,
                'is_paid' => $item->is_paid,
                'updated_at' => $item->paid_date,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        });

        return $data;
    }
}

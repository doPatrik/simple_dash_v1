<?php


namespace App\Http\Api\Home;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartDataApi
{
    public function getBarChartData()
    {
        /** @var User $user */
        $user = Auth::user();
        $bills = $user->bills()->select([
            DB::raw("DATE_FORMAT(dead_line, '%m') as month"),
            DB::raw("SUM(price) as price"),
        ])
            ->groupBy('month')
            ->orderBy('month')->get();

        $prices = array_fill(0,12,0);

        foreach ($bills as $bill) {
            $key = intval($bill['month']) - 1;
            $prices[$key] = $bill['price'];
        }

        $data = [
            'labels' =>  [
                'Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember',
                'Október', 'November', 'December',
            ],
            'datasets' => [
                ['label' => 'Összeg',
                    'backgroundColor' => [
                        'rgba(16, 196, 0, 0.3)',
                        'rgba(245, 57, 0, 0.3)',
                        'rgba(0, 127, 245, 0.3)',
                        'rgba(159, 0, 245, 0.3)',
                        'rgba(237, 226, 5, 0.3)',
                        'rgba(240, 157, 5, 0.3)',
                        'rgba(252, 3, 207, 0.3)',
                        'rgba(64, 226, 237, 0.3)',
                        'rgba(92, 74, 56, 0.3)',
                        'rgba(168, 84, 0, 0.3)',
                        'rgba(56, 50, 45, 0.3)',
                        'rgba(209, 0, 80, 0.3)'],
                    'data' => $prices]
            ]
        ];

        return response()->json($data);
    }

    public function getLineChartData()
    {
        /** @var User $user */
        $user = Auth::user();
        $expenditures = $user->expenditures()->select([
           DB::raw("DATE_FORMAT(purchase_date, '%m') as month"),
           DB::raw("SUM(price) as price"),
        ])
            ->groupBy('month')
            ->orderBy('month')->get();

        $prices = array_fill(0,12,0);

        foreach ($expenditures as $expenditure) {
            $key = intval($expenditure['month']) - 1;
            $prices[$key] = $expenditure['price'];
        }

        $data = [
            'labels' =>  [
                'Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember',
                'Október', 'November', 'December',
            ],
            'datasets' => [
                ['label' => 'Összeg', 'backgroundColor' => 'rgba(16, 196, 0, 0.3)', 'borderColor' => 'rgb(75, 192, 192)', 'tension' => '0.2', 'data' => $prices]
            ],
        ];

        return response()->json($data);
    }

    public function getDoughnutChartData()
    {
        /** @var User $user */
        $user = Auth::user();
        $bills = $user->bills()->select([
            DB::raw("DATE_FORMAT(dead_line, '%m') as month"),
            DB::raw("SUM(price) as price"),
        ])
            ->groupBy('month')
            ->orderBy('month')->get();

        $expenditures = $user->expenditures()->select([
            DB::raw("DATE_FORMAT(purchase_date, '%m') as month"),
            DB::raw("SUM(price) as price"),
        ])
            ->groupBy('month')
            ->orderBy('month')->get();

        $prices = array_fill(0,12,0);

        foreach ($bills as $bill) {
            $key = intval($bill['month']) - 1;
            $prices[$key] += $bill['price'];
        }

        foreach ($expenditures as $expenditure) {
            $key = intval($expenditure['month']) - 1;
            $prices[$key] += $expenditure['price'];
        }

        $data = [
            'labels' =>  [
                'Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember',
                'Október', 'November', 'December',
            ],
            'datasets' => [
                ['label' => 'Összeg',
                    'backgroundColor' => [
                        'rgba(16, 196, 0, 0.3)',
                        'rgba(245, 57, 0, 0.3)',
                        'rgba(0, 127, 245, 0.3)',
                        'rgba(159, 0, 245, 0.3)',
                        'rgba(237, 226, 5, 0.3)',
                        'rgba(240, 157, 5, 0.3)',
                        'rgba(252, 3, 207, 0.3)',
                        'rgba(64, 226, 237, 0.3)',
                        'rgba(92, 74, 56, 0.3)',
                        'rgba(168, 84, 0, 0.3)',
                        'rgba(56, 50, 45, 0.3)',
                        'rgba(209, 0, 80, 0.3)'
                    ],
                    'data' => $prices]
            ]
        ];

        return response()->json($data);
    }
}

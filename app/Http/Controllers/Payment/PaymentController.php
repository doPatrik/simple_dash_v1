<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\BillService;
use App\Models\NewBill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $providers = $user->providers()->get()->pluck('name', 'id_provider');

        return view('Payment.payment', compact('providers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = NewBill::findOrFail($id);
        $bill->delete();

        return response()->json('ok');
    }

    public function getBills()
    {
        /** @var User $user */
        $user = Auth::user();
        $bills = $user->bills()->with('provider')
            ->when(request('search', '') != '', function ($query) {
                $query->whereHas('provider', function ($q) {
                   $q->where('label', 'LIKE', '%' . request('search') . '%');
                });
            })
            ->when(request('provider', '') != '', function ($query) {
                $query->where('id_provider', request('provider'));
            })
            ->select([
            'id_bill',
            'id_user',
            'price',
            'dead_line',
            'id_provider',
            'is_paid',
            'start_date',
            'end_date',
            DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d') as paid_date"),
            DB::raw("DATE_FORMAT(dead_line, '%Y-%m') as month")
        ])
            ->where('is_paid', false)
            ->orderBy('dead_line')->get();

        $data = (new BillService())->getBillsByMonth($bills);
        return response()->json(['data' => $data], 200);
    }

    public function payBills(Request $request)
    {
        foreach ($request->all() as $bill) {
            NewBill::query()->find($bill['id_bill'])->update(['is_paid' => true]);
        }

        return response()->json('OK', 200);
    }
}

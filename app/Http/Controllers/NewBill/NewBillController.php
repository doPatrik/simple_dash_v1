<?php

namespace App\Http\Controllers\NewBill;

use App\Http\Controllers\Controller;
use App\Models\NewBill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NewBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $providersData = Auth::user()->providers()->with('providerAddress')->with('userAddress')->get();
        $providerForSelect = Auth::user()->providers()->select('name', 'id_provider', 'label')->get();
        return view('NewBill.new_bill')
            ->with('providers', $providersData)
            ->with('providerSelect', $providerForSelect);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'price' => ['required', 'int'],
            'dead_line' => ['required', 'date'],
            'id_provider' => ['required', 'int']
        ]);

        $bill = new NewBill();
        $bill->start_date = $request->input('start_date');
        $bill->end_date = $request->input('end_date');
        $bill->price = $request->input('price');
        $bill->dead_line = $request->input('dead_line');
        $bill->is_paid = false;
        $bill->id_provider = $request->input('id_provider');
        $bill->id_user = Auth::user()->id;

        $bill->save();

        return response()->json('OK');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'price' => ['required', 'int'],
            'dead_line' => ['required', 'date'],
            'is_paid' => ['required', 'boolean'],
            'id_provider' => ['required', 'int']
        ]);

        /** @var NewBill $bill */
        $bill = NewBill::query()->findOrFail($id);

        $bill->start_date = $request->input('start_date');
        $bill->end_date = $request->input('end_date');
        $bill->price = $request->input('price');
        $bill->dead_line = $request->input('dead_line');
        $bill->is_paid = $request->input('is_paid', false);
        $bill->id_provider = $request->input('id_provider');

        $bill->save();
        return response()->json('OK');
    }
}

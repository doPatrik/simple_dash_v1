<?php

namespace App\Http\Controllers\Expenditure;

use App\Http\Controllers\Controller;
use App\Http\Services\ExpenditureService;
use App\Models\Expenditure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenditureController extends Controller
{
    public function index()
    {
        return view('Expenditure/expenditure');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => ['required', 'string'],
            'price' => ['required', 'int'],
            'id_type' => ['required', 'int', 'exists:expenditure_types,id_type'],
            'purchase_date' => ['required', 'date_format:Y-m-d'],
        ]);

        Expenditure::create([
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'id_type' => $request->input('id_type'),
            'purchase_date' => $request->input('purchase_date'),
            'id_user' => auth()->id(),
        ]);

        return response()->json('ok');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => ['required', 'string'],
            'price' => ['required', 'int'],
            'purchase_date' => ['required', 'date_format:Y-m-d'],
            'id_type' => ['required', 'int', 'exists:expenditure_types,id_type'],
        ]);

        $expenditures = Expenditure::query()->findOrFail($id);

        $expenditures->description = $request->input('description');
        $expenditures->price = $request->input('price');
        $expenditures->id_type = $request->input('id_type');
        $expenditures->purchase_date = $request->input('purchase_date');

        $expenditures->save();

        return response()->json('ok');
    }

    public function destroy($id)
    {
        Expenditure::query()->findOrFail($id)->delete();

        return response()->json('ok');
    }

    public function loadData()
    {
        /** @var User $user */
        $user = auth()->user();

        $expenditures = $user->expenditures()->with('type')
            ->when(request('search', '') != '', function ($query) {
                $query->where('description', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('type', '') != '', function ($query) {
                $query->where('id_type', request('type'));
            })
            ->select([
            'id_expenditure',
            'id_type',
            'description',
            'price',
            DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month"),
            DB::raw("DATE_FORMAT(purchase_date, '%Y-%m-%d') as purchase_date"),
        ])->orderBy('purchase_date')->get();

        $data = (new ExpenditureService())->getExpenditureByMonth($expenditures);

        return response()->json($data);
    }

    public function getTypes()
    {
        /** @var User $user */
        $user = auth()->user();

        $types = $user->expenditureTypes()->pluck('name', 'id_type');

        return response()->json($types);
    }
}

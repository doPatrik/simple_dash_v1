<?php

namespace App\Http\Controllers\Expenditure;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenditureTypeResource;
use App\Models\ExpenditureType;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenditureTypeController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'color_code' => ['required', 'string'],
        ]);

        ExpenditureType::create([
            'name' => $request->input('name'),
            'color_code' => $request->input('color_code'),
            'id_user' => auth()->id(),
        ]);

        return response()->json('ok');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'color_code' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = auth()->user();
        $type = $user->expenditureTypes()->findOrFail($id);
        $type->name = $request->input('name');
        $type->color_code = $request->input('color_code');
        $type->save();

        return response()->json('OK');
    }

    public function destroy($id)
    {
        /** @var ExpenditureType $type */
        $type = ExpenditureType::query()->findOrFail($id);

        if ($type->expenditures()->count() > 0) {
            return response()->json(
              [
                  ['A törlni kívánt típushoz tartoznak kiadások!'],
              ],
              400
            );
        }

        $type->delete();

        return response()->json('OK');
    }

    public function loadData()
    {
        /** @var User $user */
        $user = auth()->user();

        $types = $user->expenditureTypes()
            ->when(request('search', '') != '', function ($query) {
                $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('type', '') != '', function ($query) {
                $query->where('id_type', request('type'));
            })
            ->get();

        return ExpenditureTypeResource::collection($types);
    }
}

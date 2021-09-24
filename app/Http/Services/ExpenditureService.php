<?php


namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;

class ExpenditureService
{
    public function getExpenditureByMonth(Collection $collection)
    {
        $expenditures = $collection;
        $data = [];

        $expenditures->each(function ($item) use(&$data) {

            $data[$item->month][] = [
                'id_expenditure' => $item->id_expenditure,
                'id_type' => $item->id_type,
                'description' => $item->description,
                'price' => $item->price,
                'type_name' => $item->type->name,
                'purchase_date' => $item->purchase_date,
                'color_code' => $item->type->color_code,
            ];
        });

        return $data;
    }
}

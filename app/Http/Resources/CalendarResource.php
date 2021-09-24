<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_bill,
            'title' => $this->provider->label . ' számla',
            'end' => $this->dead_line,
            'start' => $this->dead_line,
            'color' => $this->provider->color_code,
        ];
    }
}

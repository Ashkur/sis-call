<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $carbon = new Carbon($this->created_at);
        $carbon->setlocale('pt_BR');

        return [
            'id' => $this->id,
            'ocurrence' => $this->ocurrence,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $carbon->format('jS \o\f F, Y G:i'),
            'employee' => $this->employee,
            'department' => $this->employee->department
        ];
    }
}

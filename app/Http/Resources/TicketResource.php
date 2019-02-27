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
        $carbon = new Carbon;

        return [
            'id' => $this->id,
            'ocurrence' => $this->ocurrence,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $carbon->toDayDateTimeString($this->created_at),
            'user' => $this->user
        ];
    }
}

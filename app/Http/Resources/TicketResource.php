<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'ocurrence' => $this->ocurrence,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'user' => $this->user
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicianResource extends JsonResource
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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'department' => $this->department,
            'created_at' => $carbon->format('jS \o\f F, Y G:i')
        ];
    }
}

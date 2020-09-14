<?php

namespace App\Http\Resources\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolFacility extends JsonResource
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
            "type"=>$this->type,
            "number"=>$this->number,
            "description"=>$this->description
        ];
    }
}

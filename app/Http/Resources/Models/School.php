<?php

namespace App\Http\Resources\Models;

use App\Http\Resources\Models\SchoolStage as schoolStageResource;
use App\Http\Resources\Models\SchoolCertificate as schoolCertificateResource;
use App\Http\Resources\Models\SchoolImage as schoolImageResource;
use App\Http\Resources\Models\SchoolFacility as schoolFacilityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class School extends JsonResource
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
        "id"=>$this->id,
        "name"=>$this->name,
        "stages"=>  schoolStageResource::collection($this->stages),
        "certificates"=> schoolCertificateResource::collection($this->certificates),
        "gender"=>$this->gender,
        "main language"=>$this->language,
        "addresss"=>$this->address,
        "phone number"=>$this->phone_number,
        "Annual fees"=>$this->fees,
        "description"=>$this->description,
        "estiblashing year"=>$this->establishing_year,
        "gallery"=>schoolImageResource::collection($this->images),
        "facilities"=>schoolFacilityResource::collection($this->facilities),
        "external_urls"=>$this->when($this->external_url != NULL,$this->external_url),
        ];
    }
}

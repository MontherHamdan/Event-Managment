<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // here will customize the json responce
        // so we can rturn just 3 column for example from the table instead of get all columns
        // you can use custome format to return a column like custome the data
        // you can add relation like here will get data from user table (the owner of the specefic event)
        // whenloaded() function it used to load the data jiust when we have request for it 
        // like when request user data found user will load if not the other data will return but user data will not return
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user' => new UserResource($this->whenLoaded('user')),
            'attendees' =>  AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
}

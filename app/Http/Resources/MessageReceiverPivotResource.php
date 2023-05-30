<?php

namespace App\Http\Resources;

use App\Helpers\PardisanHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageReceiverPivotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message_id' => $this->message_id,
            'message' => new MessageResource($this->whenLoaded('message')),
            'receiver_type' => PardisanHelper::getUserTypeByUserClass($this->receiver_type),
            'receiver_id' => $this->receiver_id,
            'is_seen' => $this->is_seen
        ];
    }
}

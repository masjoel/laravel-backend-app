<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'seller_id' => $this->seller_id,
        //     'number' => $this->number,
        //     'total_price' => $this->total_price,
        //     'payment_status' => $this->payment_status,
        //     'user_id' => new UserResource($this->whenLoaded('user')),
        // ];
    }
}

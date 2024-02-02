<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment'   => $this->comment,
            'ip'        => $this->ip_address,
            'author'    => new UserResource($this->user),
            'post'      => PostResource::make($this->post)->withoutComments()->withoutAuthor()
        ];
    }
}

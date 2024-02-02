<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    protected $withoutComments;
    protected $withoutAuthor;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'slug'  => $this->slug,
            'content'   => $this->content,
            'status'    => $this->status,
            'allow_comments' => $this->allow_comments,
            'creation_date' => $this->created_at,
            'modified_on'   => $this->updated_at,
            'comments'  => $this->when(!$this->withoutComments, $this->comment),
            'author'    => $this->when(optional($request->user())->isAdmin() && !$this->withoutAuthor, UserResource::make($this->user)),
        ];
    }

    // Method to exclude comments
    public function withoutComments()
    {
        $this->withoutComments = true;
        return $this;
    }

    // Method to exclude comments
    public function withoutAuthor()
    {
        $this->withoutAuthor = true;
        return $this;
    }
}

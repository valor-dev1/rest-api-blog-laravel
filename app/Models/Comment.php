<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 * @package App\Models
 *
 * @property int $id
 * @property string $comment
 * @property string $ip_address
 * @property int $user_id
 * @property int $post_id
 * @property string $created_at
 * @property string $updated_at
 *
 * Model for managing comments.
 */
class Comment extends Model
{
    use HasFactory;

    const SPAM = 'spam';
    const PENDING = 'pending';
    const APPROVE = 'approve';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['comment', 'ip_address'];

    /**
     * Boot method to hook into the model's lifecycle events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Comment $comment) {
            // Set the user ID and IP address when creating a comment
            $comment->user_id = auth()->id();
            $comment->ip_address = request()->ip();
        });

        static::updating(function (Comment $comment) {
            // Update the IP address when updating a comment
            $comment->ip_address = request()->ip();
        });
    }

    /**
     * Get the user that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

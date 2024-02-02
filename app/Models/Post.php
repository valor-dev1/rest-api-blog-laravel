<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Post
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property string $status
 * @property bool $allow_comments
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * Model for managing posts.
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 
        'content', 
        'slug', 
        'status', 
        'allow_comments'
    ];

    /**
     * @var string DRAFT
     */
    const DRAFT = 'draft';

    /**
     * @var string PENDING
     * @type Constant
     */
    const PENDING = 'pending';

    /**
     * @var string PUBLISH
     * @type Constant
     */
    const PUBLISH = 'publish';

    /**
     * Boot method to hook into the model's lifecycle events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Post $post) {
            // Set the slug from the title if not provided
            $post->slug = $post->slug ?: Str::slug($post->title);
        });

        static::updating(function (Post $post) {
            // Set the slug from the title if not provided
            $post->slug = $post->slug ?: Str::slug($post->title);
        });
    }

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Scope a query to only include published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', self::PUBLISH);
    }
}

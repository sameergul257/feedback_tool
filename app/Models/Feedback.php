<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'feedback_category_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function category()
    {
        return $this->belongsTo(FeedbackCategory::class, 'feedback_category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }

    public function voters()
    {
        return $this->belongsToMany(User::class, 'feedback_votes', 'feedback_id', 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(FeedbackVote::class);
    }

    public function hasUserVoted(User $user)
    {
        return $this->voters->contains($user);
    }
}

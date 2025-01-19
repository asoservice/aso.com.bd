<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'sort_order', 'status', 'helpful_votes', 'not_helpful_votes', 'created_by', 'category_id'];
}

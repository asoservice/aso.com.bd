<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFAQ extends Model
{
    use HasFactory;

    protected $table = 'service_faqs';

    protected $fillable = [
        'answer',
        'question',
        'service_id',
    ];

    protected $casts = [
        'service_id' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

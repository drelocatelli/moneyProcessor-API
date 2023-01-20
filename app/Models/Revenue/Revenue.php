<?php

namespace App\Models\Revenue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Revenue extends Model
{
    use HasFactory;

    protected $table = 'revenues';

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'title',
        'total'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

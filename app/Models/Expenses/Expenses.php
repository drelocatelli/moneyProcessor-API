<?php

namespace App\Models\Expenses;

use Database\Factories\ExpensesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Expenses extends Model
{
    use HasFactory;

    protected $table = 'expenses';

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

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): ExpensesFactory
    {
        return ExpensesFactory::new();
    }
}

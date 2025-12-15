<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class policies_category extends Model
{

    protected $table = 'policies_category';
    protected $fillable = [
        'name',
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::guard('admin')->check()) {
                $model->created_by = Auth::guard('admin')->id();
                $model->updated_by = Auth::guard('admin')->id();
            }
        });

        static::updating(function ($model) {
            if (Auth::guard('admin')->check()) {
                $model->updated_by = Auth::guard('admin')->id();
            }
        });
    }
}

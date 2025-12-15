<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\policies_category as Category;
use Illuminate\Support\Facades\Storage;

class policies extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'file_path',
        'description',
        'is_published',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }
}

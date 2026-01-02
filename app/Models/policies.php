<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\policies_category;
use Illuminate\Support\Facades\Storage;
use App\Models\AssignPolicyToUser;

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
        return $this->belongsTo(policies_category::class, 'category_id');
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    public function assignedUsers()
    {
        return $this->hasMany(AssignPolicyToUser::class, 'policy_id');
    }
}

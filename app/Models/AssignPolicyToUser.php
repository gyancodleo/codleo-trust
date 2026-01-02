<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\policies;
use App\Models\ClientUser;

class AssignPolicyToUser extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'assign_policies_to_user';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'policy_id',
        'client_user_id',
        'created_by',
        'updated_by',
    ];

    public function policy()
    {
        return $this->belongsTo(policies::class, 'policy_id');
    }
    public function clientId()
    {
        return $this->belongsTo(ClientUser::class, 'client_user_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}

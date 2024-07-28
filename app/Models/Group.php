<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use HasFactory , Notifiable;

    protected $table = 'tb_group';

    protected $primaryKey = 'pk_group';

    public $timestamps = false;

    protected $fillable = [
        'st_name',
        'fk_user_admin',
        'st_description',
        'bl_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'grupo_user', 'grupo_id', 'user_id')->withPivot('bl_accepted','int_request_type')->withTimestamps();
    }

    public function adminUse()
    {
        return $this->belongsTo(User::class, 'fk_user_admin', 'id');
    }
}

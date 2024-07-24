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

    protected $fillable = [
        'st_name',
        'fk_admin',
        'st_description',
        'bl_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_grupo_user', 'grupo_id', 'user_id');
    }

    public function adminUse()
    {
        return $this->belongsTo(User::class, 'fk_admin', 'id');
    }
}

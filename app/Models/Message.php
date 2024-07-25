<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'tb_message';

    public $timestamps = true;

    protected $primaryKey = 'pk_message';

    protected $fillable = [
        'fk_group',
        'st_message',
        'url_file_audio',
        'fk_user_send_message'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class,'fk_grupo','pk_grupo');
    }

    public function userSendMessage()
    {
        return $this->belongsTo(User::class,'fk_user_send_message','id');
    }
}

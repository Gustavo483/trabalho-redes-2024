<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Notifications\sendMessageNotification;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function dashboard()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $grupo = $user->groups;

        return view('dashboard', ['groups' => $grupo]);
    }

    public function show(Group $group)
    {

        return view('group.showGroup', ['idGrupo' => $group->pk_group]);
    }

    public function sendMessege(Group $group)
    {
        $ms = 'teste ed mensagem';
        $group->notify(new SendMessageNotification($ms, $group));
    }
}

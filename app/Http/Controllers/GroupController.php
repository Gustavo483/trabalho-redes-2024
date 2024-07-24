<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
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
        return view('group.showGroup', ['idGrupo' => $group]);
    }

    public function sendMessege(Group $group, MessageRequest $request)
    {
        $group->notify(new SendMessageNotification($request->st_message, $group, Auth::user()->id));

        return redirect()->back()->with('success', 'Message sent successfully');

    }
}

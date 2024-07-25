<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Group;
use App\Models\User;
use App\Notifications\sendMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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

    public function sendMessege(Group $group, Request $request)
    {
        $file = $request->file('file') ?? null;
        $message = $request->st_message ?? null;

        $fileUrl = null;
        $fileName = null;

        if ($file) {
            $filePath = $file->store('files', 'public');
            $fileUrl = Storage::url($filePath);
            $fileName = $file->getClientOriginalName();
        }

        $arrayMessage = [
            'st_message' => $message,
            'url_file_audio' => $fileUrl,
            'name_file' => $fileName
        ];

        $group->notify(new SendMessageNotification($arrayMessage, $group, Auth::user()->id));
    }
}

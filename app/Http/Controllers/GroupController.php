<?php

namespace App\Http\Controllers;

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

        $platformUsers = User::all();

        $grupo = $user->groups;

        $activeGroups = $grupo->filter(function ($group) {
            return $group->pivot->bl_accepted;
        });

        $invitations = $grupo->filter(function ($group) {
            return !$group->pivot->bl_accepted;
        });

        return view('dashboard', [
            'groups' => $activeGroups,
            'user' => $user,
            'platformUsers' => $platformUsers,
            'invitations' => $invitations
        ]);
    }

    public function show(Group $group)
    {
        $user = User::where('id', Auth::user()->id)->first();

        if ($user->groups->contains($group->pk_group)) {
            return view('group.showGroup', ['idGrupo' => $group]);
        }

        return redirect()->route('dashboard')->with('error', 'You do not have access to the group');
    }

    public function sendMessege(Group $group, Request $request)
    {
        $file = $request->file('file') ?? null;
        $message = $request->st_message ?? null;

        $fileUrl = null;
        $fileName = null;
        $fileType = null;

        if ($file) {
            $filePath = $file->store('files', 'public');
            $fileUrl = Storage::url($filePath);
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
        }

        $arrayMessage = [
            'st_message' => $message,
            'url_file_audio' => $fileUrl,
            'name_file' => $fileName,
            'file_type' => $fileType,
        ];

        $group->notify(new SendMessageNotification($arrayMessage, $group, Auth::user()->id));
    }

    public function newGroup(User $user, Request $request)
    {
        $grupo = Group::create([
            'st_name' => $request->st_name,
            'st_description' => $request->st_description,
            'fk_user_admin' => $user->id,
            'bl_active' => 1,
        ]);

        $userIds = $request->user_ids;

        $userIds[] = $user->id;

        $userSyncData = array_fill_keys($userIds, ['bl_accepted' => false]);

        $userSyncData[$user->id]['bl_accepted'] = true;

        $grupo->users()->sync($userSyncData);

        return redirect()->back()->with('success', 'Group created and invitation sent to selected users.');
    }

    public function rejectInvitation(Group $group)
    {
        $user = User::where('id', Auth::user()->id)->first();

        $user->groups()->detach($group->pk_group);

        return redirect()->back()->with('success', 'invitation successfully rejected');
    }

    public function acceptInvite(Group $group)
    {
        $user = User::where('id', Auth::user()->id)->first();

        $user->groups()->updateExistingPivot($group->pk_group, ['bl_accepted' => true]);

        return redirect()->route('show', ['group' => $group]);
    }

}

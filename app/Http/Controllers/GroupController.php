<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
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
            return ! $group->pivot->bl_accepted && $group->pivot->int_request_type === 1;
        });

        $groupsAdmin = Group::where('fk_user_admin', Auth::user()->id)->get();

        $userIdsWithRequestType2 = [];

        foreach ($groupsAdmin as $group) {

            $usersWithRequestType2 = $group->users()->wherePivot('int_request_type', 2)->wherePivot('bl_accepted',
                0)->get();

            if (count($usersWithRequestType2)) {

                $nameUser = $usersWithRequestType2->pluck('name');
                $idUser = $usersWithRequestType2->pluck('id');

                $userIdsWithRequestType2[] = [$nameUser[0], $idUser[0], $group->st_name, $group->pk_group];
            }
        }

        return view('dashboard', [
            'groups' => $activeGroups,
            'user' => $user,
            'platformUsers' => $platformUsers,
            'invitations' => $invitations,
            'groupAccessRequest' => $userIdsWithRequestType2,
        ]);
    }

    public function showGroups()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $idGroups = [];

        foreach ($user->groups as $group) {
            $idGroups[] = $group->pk_group;
        }

        return view('group.showGroups',
            ['idGroups' => $idGroups, 'user' => $user, 'groups' => Group::all(), 'users' => User::all()]);
    }

    public function show(Group $group)
    {
        $usersGroup = [];

        foreach ($group->users as $usersBelongsGroup) {
            array_push($usersGroup, $usersBelongsGroup->id);
        }

        $user = User::where('id', Auth::user()->id)->first();

        $platformUsers = User::whereNotIn('id', $usersGroup)->get();

        if ($user->groups->contains($group->pk_group)) {

            $messages = Message::where('fk_group', $group->pk_group)->get();

            return view('group.showGroup',
                ['idGrupo' => $group, 'user' => $user, 'messages' => $messages, 'platformUsers' => $platformUsers]);
        }

        return redirect()->route('dashboard')->with('error', 'You do not have access to the group');
    }

    public function sendMessage(Group $group, Request $request)
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
        $nameGroup = Group::where('st_name', $request->st_name)->first();

        if ($nameGroup) {
            return redirect()->back()->with('error', 'The group name is already in use.');
        }

        $grupo = Group::create([
            'st_name' => $resquestName ?? $request->st_name,
            'st_description' => $request->st_description,
            'fk_user_admin' => $user->id,
            'bl_active' => 1,
        ]);

        $userIds = $request->user_ids;
        $userIds[] = strval($user->id);
        $userSyncData = array_fill_keys($userIds, ['bl_accepted' => false]);
        $userSyncData[$user->id]['bl_accepted'] = true;
        $grupo->users()->sync($userSyncData);

        return redirect()->back()->with('success', 'Group created and invitation sent to selected users.');
    }

    public function newInvitation(User $user, Group $group, Request $request)
    {
        if ($group->fk_user_admin === $user->id) {
            $userSyncData = array_fill_keys($request->user_ids, ['bl_accepted' => false]);
            $group->users()->sync($userSyncData);

            return redirect()->back()->with('success', 'Invitation sent to selected users.');
        }

        return redirect()->back()->with('error', 'You do not have access');
    }

    public function outGroup(User $user, Group $group, Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->groups()->detach($group->pk_group);

        return redirect()->route('dashboard')->with('success', 'You left the group');
    }

    public function sendRequesToGroup(Group $group)
    {
        $userIds[] = Auth::user()->id;
        $userSyncData = array_fill_keys($userIds, ['bl_accepted' => false, 'int_request_type' => 2]);
        $group->users()->sync($userSyncData);

        return redirect()->back()->with('success', 'Access request sent successfully');
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

    public function rejectInvitationAdmin(Group $group, User $user)
    {
        $user->groups()->detach($group->pk_group);

        return redirect()->back()->with('success', 'invitation successfully rejected');
    }

    public function acceptInviteAdmin(Group $group, User $user)
    {
        $user->groups()->updateExistingPivot($group->pk_group, ['bl_accepted' => true]);

        return redirect()->back()->with('success', 'Invitation successfully accepted');
    }
}

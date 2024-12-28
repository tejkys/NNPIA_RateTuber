<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelCategories;
use App\Models\Comment;
use App\Models\LogChannel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        return view('admin', [
        ]);
    }
    public function users(Request $request)
    {
        $users = User::where('name', 'LIKE', "%".$request->search."%")->orderBy('created_at')->paginate(10);
        $roles = Role::get();
        return view('admin.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
    public function usersUpdate(Request $request)
    {
        if($request->has('delete')) {
            Comment::where('user_id', decrypt($request->id))->delete();
            User::whereId(decrypt($request->id))->delete();
            return redirect()->back()->withSuccess(['Deleted']);

        } else {
            User::whereId(decrypt($request->id))->update($request->only(['role_id', 'active']));
            return redirect()->back()->withSuccess(['Updated']);
        }
    }
    public function channels(Request $request)
    {
        $channels = Channel::where('name', 'LIKE', "%".$request->search."%")->orderBy('created_at')->paginate(10);
        return view('admin.channels', [
            'channels' => $channels,
        ]);
    }
    public function channelsUpdate(Request $request)
    {
        if($request->has('delete')) {
            LogChannel::where('channel_id', decrypt($request->id))->delete();
            ChannelCategories::where('channel_id', decrypt($request->id))->delete();
            Comment::where('channel_id', decrypt($request->id))->delete();
            Channel::whereId(decrypt($request->id))->delete();
            return redirect()->back()->withSuccess(['Deleted']);

        } else {
            Channel::whereId(decrypt($request->id))->update($request->only(['active']));
            return redirect()->back()->withSuccess(['Updated']);
        }
    }
    public function channelsSearch(Request $request)
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/search',
            [
                'key' => env('YOUTUBE_KEY'),
                'part' => 'snippet',
                'type' => 'channel',
                'maxResults' => '1',
                'q' => $request->name,
            ]);
        if ($response->successful()) {
             return $response->body();
        }
        return response()->json(['status' => 'Api request failed']);
    }
    public function channelsCreate(Request $request)
    {
        createOrUpdateChannel($request, null, true);
        return redirect()->back()->withSuccess(['Created']);
    }
}

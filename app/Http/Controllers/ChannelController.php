<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelCategories;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Response;
use Carbon\Carbon;

class ChannelController extends Controller
{

    public function get(Request $request)
    {
        $channel = Channel::where('name', $request->channel)->with('categories', 'comments')->first();
        if (!$channel)
            return redirect()->route('home.index')->withErrors(['The Channel Could Not Be Found']);

        createOrUpdateChannel($request, $channel);

        $comments = Comment::where('channel_id', $channel->id)->with('user')->orderByDesc('updated_at')->paginate(5);
        $score = round($channel->comments->avg('rating') / 5 * 100, 2);
        $ratings = Comment::select(
            DB::raw("SUM(rating_violence) as violence"),
            DB::raw("SUM(rating_fear) as fear"),
            DB::raw("SUM(rating_sex) as sex"),
            DB::raw("SUM(rating_coarse_language) as coarse_language"),
            DB::raw("SUM(rating_discrimination) as discrimination"),
            DB::raw("SUM(rating_drugs) as drugs"),
        )->where('channel_id', $channel->id)->get();
        $age = Comment::select('rating_age', DB::raw("COUNT(*) as count"))->where('channel_id', $channel->id)->whereNotNull('rating_age')->groupBy('rating_age')->orderByDesc('count')->first();
        $user_comment = null;
        if (Auth::check())
            $user_comment = Comment::where('channel_id', $channel->id)->where('user_id', Auth::id())->first();


        return view('channel', [
            'channel' => $channel,
            'comments' => $comments,
            'user_comment' => $user_comment,
            'score' => $score,
            'ratings' => $ratings[0],
            'age' => $age,
        ]);
    }

    public function thumbnail(Request $request)
    {
        $path = storage_path('app/public/thumbnails/' . decrypt($request->channel));
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function comment(Request $request)
    {
        $request->validate([
            'comment' => 'required|min:3|max:255',
            'rate' => 'required|numeric|between:1,5',
            'age' => 'in:6,12,16,al',
        ]);
        $channel = Channel::where('name', $request->channel)->first();
        if ($channel && Auth::check()) {
            Comment::updateOrCreate([
                'user_id' => Auth::id(),
                'channel_id' => $channel->id,
            ], [
                'text' => $request->comment,
                'rating' => $request->rate,
                'rating_age' => $request->age,
                'rating_violence' => $request->rating_violence ? 1 : 0,
                'rating_fear' => $request->rating_fear ? 1 : 0,
                'rating_sex' => $request->rating_sex ? 1 : 0,
                'rating_coarse_language' => $request->rating_coarse_language ? 1 : 0,
                'rating_discrimination' => $request->rating_discrimination ? 1 : 0,
                'rating_drugs' => $request->rating_drugs ? 1 : 0,
            ]);
        }
        return redirect()->back()->withSuccess(['Thank you for your comment']);
    }

    public function commentDelete(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role->name == "admin") {
                $user_comment = Comment::where('id', decrypt($request->comment))->delete();
            } else {
                $user_comment = Comment::where('id', decrypt($request->comment))->where('user_id', Auth::id())->delete();
            }
        }
        return redirect()->back()->withSuccess(['Your comment is gone']);
    }
}

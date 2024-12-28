<?php

use App\Models\Channel;
use App\Models\ChannelCategories;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

function createOrUpdateChannel(Request $request, $channel = null, $force = false)
{
    if ($request->has('yt_id')) {
        $channel = Channel::firstOrCreate(['yt_id' => $request->yt_id], ['name' => 'Added channel']);
    }

    if (!$channel)
        return redirect()->route('home.index')->withErrors(['The Channel Could Not Be Found']);

    if ($force  || $channel->updated_at == null || Carbon::now()->diffInDays($channel->updated_at) > 0) {
        $response = Http::get('https://www.googleapis.com/youtube/v3/channels',
            [
                'key' => env('YOUTUBE_KEY'),
                'part' => 'snippet,statistics',
                'id' => $channel->yt_id,
            ]);
        if ($response->successful()) {
            $body = json_decode($response->body());
            $channel->subscribers = $body->items[0]->statistics->subscriberCount;
            $channel->views = $body->items[0]->statistics->viewCount;
            $channel->videos = $body->items[0]->statistics->videoCount;

            $channel->name = $body->items[0]->snippet->title;
            $channel->description = $body->items[0]->snippet->description;
            $channel->published = Carbon::parse($body->items[0]->snippet->publishedAt);
            $channel->country = $body->items[0]->snippet->country ?? "";
            copy($body->items[0]->snippet->thumbnails->medium->url, storage_path('app/public/thumbnails/' . $channel->id));
            $channel->save();
        }
        $response = Http::get('https://www.googleapis.com/youtube/v3/search',
            [
                'key' => env('YOUTUBE_KEY'),
                'part' => 'snippet,id',
                'channelId' => $channel->yt_id,
                'order' => 'date',
                'maxResults' => 10,
            ]);
        if ($response->successful()) {
            $body = json_decode($response->body());
            $videos = "";
            $amount = 0;
            foreach ($body->items as $video) {
                if($video->id->kind == "youtube#video") {
                    $videos = $videos . "," . $video->id->videoId;
                    $amount++;
                }
            }
            $videos = ltrim($videos, ',');

            $response = Http::get('https://www.googleapis.com/youtube/v3/videos',
                [
                    'key' => env('YOUTUBE_KEY'),
                    'part' => 'snippet,statistics',
                    'id' => $videos,
                ]);
            if ($response->successful()) {
                $body = json_decode($response->body());
                $viewCount = 0;
                $likeCount = 0;
                $commentCount = 0;
                $categories = array();
                foreach ($body->items as $video) {
                    if($video->kind == "youtube#video") {
                        $viewCount += $video->statistics->viewCount;
                        $likeCount += $video->statistics->likeCount;
                        $commentCount += $video->statistics->commentCount ?? 0;
                        array_push($categories, ['channel_id' => $channel->id, 'category_id' => $video->snippet->categoryId]);
                    }
                }
                $categories = array_unique($categories, SORT_REGULAR);
                ChannelCategories::where('channel_id', $channel->id)->delete();
                ChannelCategories::insert($categories);

                $channel->avg_views = $viewCount / $amount;
                $channel->avg_likes = $likeCount / $amount;
                $channel->avg_comments = $commentCount / $amount;
                $channel->save();
            }
        }
    }
    return $channel;
}

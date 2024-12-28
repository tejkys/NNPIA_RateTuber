<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Template;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    private function getPaginator(Request $request, $items)
    {
        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $page = $request->page ?? 1; // get current page from the request, first page is null
        $perPage = 10; // how many items you want to display per page?
        $offset = ($page - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    }

    public function index(Request $request)
    {
//        Mail::to('tejkys@email.cz')->send(new Template([
//            'subject' => 'Ahoj ty jeden',
//            'title' => 'Mail from ItSolutionStuff.com',
//            'body' => 'This is for testing email using smtp'
//        ]));

        $order = $request->query('sort', 'rating');
        if (!in_array($order, ['rating', 'subscribers', 'avg_views', 'avg_likes', 'avg_comments'])) {
            $order = "rating";
        }
        $channels = Channel::select('channels.*', DB::raw('AVG(rating) as rating'))
            ->where('channels.active', '1')
            ->leftJoin('comments', 'channels.id', '=', 'comments.channel_id')
            ->groupBy('channels.id')
            ->orderBy($order, 'desc')
            ->get()->toArray();
        $paginator = $this->getPaginator($request, $channels);
        return view('home', [
            'channels' => $paginator,
        ]);
    }

    public function search(Request $request)
    {
        $channel = Channel::where('name', 'like', '%' . $request->search . '%')->first();
        if ($channel) {
            return redirect()->route('channel.get', $channel->name);
        } else {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search',
                [
                    'key' => env('YOUTUBE_KEY'),
                    'part' => 'snippet',
                    'type' => 'channel',
                    'maxResults' => '1',
                    'q' => $request->search,
                ]);
            if ($response->successful()) {
                $body = json_decode($response->body());
                if ($body->items) {
                    $response = Http::get('https://www.googleapis.com/youtube/v3/channels',
                        [
                            'key' => env('YOUTUBE_KEY'),
                            'part' => 'snippet,statistics',
                            'id' => $body->items[0]->id->channelId,
                        ]);

                    if ($response->successful()) {
                        $body = json_decode($response->body());
                        if ($body->items[0]->statistics->subscriberCount > 10000) {
                            $request->merge(["yt_id"=>$body->items[0]->id]);

                            $channel = createOrUpdateChannel($request, null, true);
                            return redirect()->route('channel.get', $channel->name);
                        }
                    }
                }
            }
            return redirect()->back()->withErrors(['The Channel Could Not Be Found']);
        }

    }
}

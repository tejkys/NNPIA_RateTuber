@extends('layout')

@section('title', 'Ranking')

@section('content')
    <div style="padding: 0 10px;display: flex;
    justify-content: space-between;   align-items: center;">
        <h1>Rank</h1>
        <form>
            <select name="sort" id="sort">
                <option value="rating">Score</option>
                <option value="subscribers">Subscribers</option>
                <option value="avg_views">Avg. views</option>
                <option value="avg_likes">Avg. likes</option>
                <option value="avg_comments">Avg. comments</option>
            </select>
        </form>
        <script>
            @if(request()->sort && in_array(request()->sort, ['rating', 'subscribers', 'avg_views', 'avg_likes', 'avg_comments']))
            $('#sort option[value="{{request()->sort}}"]').attr("selected", "selected");
            @endif
        </script>
    </div>
    @forelse($channels as $channel)
        @php
            $title = match (request()->query('sort', 'rating')) {
            'rating' => "Score: " . round($channel['rating'],2),
            'subscribers' => "Subscribers: " . number_format($channel['subscribers']),
            'avg_views' => "Average views: " . number_format($channel['avg_views']),
            'avg_likes' => "Average likes: " . number_format($channel['avg_likes']),
            'avg_comments' => "Average comments: " . number_format($channel['avg_comments']),
            default => 'unknown',
            };
            $rates = match (request()->query('sort', 'rating')) {
            'rating' => "<i class='fa-solid fa-star'></i> " . round($channel['rating'],2),
            'subscribers' => "<i class='fas fa-users'></i> " . number_format($channel['subscribers']),
            'avg_views' => "<i class='far fa-eye'></i> " . number_format($channel['avg_views']),
            'avg_likes' => "<i class='fas fa-play-circle'></i> " . number_format($channel['avg_likes']),
            'avg_comments' => "<i class='fa-solid fa-comment'></i> " . number_format($channel['avg_comments']),
            default => 'unknown',
            };

        @endphp
        <a href="{{route('channel.get', [$channel['name']])}}" title="{{ $title }}">
            <div class="card-section form-inline" style="justify-content: left;">
                <div
                    style="margin: 0 12px">{{ ($channels ->currentpage()-1) * $channels ->perpage() + $loop->index + 1 }}</div>
                <img class="thumbnail" style="width: 48px !important; height: 48px !important;"
                     src="{{ route('channel.thumbnail', encrypt($channel['id'])) }}" alt="user">
                <p class="rank-item-name"> {{ $channel['name'] }}</p>
                <div class="rank-item-score">
                    {!!  $rates !!}
                </div>
            </div>
        </a>
    @empty
        <div class="card-section">
            <h6>Nothing to show</h6>
        </div>
    @endforelse
    <div>{!! $channels->links('pagination::bootstrap-4') !!} </div>
@endsection

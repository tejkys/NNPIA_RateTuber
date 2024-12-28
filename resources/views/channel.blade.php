@extends('layout')

@section('title', $channel->name)

@section('content')
    <!--<span class="pro">PRO</span>-->
    <img class="thumbnail" src="{{ route('channel.thumbnail', encrypt($channel->id)) }}"
         alt="{{ $channel->name }} Thumbnail">
    <div class="form-inline">
        @if($channel->country)
            <img alt="Contry Flag" src="https://flagcdn.com/32x24/{{ strtolower($channel->country) }}.png"
                 style="margin-right: 10px;">
        @endif
        <h1><a href="https://www.youtube.com/channel/{{ $channel->yt_id }}">{{$channel->name}}</a></h1>
    </div>
    <div class="channel-rating" title="{{ $score }}%">
        <div class="rating-slider" data-value="{{ $score }}"></div>
    </div>

    <div class="card-section">
        <ul>
            <li title="Subscribers"><i class="fas fa-users"></i> {{ number_format($channel->subscribers) }}</li>
            <li title="Views"><i class="far fa-eye"></i> {{ number_format($channel->views) }}</li>
            <li title="Uploaded videos"><i class="fas fa-play-circle"></i> {{ number_format($channel->videos) }}</li>
        </ul>
        <div class="form-inline" style="margin-top: 4px;">
            <p style="margin:0 10px 0 0;">Content: </p>
            <ul>
                @foreach($channel->categories as $category)
                    <li> {{ $category->category }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <p class="channel-description">{!! nl2br(e($channel->description)) !!} </p>

    <div class="card-section">
        <h3>Statistics</h3>
        <table class="table-statistics">
            <tr>
                <td>Average Views:</td>
                <td>{{ number_format($channel->avg_views) }}</td>
            </tr>
            <tr>
                <td>Average Likes:</td>
                <td>{{ number_format($channel->avg_likes) }}</td>
            </tr>
            <tr>
                <td>Average Comments:</td>
                <td>{{ number_format($channel->avg_comments) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="content-tags">
                    @if($age && $age->rating_age)
                    <img src="{{URL::asset('/images/kijkwijzer/'.$age->rating_age.'.png')}}"
                         alt="For {{ $age->rating_age }} age" title="Suitable from age: {{ $age->rating_age == 'al' ? "all" :  $age->rating_age }}">
                    @endif
                    @if($ratings->violence)
                        <img src="{{URL::asset('/images/kijkwijzer/violence.png')}}" alt="Violence"
                             title="Violence {{ $ratings->violence }} votes">
                    @endif
                    @if($ratings->fear)
                        <img src="{{URL::asset('/images/kijkwijzer/fear.png')}}" alt="Fear"
                             title="Fear {{ $ratings->fear }} votes">
                    @endif
                    @if($ratings->sex)
                        <img src="{{URL::asset('/images/kijkwijzer/sex.png')}}" alt="Sex"
                             title="Sex {{ $ratings->sex }} votes">
                    @endif
                    @if($ratings->coarse_language)
                        <img src="{{URL::asset('/images/kijkwijzer/coarse_language.png')}}" alt="Coarse Language"
                             title="Coarse Language {{ $ratings->coarse_language }} votes">
                    @endif
                    @if($ratings->discrimination)
                        <img src="{{URL::asset('/images/kijkwijzer/discrimination.png')}}" alt="Discrimination"
                             title="Discrimination {{ $ratings->discrimination }} votes">
                    @endif
                    @if($ratings->drugs)
                        <img src="{{URL::asset('/images/kijkwijzer/drugs.png')}}" alt="Drugs and Alcohol abuse"
                             title="Drugs and Alcohol abuse {{ $ratings->drugs }} votes">
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="card-comments">
        @if(Auth::check())
            <div class="card-section">
                <form method="post" action="{{route('channel.comment', $channel->name)}}" class="form-style">
                    <div class="comment-rating">
                        <div>
                            <h2>Rate Youtuber</h2>
                            <button style="float: right;" type="button" class="toggle-button"
                                    data-for="additional-ratings" title="Show/hide additional ratings"><i
                                    class="fas fa-chevron-circle-down"></i>
                            </button>
                        </div>
                        <div>
                            <p style="margin: 5px;">Total score:</p>
                            <label class="rating-option comment-rating-1" title="Terrible">One
                                <input type="radio" value="1" name="rate">
                                <span class="checkmark"></span>
                            </label>
                            <label class="rating-option comment-rating-2" title="Bad">Two
                                <input type="radio" value="2" name="rate">
                                <span class="checkmark"></span>
                            </label>
                            <label class="rating-option comment-rating-3" title="Moderate">Three
                                <input type="radio" value="3" name="rate">
                                <span class="checkmark"></span>
                            </label>
                            <label class="rating-option comment-rating-4" title="Good">Four
                                <input type="radio" value="4" name="rate">
                                <span class="checkmark"></span>
                            </label>
                            <label class="rating-option comment-rating-5" title="Excellent">Five
                                <input type="radio" value="5" name="rate">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div id="additional-ratings" class="hidden">
                            <div>
                                <p style="margin: 5px;">Suitable for age:</p>
                                <label class="rating-option comment-rating-1" title="Terrible">16+
                                    <input type="radio" value="16" name="age">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="rating-option comment-rating-2" title="Bad">12+
                                    <input type="radio" value="12" name="age">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="rating-option comment-rating-3" title="Moderate">6+
                                    <input type="radio" value="6" name="age">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="rating-option comment-rating-5" title="Excellent">All
                                    <input type="radio" value="al" name="age">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div style="margin-bottom: 5px;">
                                <p style="margin: 5px;">Content Categories:</p>
                                <label for="checkbox-categories-1">Violence
                                    <input type="checkbox" name="rating_violence" id="checkbox-categories-1">
                                </label>
                                <label for="checkbox-categories-2">Fear
                                    <input type="checkbox" name="rating_fear" id="checkbox-categories-2">
                                </label>
                                <label for="checkbox-categories-3">Sex
                                    <input type="checkbox" name="rating_sex" id="checkbox-categories-3">
                                </label>
                                <label for="checkbox-categories-4">Coarse language
                                    <input type="checkbox" name="rating_coarse_language" id="checkbox-categories-4">
                                </label>
                                <label for="checkbox-categories-5">Discrimination
                                    <input type="checkbox" name="rating_discrimination" id="checkbox-categories-5">
                                </label>
                                <label for="checkbox-categories-6">Drugs/alcohol abuse
                                    <input type="checkbox" name="rating_drugs" id="checkbox-categories-6">
                                </label>

                            </div>
                        </div>

                        @if($user_comment)
                            <script>
                                $('input:radio[name="rate"]').filter('[value="{{$user_comment->rating}}"]').attr('checked', true);
                                $('input:radio[name="age"]').filter('[value="{{$user_comment->rating_age}}"]').attr('checked', true);
                                @foreach($user_comment->getAttributes() as $key=>$val)
                                @if(str_contains($key, 'rating_') && $val == 1)
                                $('input:checkbox[name="{{ $key }}"]').attr('checked', true);
                                @endif
                                @endforeach
                            </script>
                        @endif
                    </div>
                    @csrf
                    <textarea name="comment" placeholder="share what you think about this youtuber"
                              minlength="3" maxlength="255"
                              required>{{ $user_comment ? $user_comment->text : ""}}</textarea>
                    <input type="submit" value="Submit comment">@if($user_comment)<a class="button"
                                                                                     style="font-size: 0.75rem;"
                                                                                     href="{{ route('channel.commentDelete', encrypt($user_comment->id)) }}">Delete</a>@endif
                </form>
            </div>
        @endif
        <h2>Comments</h2>

        @forelse($comments as $comment)
            <div class="card-section">
                <div class="left-vertical" style="margin-bottom: 2px;">
                    <div class="comment-rate">[
                        <div class="comment-rating-{{ $comment->rating }}">{{ $comment->rating }}</div>
                        <div>/5</div>
                        ]
                    </div>
                    <div class="comment-author">{{ $comment->user->name }}</div>
                    @if(Auth::check() && Auth::user()->role->name == 'admin')<a class="button"
                                                                                style="font-size: 1rem; float: right;"
                                                                                href="{{ route('channel.commentDelete', encrypt($comment->id)) }}"
                                                                                title="Admin: delete"><i
                            class="far fa-trash-alt"></i></a>
                    @endif
                </div>
                <div class="comment-text">{{ $comment->text }}</div>
                <div class="comment-datetime">{{ $comment->updated_at }}</div>
            </div>
        @empty
            <div class="card-section">No comments available</div>
        @endforelse
        <div>{!! $comments->links('pagination::bootstrap-4') !!} </div>
    </div>
    {{--    <div class="buttons">--}}
    {{--        <button class="primary">--}}
    {{--            Message--}}
    {{--        </button>--}}
    {{--        <button class="primary ghost">--}}
    {{--            Following--}}
    {{--        </button>--}}
    {{--    </div>--}}
@endsection

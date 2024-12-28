@extends('layout')

@section('title', 'Admin - channels')

@section('content')
    <div id="dialog-channel" title="Create new channel">
        <p >Find channel on YouTube:</p>
        <form method="post" action="{{ route('admin.channels.create') }}" onkeydown="return event.key != 'Enter';" id="dialog-channel-form">
            @csrf
            <label for="name">Name:</label>
                <input type="text" name="name" id="name">
                <input type="submit" value="Search" id="dialog-channel-search">
        </form>
        <p id="dialog-channel-content"></p>
    </div>

    <span>
        <h2>Admin - channels</h2>

    <a href="{{ route('admin.index') }}" style="position: absolute; left: 5px; top: 5px;"><i
            class="fas fa-arrow-circle-left"></i></a>
    </span>
    <div style="margin: 10px;">

        <form>
            <input style="width: 50%" type="text" name="search" value="{{ request()->input('search', '') }}" placeholder="Channel">
            <button type="submit" id="dialog-channel-search">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
            <button title="Add channel" id="create-channel" type="button">
            <i class="fas fa-plus-square"></i>
            </button>
        </form>
    </div>
    <style>
        form > div {
            display: inline-block;
        }

        form > div:nth-of-type(1) {
            width: 50%;
            height: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    @forelse($channels as $channel)
        <div class="card-section">
            <form method="post" action="{{ route('admin.channels.update') }}">
                @csrf
                <input type="hidden" name="id" value="{{ encrypt($channel->id) }}">
                <div title="{{$channel->yt_id}}">
                    {{ $channel->name }}
                </div>
                <div>
                    <select name="active" class="select-menu">
                        <option value="1" {{ $channel->active == 1 ? 'selected' : ''}}> Active</option>
                        <option value="0" {{ $channel->active == 0 ? 'selected' : ''}}> Inactive</option>
                    </select>
                </div>
                <div>
                    <button title="Update changes"><i class="fa-solid fa-circle-check"></i></button>
                    <button name="delete" title="Delete user"><i class="fas fa-trash"></i></button>
                </div>
            </form>
        </div>
    @empty

        <div class="card-section">
            No channels
        </div>
    @endforelse
    <div>{!! $channels->links('pagination::bootstrap-4') !!} </div>
@endsection

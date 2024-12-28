@extends('layout')

@section('title', 'Admin - users')

@section('content')
    <span>
        <h2>Admin - users</h2>

    <a href="{{ route('admin.index') }}" style="position: absolute; left: 5px; top: 5px;"><i
            class="fas fa-arrow-circle-left"></i></a>
    </span>
    <div style="margin: 10px;">

        <form>
            <input style="width: 50%" type="text" name="search" value="{{ request()->input('search', '') }}" placeholder="Username">
            <button type="submit">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>
    </div>
    <style>
        form > div {
            display: inline-block;
        }

        form > div:nth-of-type(1) {
            width: 20%;
            height: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    @forelse($users as $user)
        <div class="card-section">
            <form style="display: flex;" method="post" action="{{ route('admin.users.update') }}">
                @csrf
                <input type="hidden" name="id" value="{{ encrypt($user->id) }}">
                <div title="{{$user->email}} ({{$user->ip?:'no ip'}})">
                    {{ $user->name }}
                </div>
                <div>
                    <select name="active" class="select-menu">
                        <option value="1" {{ $user->active == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{ $user->active == 0 ? 'selected' : ''}}>Inactive</option>
                    </select>
                </div>
                <div><select name="role_id" class="select-menu">
                        @foreach($roles as $role)
                            <option
                                value="{{$role->id}}" {{ $role->id == $user->role->id ? 'selected' : ''}}>{{$role->name}}</option>
                        @endforeach
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
            No users
        </div>
    @endforelse
    <div>{!! $users->links('pagination::bootstrap-4') !!} </div>
@endsection

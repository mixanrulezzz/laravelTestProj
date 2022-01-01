@extends('layouts.main-layout')
@extends('assets.main')

@section('title', 'Main')

@section('content')
    <h1>Main page</h1>

    <a href="{{ route('platform.main') }}">Админка</a>

    @if(!empty($users))
        @foreach($users as $user)
            <h3>{{$user->name}}</h3>
            <br>
        @endforeach
    @endif
@endsection

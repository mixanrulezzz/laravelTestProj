@extends('layouts.main-layout')
@extends('assets.main')

@section('title', 'Main')

@section('content')
    <h1>Main page</h1>

    @if(!empty($users))
        @foreach($users as $user)
            <h3>{{$user->name}}</h3>
            <br>
        @endforeach
    @endif
@endsection

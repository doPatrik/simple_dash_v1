@extends('layouts.app')

@section('content')
    @if(auth()->user()->isAdmin)
        <admin-component :users="{{$users}}" :menu-items="{{$menuItems}}"></admin-component>
    @endif
@endsection

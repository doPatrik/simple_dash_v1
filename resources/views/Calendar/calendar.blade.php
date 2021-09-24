@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <calendar-component :bills="{{$bills}}"></calendar-component>
    </div>
@endsection

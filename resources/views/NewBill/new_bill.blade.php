@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <new-bill-component :providerSelect="{{$providerSelect}}" :providers="{{$providers}}"></new-bill-component>
    </div>
@endsection

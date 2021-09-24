@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <overview-component :bills="{{json_encode($bills)}}" :providers="{{$providers}}"></overview-component>
    </div>
@endsection

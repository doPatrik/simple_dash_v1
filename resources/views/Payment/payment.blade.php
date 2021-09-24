@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <payment-component :providers="{{$providers}}"></payment-component>
    </div>
@endsection

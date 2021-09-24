@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <div class="card mt-5">
            @include('inc.messages')
            <div class="card-header">
                {{ __('Szolgáltatók') }}
                <a class="btn btn-info float-sm-none float-md-right m-1" href="{{route('provider.create')}}">Új szolgáltató felvétele</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Név</th>
                        <th scope="col">Azonosító</th>
                        <th scope="col">Cimke</th>
                        <th scope="col">Műveletek</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($providers) > 0)
                        @foreach($providers as $provider)
                            <tr>
                                <th scope="row">{{$provider->id_provider}}</th>
                                <td>{{$provider->name}}</td>
                                <td>{{$provider->number}}</td>
                                <td>{{$provider->label}}</td>
                                <td>
                                    <a href="{{route('provider.edit', $provider->id_provider)}}" class="btn btn-warning m-1">Szerkeszt</a>
                                    <form class="d-inline" action="{{route('provider.destroy', $provider->id_provider)}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger m-1">Töröl</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th scope="row">Nincs hozzárendelt szolgáltató</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

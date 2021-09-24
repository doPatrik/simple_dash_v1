@extends('layouts.app')

@section('content')
    <div id="dashContent" class="profile">
        <div class="col-md-12">
            @include('inc.messages')
            <div class="card">
                <div class="card-header">{{ __('Profil') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.change', Auth::user()->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Felhasználónév') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') ? old('name') : Auth::user()->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Családnév') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                                name="last_name" value="{{ old('last_name') ? old('last_name') : Auth::user()->last_name}}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('Keresztnév') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                                 name="first_name" value="{{ old('first_name') ? old('first-name') : Auth::user()->first_name}}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail cím') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') ? old('email') : Auth::user()->email}}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Módosítás') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header">
                    {{ __('Profilhoz tartozó címek') }}
                    <a class="btn btn-info float-xl-right" href="{{route('address.create')}}">Új cím felvétele</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Irsz</th>
                            <th scope="col">Város</th>
                            <th scope="col">Utca</th>
                            <th scope="col">Műveletek</th>
                          </tr>
                        </thead>
                        <tbody>
                            @if(count($addresses) > 0)
                                @foreach ($addresses as $address)
                                <tr>
                                    <th scope="row">{{$address->id_address}}</th>
                                    <td>{{$address->post_code}}</td>
                                    <td>{{$address->city}}</td>
                                    <td>{{$address->street_name}}</td>
                                    <td>
                                        <a href="{{route('address.edit', $address->id_address)}}" class="btn btn-warning m-1">Szerkeszt</a>
                                        <form class="d-inline" action="{{route('address.destroy', $address->id_address)}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger m-1">Töröl</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <th scope="row">Nincs hozzárendelt cím</th>
                            </tr>
                            @endif
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div id="dashContent">
        <div class="col-md-12">
            @include('inc.messages')
            <div class="card">
                <div class="card-header">{{ __('Lakcím szerkesztése') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('address.update', $address->id_address) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group row">
                            <label for="post_code" class="col-md-4 col-form-label text-md-right">{{ __('Irányítószám') }}</label>

                            <div class="col-md-6">
                                <input id="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror"
                                name="post_code" value="{{ old('post_code') ? old('post_code') : $address->post_code }}" required autocomplete="post_code" autofocus>

                                @error('post_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('Város') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" 
                                name="city" value="{{ old('city') ? old('city') : $address->city}}" required autocomplete="city" autofocus>

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="street_name" class="col-md-4 col-form-label text-md-right">{{ __('Utca, házszám') }}</label>

                            <div class="col-md-6">
                                <input id="street_name" type="text" class="form-control @error('street_name') is-invalid @enderror"
                                 name="street_name" value="{{ old('street_name') ? old('street_name-name') : $address->street_name}}" required autocomplete="street_name" autofocus>

                                @error('street_name')
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
        </div>
    </div>
@endsection

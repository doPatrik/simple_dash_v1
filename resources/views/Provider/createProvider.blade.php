@extends('layouts.app')

@section('content')
    <div id="dashContent" class="createProvider">
        @include('inc.messages')
        <div class="card">
            <h2>Szolgáltató hozzáadás</h2>
            <hr class="divider">
            <form method="POST" action="{{route('provider.store')}}">
                @csrf
                <div class="gridBox">
                    <div class="left-section">
                        <b>Szolgáltató adatai</b>
                    </div>

                    <div id="provider">
                        <div id="providerName">
                            <label for="name">{{__('Szolgáltató neve')}}</label></br>
                            <input type="text" name="name" id="name" value="{{old('name')}}" required autocomplete="name"></br>
                        </div>

                        <div id="providerPostCode">
                            <label for="post_code">Irányítószám</label></br>
                            <input type="text" name="post_code" id="post_code" value="{{old('post_code')}}" required autocomplete="post_code">
                        </div>
                        <div id="providerCity">
                            <label for="city">Város</label></br>
                            <input type="text" name="city" id="city" value="{{old('city')}}" required autocomplete="city">
                        </div>
                        <div id="providerStreetName">
                            <label for="street">Cím</label></br>
                            <input type="text" name="street" id="street" value="{{old('street')}}" required autocomplete="street">
                        </div>

                    </div>
                </div>
                <hr class="divider">
                <div class="gridBox">

                    <div class="left-section">
                        <b>Tulajdonos adatai</b>
                    </div>

                    <div id="owner">

                        <div id="ownerId">
                            <label for="number">Tulajdonos azonosító</label></br>
                            <input type="text" name="number" id="number" value="{{old('number')}}" required autocomplete="number"></br>
                        </div>

                        <div id="owner_name">
                            <label for="owner_name">Tulajdonos neve</label></br>
                            <input type="text" name="owner_name" id="owner_name" value="{{old('owner_name')}}" required autocomplete="owner_name"></br>
                        </div>

                        <div id="ownerAddresses">
                            <label for="ownerAddress">Lakcím</label></br>
                            <select id="ownerAddress" name="user.address">
                                @foreach($addresses as $address)
                                    <option value="{{$address->id_address}}">{{$address->post_code}}, {{$address->city}}, {{$address->street_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="divider">
                <div class="gridBox">

                    <div class="left-section">
                        <b>Stílus</b>
                    </div>

                    <div id="style">

                        <div id="label">
                            <label for="label">Cimke</label></br>
                            <input type="text" name="label" id="label" value="{{old('label')}}" required autocomplete="label"></br>
                        </div>

                        <div id="color">
                            <label for="color_code">Számla színe</label></br>
                            <input type="color" name="color_code" id="color_code" value="#e66465" value="{{old('color_code')}}" required autocomplete="color_code"></br>
                        </div>

                    </div>
                </div>

                <hr class="divider">
                <div class="button">
                    <a href="{{route('provider.index')}}">Cancel</a>
                    <button type="submit">Hozzáadás</button>
                </div>
            </form>
        </div>
    </div>
@endsection

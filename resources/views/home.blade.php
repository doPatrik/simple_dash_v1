@extends('layouts.app')

@section('content')
    <div class="home-container">

        <div class="info-card-container">


            <info-card-component
                :labels="{first:'Havi befizetetlen számla', second:'Számlák összege'}"
                :classes="{icon: 'fas fa-coins', iconBg: 'month', tag: 'blue', valueColor: 'orange'}"
                :values="{
                    first: {value: {{$monthlyBills['db']}}, postFix: 'db'},
                    second: {value: {{$monthlyBills['price'] ? $monthlyBills['price'] : 0}}, postFix: 'Ft'}
                }"
            ></info-card-component>

            <info-card-component
                :labels="{first:'Összes befizetetlen számla', second:'Számlák összege'}"
                :classes="{icon: 'far fa-chart-bar', iconBg: 'all', tag: 'crimson', valueColor: 'dark-grey'}"
                :values="{
                    first: {value: {{$allBills['db']}}, postFix: 'db'},
                    second: {value: {{$allBills['price'] ? $allBills['price'] : 0}}, postFix: 'Ft'}
                }"
            ></info-card-component>

            <info-card-component
                :labels="{first:'Következő számla határidő', second:'Összege'}"
                :classes="{icon: 'far fa-calendar-alt', iconBg: 'dead-line', tag: 'green', valueColor: 'corn-blue'}"
                :values="{
                    first: {value: {{json_encode($nextBill['dead_line'])}}, postFix: ''},
                    second: {value: {{$nextBill['price'] ? $nextBill['price'] : 0}}, postFix: 'Ft'}
                }"
            ></info-card-component>

        </div>
        <charts-component :currentyear="{{$currentYear}}"></charts-component>

        <incoming-bill-component :bills="{{json_encode($incomingBills)}}"></incoming-bill-component>
    </div>
@endsection

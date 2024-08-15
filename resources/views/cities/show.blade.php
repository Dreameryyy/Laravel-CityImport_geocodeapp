@extends('layouts.app')

@section('content')
<div class="container city-details-page">
    <h3 class="city-detail-title text-center">Detail obce</h3>
    <div class="card mt-4">
        <div class="card-body d-flex align-items-stretch">
            <!-- Left Column -->
            <div class="city-details">
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Meno starostu:
                    </div>
                    <div class="col-6">
                        {{ $city->mayor_name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Adresa obecného úradu:
                    </div>
                    <div class="col-6">
                        {{ $city->city_hall_address }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Telefón:
                    </div>
                    <div class="col-6">
                        {{ $city->phone }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Fax:
                    </div>
                    <div class="col-6">
                        {{ $city->fax }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Email:
                    </div>
                    <div class="col-6">
                        <a href="mailto:{{ $city->email }}">{{ $city->email }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Web:
                    </div>
                    <div class="col-6">
                        <a href="{{ $city->web_address }}" target="_blank">{{ $city->web_address }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-right font-weight-bold">
                        Zemepisné súradnice:
                    </div>
                    <div class="col-6">
                        {{ $city->latitude }}, {{ $city->longitude }}
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="city-image text-center">
                <img src="{{ asset($city->coat_of_arms_path) }}" alt="Coat of Arms" class="img-fluid" style="max-width: 250px;">
                <h4 class="mt-3">{{ $city->name }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection

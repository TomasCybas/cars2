@extends('layouts.app')

@section('content')
    <div class="container position-relative">
        <div class="row justify-content-center mb-5">

                <div class="col-md-6 mr-auto">
                    <div class="form-group">
                        <label for="car_select">Automobilio Nr.</label>
                        <select id="car_select" name="car" class="custom-select">
                            <option selected disabled>Pasirinkti automobilį</option>
                            @foreach($cars as $car)
                                <option value="{{$car->_id}}"
                                        data-imei="{{$car->tracker['imei']}}">{{$car->plate_nr}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-md-6 position-relative">
                Nuvažiuotas atstumas: <span id="distance"></span>
                <div class="preloader">
                    <div class="loader">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

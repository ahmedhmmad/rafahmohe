@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="{{url('/img/login.jpg')}}" alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="brand-wrapper">
                            <img src="{{url('/img/logo1.png')}}" alt="logo" class="logo">
                        </div>
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p class="login-card-description">Please enter your authentication code to login.</p>
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <input type="text" name="code" class="form-control"/>
                                <input name="submit" id="submit" class="btn btn-block login-btn mb-4" type="submit"
                                       value="Submit">
                            </div>
                        </form>
                        <p>Enter your recovery code
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <input type="text" name="recovery_code" class="form-control"/>
                                <input name="submit" id="submit" class="btn btn-block login-btn mb-4" type="submit"
                                       value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="container p-4">
        {{-- Show errors --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card px-4">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-10">
                                <div class="card-body">
                                    <div class="brand-wrapper">
                                        {{-- <img src="{{ url('/img/logo.png') }}" alt="logo" class="logo"> --}}
                                    </div>
                                    <p class="login-card-description">حسابي</p>
                                    <form method="POST" action="{{ route('auth.update-password') }}">
                                        @csrf
                                        {{-- <input type="hidden" name="token" value="{{ $request->route('token') }}"> --}}

                                        <!-- Error message for current_password -->
                                        @error('current_password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="current_password">كلمة المرور الحالية</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="password" name="current_password" id="current_password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">كلمة المرور الجديدة</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="password" name="password" id="password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center"> <!-- Added center alignment -->
                                            <button type="submit" class="btn btn-success mt-4">تحديث كلمة المرور</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

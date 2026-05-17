@extends('layouts.auth')

@section('title', 'login')

@section('main')
    <h4>Login</h4>
    <form class="pt-3">
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
        </div>
        <div class="mt-3">
            <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="{{ url('dashboard') }}">Login</a>
        </div>
        <!-- <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input">
                    Keep me signed in
                </label>
            </div>
            <a href="#" class="auth-link text-black">Forgot password?</a>
        </div> -->
        <div class="text-center mt-4 font-weight-light">
            Don't have an account? <a href="{{ url('register') }}" class="text-primary">Create</a>
        </div>
    </form>
@endsection

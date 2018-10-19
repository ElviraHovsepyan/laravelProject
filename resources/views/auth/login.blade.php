@extends('auth.layout')
@section('content')
    <main class="register-main login-main">
        <div class="container wrapper-small">

            <div class="for-error">
                <p class="text-white text-center mb-0" id="warningsP">
                    @if(isset($warnings))
                    {{ $warnings }}
                    @endif
                </p>
            </div>
            <h5 class="fs-32 text-white font-bold text-center">Log In</h5>
            <form action="{{ route('login') }}" method="post" id="loginForm">
                @csrf
                <div class="row my-row">
                    <div class="my-col col-md-4 offset-md-4 form-group">
                        <input  id="email" type="text"  name="email" class="form-control" placeholder="Email *" autofocus>
                    </div>
                    <div class="my-col col-md-4  offset-md-4 form-group">
                        <input  id="password" type="password"  name="password" class="form-control" placeholder="Password *">
                    </div>
                    <div class="my-col col-md-4  offset-md-4 form-group">
                        <label class="check-container pl-4 text-white fs-14">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Remember me next time
                        </label>
                    </div>
                    <div class="my-col col-md-4 offset-md-4 mb-5">
                        <button type="submit" class="btn text-white btn-blue w-100 border-0 text-uppercase">SIGN IN</button>
                    </div>
                </div>

            </form>
        </div>
    </main>
@endsection

@extends('auth.layout')
@section('content')
    <main class="register-main">
        <div class="container wrapper-small">
            <p>
                @if(isset($warnings))
                    {{ $warnings }}
                @endif
            </p>
            <h5 class="fs-32 text-white font-bold">Register</h5>
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <div class="row my-row">
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span1"></span>
                        <input type="text" id="first_name"  name="first_name" class="form-control" placeholder="First Name *" autofocus>
                    </div>
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span2"></span>
                        <input type="text" id="last_name" name="last_name" autofocus class="form-control" placeholder="Last Name *">
                    </div>
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span3"></span>
                        <input  id="email" type="text" class="form-control" placeholder="Email *" name="email">
                    </div>
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span4"></span>
                        <input type="text" id="company_name" class="form-control" placeholder="Company name *" name="company_name" >
                    </div>
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span5"></span>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password *">
                    </div>
                    <div class="my-col col-md-4 form-group">
                        <span class="message" id="span6"></span>
                        <input type="password" id="password-confirm" class="form-control" placeholder="Password Confirmation *" name="password_confirmation">
                    </div>
                    <div class="my-col col-md-4 offset-md-8 mb-5">
                        <button type="submit" class="btn border-0 text-white btn-blue w-100 text-uppercase">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@extends('auth.layout')
@section('content')
    <main class="register-main register-verify">
        <div class="container wrapper-small">
            {{--<h5 class="fs-32 text-white font-bold">Register</h5>--}}
            <p class="text-white text-center mt-5 fs-18">Please check your Email to verify your account</p>
            <form action="#">
                {{--<div class="form-group">--}}
                    {{--<input type="text" class="form-control text-center" placeholder="code">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<button class="btn border-0 text-white btn-blue w-100 text-uppercase">Send &amp; Finish</button>--}}
                {{--</div>--}}
            </form>
        </div>
    </main>
@endsection

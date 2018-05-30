@extends('tdd-generator::layouts.app', ['body_class' => 'auth'])

@section('content')
    <div class="container">
        <div class="row justify-content-center h-full d-flex">
            <div class="col-lg-4 col-md-6 col-sm-8 mt-5 my-auto">
                <div class="card card-custom">
                    <div class="card-img-top login-img h-25"></div>

                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('Reset Password') }}
                        </h3>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input placeholder="Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0 justify-content-center">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('tdd-generator::layouts.app', ['body_class' => 'auth'])

@section('content')
    <div class="container">
        <div class="row justify-content-center h-full d-flex">
            <div class="col-lg-4 col-md-6 col-sm-8 mt-5 my-auto">
                <div class="card">
                    <div class="card-img-top login-img h-25"></div>

                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('Login') }}
                        </h3>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input placeholder="Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input placeholder="Password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <a class="btn btn-outline-secondary w-100" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary flex-fill mr-2">
                                        Go
                                    </button>

                                    <button @click.prevent="remember=!remember" class="btn btn-primary" :class="{active : remember}">
                                        <label class="form-check-label" for="remember">
                                            <i class="fa fa-fw" :class="[ remember ? 'fa-check-square-o' : 'fa-square-o']"></i>
                                            <input class="form-check-input" id="remember" type="hidden" v-model="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            {{ __('Remember Me') }}
                                        </label>
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

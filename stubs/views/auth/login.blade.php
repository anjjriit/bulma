@extends('layouts.auth')

@section('title', 'Login')

@section('body')
    <section id="auth" class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                            Login
                        </h1>
                        <div class="box">
                            <form role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}

                                @if ($errors->has('email'))
                                    <label class="label">Email</label>
                                    <p class="control has-icon has-icon-right">
                                        <input class="input is-danger" type="text" name="email"
                                               placeholder="Email input" value="{{ old('email') }}">
                                        <span class="icon is-small">
                                                <i class="fa fa-warning"></i>
                                            </span>
                                        <span class="help is-danger">{{ $errors->first('email') }}</span>
                                    </p>
                                @else
                                    <label class="label">Email</label>
                                    <p class="control">
                                        <input class="input" type="text" name="email" placeholder="jsmith@example.org"
                                               required autofocus>
                                    </p>
                                @endif

                                @if ($errors->has('password'))
                                    <label class="label">Email</label>
                                    <p class="control has-icon has-icon-right">
                                        <input class="input is-danger" type="text" name="password"
                                               placeholder="●●●●●●●">
                                        <span class="icon is-small">
                                                <i class="fa fa-warning"></i>
                                            </span>
                                        <span class="help is-danger">{{ $errors->first('password') }}</span>
                                    </p>
                                @else
                                    <label class="label">Password</label>
                                    <p class="control">
                                        <input class="input" name="password" type="password" placeholder="●●●●●●●">
                                    </p>
                                @endif

                                <hr>
                                <p class="control">
                                    <button class="button is-primary">Login</button>
                                </p>
                            </form>
                        </div>
                        <p class="has-text-centered">
                            <a href="{{ url('/register') }}">Register an Account</a>
                            |
                            <a href="{{ url('/password/reset') }}">Forgot password</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

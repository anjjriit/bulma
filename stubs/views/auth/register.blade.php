@extends('layouts.auth')

@section('title', 'Register')

@section('body')
    <div id="app">
        <section id="auth" class="hero is-fullheight">

            <div class="hero-body">
                <div class="container">
                    <div class="columns is-vcentered">
                        <div class="column is-4 is-offset-4">
                            <h1 class="title">
                                Register
                            </h1>
                            <div class="box">
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                                    {{ csrf_field() }}

                                    @if ($errors->has('name'))
                                        <label class="label">Name</label>
                                        <p class="control has-icon has-icon-right">
                                            <input class="input is-danger" type="text" name="name" value="{{ old('name') }}">
                                            <span class="icon is-small">
                                                <i class="fa fa-warning"></i>
                                            </span>
                                            <span class="help is-danger">{{ $errors->first('name') }}</span>
                                        </p>
                                    @else
                                        <label class="label">Name</label>
                                        <p class="control">
                                            <input class="input" type="text" name="name" placeholder="John Smith" autofocus>
                                        </p>
                                    @endif

                                    @if ($errors->has('email'))
                                        <label class="label">Email</label>
                                        <p class="control has-icon has-icon-right">
                                            <input class="input is-danger" type="text" name="email" placeholder="Email input" value="{{ old('email') }}">
                                            <span class="icon is-small">
                                                <i class="fa fa-warning"></i>
                                            </span>
                                            <span class="help is-danger">{{ $errors->first('email') }}</span>
                                        </p>
                                    @else
                                        <label class="label">Email</label>
                                        <p class="control">
                                            <input class="input" type="text" name="email" placeholder="jsmith@example.org">
                                        </p>
                                    @endif

                                    @if ($errors->has('password'))
                                        <label class="label">Password</label>
                                        <p class="control has-icon has-icon-right">
                                            <input class="input is-danger" type="text" name="password" placeholder="●●●●●●●">
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

                                    <label class="label">Confirm Password</label>
                                    <p class="control">
                                        <input class="input" type="password" name="password_confirmation" placeholder="●●●●●●●">
                                    </p>

                                    <hr>
                                    <p class="control">
                                        <button class="button is-primary">Register</button>
                                    </p>
                                </form>
                            </div>
                            <p class="has-text-centered" id="login-links">
                                <a href="{{ url('/login') }}">Login</a>
                                |
                                <a href="{{ url('/password/reset') }}">Forgot password</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

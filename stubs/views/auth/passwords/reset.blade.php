@extends('layouts.auth')

@section('title', 'Reset Password')

@section('body')
    <section id="auth" class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-4 is-offset-4">
                        <h1 class="title">
                            Reset Password
                        </h1>
                        <div class="box">
                            @if (session('status'))
                                <div class="notification">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="token" value="{{ $token }}">

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
                                               required>
                                    </p>
                                @endif

                                @if ($errors->has('password'))
                                    <label class="label">Email</label>
                                    <p class="control has-icon has-icon-right">
                                        <input class="input is-danger" type="text" name="password" placeholder="●●●●●●●"
                                               required>
                                        <span class="icon is-small">
                                                <i class="fa fa-warning"></i>
                                            </span>
                                        <span class="help is-danger">{{ $errors->first('password') }}</span>
                                    </p>
                                @else
                                    <label class="label">Password</label>
                                    <p class="control">
                                        <input class="input" name="password" type="password" placeholder="●●●●●●●"
                                               required>
                                    </p>
                                @endif

                                <label class="label">Confirm Password</label>
                                <p class="control">
                                    <input class="input" type="password" name="password_confirmation"
                                           placeholder="●●●●●●●" required>
                                </p>

                                <hr>
                                <p class="control">
                                    <button class="button is-primary">Reset Password</button>
                                </p>
                            </form>
                        </div>
                        <p class="has-text-centered">
                            <a href="{{ url('/login') }}">Login</a>
                            |
                            <a href="{{ url('/register') }}">Register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

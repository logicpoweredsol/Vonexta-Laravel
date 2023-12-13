@extends('layouts.guest')
<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />



@section('content')
<div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form method="POST" action="{{ route('login') }}">
      @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @if($errors->has('account_disabled'))
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                  <li>{{ $errors->first('account_disabled') }}</li>
                </ul>
              </div>
          </div>
        @endif

          <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                  <x-input-error :messages="$errors->get('email')" class="mt-2" />
              </div>
          </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

        </div>
          <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                  <x-input-error :messages="$errors->get('password')" class="mt-2" />
              </div>
          </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember_me" name="remember">
              <label for="remember_me">
              {{ __('Remember me') }}
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Log in') }}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>


        @if (Route::has('password.request'))
            <p class="mb-1">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </p>
        @endif

      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
@endSection

@extends('admin::layouts.anonymous-master')

@section('page_title')
    {{ __('admin::app.users.sessions.title') }}
@stop

@section('content')

    <div class="panel">

        <div class="panel-content">

            <div class="form-container" style="text-align: left">

                <h1>{{ __('admin::app.users.sessions.title') }}</h1>

                <form method="POST" action="login" @submit.prevent="onSubmit">
                    @csrf

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email">{{ __('admin::app.users.sessions.email') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="email" name="email"/>
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                        <label for="password">{{ __('admin::app.users.sessions.password') }}</label>
                        <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password"/>
                        <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                    </div>

                    <div class="control-group">
                        <a href="{{ route('admin.forget-password.create') }}">{{ __('admin::app.users.sessions.forget-password-link-title') }}</a>
                    </div>

                    <div class="control-group">
                        <span class="checkbox">
                            <input type="checkbox" id="remember" name="remember" value="1">
                            <label class="checkbox-view" for="remember"></label>
                            {{ __('admin::app.users.sessions.remember-me') }}
                        </span>
                    </div>
                    
                    <div class="button-group">
                        <button class="btn btn-xl btn-primary">{{ __('admin::app.users.sessions.submit-btn-title') }}</button>
                    </div>
                </form>

            </div>
        
        </div>

    </div>

@stop
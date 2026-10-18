@extends('layouts.gate')
@section('title_key', 'login_title')
@section('title_default', 'چوونەژوورەوە - سیستەمی فرۆشتن')

@section('content')
    <h2 class="gate-title" data-i18n="login_title">چوونەژوورەوە</h2>
    <p class="gate-sub" data-i18n="login_subtitle">بەخێربێیت بۆ سیستەمی فرۆشتنی مارکێتەکەم</p>

    <form id="loginform" method="post" action="/login">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="username" data-i18n="login_username">ناوی بەکارهێنەر</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control" data-i18n="login_username_placeholder" placeholder="ناوی بەکارهێنەر" autofocus>
        </div>

        <div class="form-group">
            <label for="password" data-i18n="login_password">تێپەڕەوشە</label>
            <input type="password" id="password" name="password" class="form-control" data-i18n="login_password_placeholder" placeholder="تێپەڕەوشە">
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
            <label class="form-check-label" for="rememberMe" data-i18n="login_remember">منت بیربێت</label>
        </div>

        <button type="submit" class="btn btn-primary btn-login" data-i18n="login_button">چوونەژوورەوە</button>
    </form>
@endsection

@extends('layouts.gate')
@section('title_key', 'dev_view_title')
@section('title_default', 'Dev View')

@section('content')
    <h2 class="gate-title">Dev View</h2>
    <p class="gate-sub">ئێوە بە ئەندامێتی Supper Dev تۆماربووە، دەتوانن بەخێر بێن بۆ سیستەم یان بگەڕێنەوە بۆ ماڵەوە.</p>

    <div class="gate-actions">
        <a href="{{ url('/home') }}" class="btn btn-primary">چوونە ژوورەوە بۆ ماڵەوە</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">چوونەدەرەوە</button>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')

<div class="container">
    <div class="form__group">
        <p class="msg">登録していただいたメールアドレスに認証メールを送付しました。 <br>
        メール認証を完了してください。</p>

        <a href="http://localhost:8025" target="_blank" class="btn-check">
            認証はこちらから
        </a>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-resend">認証メールを再送信</button>
        </form>
    </div>
</div>

@endsection
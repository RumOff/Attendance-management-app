@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="container">

  <form class="form" action="{{ route('register') }}" method="post">
    @csrf
    <h1 class="form__title">
      会員登録
    </h1>
    
    <div class="form__group">
      <p class="form__label--item">お名前</p>
      <div class="form__group-content">
        <div class="form__input">
          
          <input type="text" name="name" value="{{ old('name') }}" class="form__input--text" />

          <p class="error">
            @error('name'){{ $message }}@enderror
          </p>
          
        </div>
      </div>
    </div>

    <div class="form__group">
      <p class="form__label--item">メールアドレス</p>
      <div class="form__group-content">
        <div class="form__input">

          <input type="email" name="email" value="{{ old('email') }}" class="form__input--text" />

          <p class="error">
            @error('email'){{ $message }}@enderror
          </p>

        </div>
      </div>
    </div>

    <div class="form__group">
      <p class="form__label--item">パスワード</p>
      <div class="form__group-content">
        <div class="form__input">

          <input type="password" name="password" value="{{ old('password') }}" class="form__input--text" />

          <p class="error">
            @error('password'){{ $message }}@enderror
          </p>

        </div>
      </div>
    </div>

    <div class="form__group">
      <p class="form__label--item">確認用パスワード</p>
      <div class="form__group-content">
        <div class="form__input">

          <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form__input--text" />

          <p class="error">
            @error('password_confirmation'){{ $message }}@enderror
          </p>

        </div>
      </div>
    </div>

    <div class="form__button">
      <button class="btn-red form__button-submit" type="submit" formnovalidate >登録</button>
    </div>
    <div class="auth-parent">
      <a class="auth__button" href="/login">ログインの方はこちら</a>
    </div>
  </form>
  
</div>
@endsection

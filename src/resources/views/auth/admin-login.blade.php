@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')

<div class="container">
  
  <form class="form" action="/login" method="post">
    @csrf
    <h1 class="form__title">
      管理者ログイン
    </h1>

    <div class="form__group">
      <p class="form__label--item">メールアドレス</p>
        <div class="form__input">

          <input type="email" name="email" value="{{ old('email') }}"
          class="form__input--text" />
          
          <p class="error">
            @error('email'){{ $message }}@enderror
          </p>
      
       </div> 
    </div>

    <div class="form__group">
      <p class="form__label--item">パスワード</p>
        <div class="form__input">

          <input type="password" name="password" class="form__input--text" />

          <p class="error">
            @error('password'){{ $message }}@enderror
          </p>

        </div>
    </div>

    <div class="form__group">
      <button class="form__button-submit btn-red" type="submit">管理者ログインする</button>
    </div>

  </form>
  
</div>
@endsection

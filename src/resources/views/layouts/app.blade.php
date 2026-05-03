<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtech attendance manage app</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">

      {{-- ロゴ --}}
      <a class="header__logo" href="/">
        <img src="{{ asset('images/COACHTECH_header_logo.png') }}" alt="logo" class="header__img">
      </a>

      {{-- ナビ --}}
      <nav class="header__nav">
        <ul class="header__list {{ request()->is('login') || request()->is('register') ? 'hidden' : '' }}">
          
          {{-- ログインの時のみ表示 --}}
          @auth
            <li class="header__item">
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="header__button">ログアウト</button>
              </form>
            </li>
          @else
              <li class="header__item">
                <a href="{{ route('login') }}" class="header__nav">ログイン</a>
              </li>
          @endauth


          <li class="header__item">
            <a href="/attendance" class="header__nav">勤怠</a>
            <a href="/attendance/list" class="header__nav">勤怠一覧</a>
            <a href="/stamp_correction_request/list" class="header__nav">申請</a>
          </li>

        </ul>
      </nav>
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>
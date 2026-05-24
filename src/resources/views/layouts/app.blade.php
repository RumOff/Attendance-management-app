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
			@if(Auth::guard('admin')->check())
				<a class="header__logo" href="{{ route('admin.history') }}">
					<img src="{{ asset('images/COACHTECH_header_logo.png') }}" alt="logo" class="header__img">
				</a>
			@elseif(Auth::guard('web')->check())
				<a class="header__logo" href="{{ route('staff.history') }}">
					<img src="{{ asset('images/COACHTECH_header_logo.png') }}" alt="logo" class="header__img">
				</a>
			@endif

			{{-- ナビ --}}
			<nav class="header__nav">
				<ul class="header__list {{ request()->is('login') || request()->is('register') ? 'hidden' : '' }}">

				{{-- 管理者ログイン中 --}}
				@if(Auth::guard('admin')->check())
					<a href="/admin/attendance/list" class="header__link" >勤怠一覧</a>
					<a href="/admin/staff/list" class="header__link" >スタッフ一覧</a>
					<a href="/stamp_correction_request/list" class="header__link">申請一覧</a>
					<form action="/admin/logout" method="POST">
						@csrf
						<button class="header__button">ログアウト</button>
					</form>

				{{-- 一般ユーザーログイン中 --}} 
				@elseif(Auth::guard('web')->check())
					
					<a href="/attendance" class="header__link">勤怠</a>
					<a href="/attendance/list" class="header__link">勤怠一覧</a>
					<a href="/stamp_correction_request/list" class="header__link">申請</a>
					<form action="/logout" method="POST">
						@csrf
						<button class="header__button">ログアウト</button>
					</form>
					
				@endif

				</ul>
			</nav>
		</div>
	</header>

	<main>
		@yield('content')
	</main>
</body>

</html>
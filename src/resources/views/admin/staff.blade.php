@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/adminlogin.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="content">
            <h1 class="page__title">
                スタッフ一覧
            </h1>

            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>月次勤怠</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($staffs as $staff)

                        <tr>

                            <td>
                                {{ $staff->name }}
                            </td>

                            <td>
                                {{ $staff->email }}
                            </td>

                            <td>
                                <a href="/admin/attendance/staff/{{ $staff->id }}" class="history__detail">詳細</a>
                            </td>

                        </tr>

                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/adminlogin.css') }}">
@endsection

@section('content')

    <h1 class="staff_title">
        スタッフ一覧
    </h1>

    <table class="staff__table">
        <thead class="staff__table--thead">
            <tr class="staff__table--header-row">
                <th class="staff__table--header">名前</th>
                <th class="staff__table--header">メールアドレス</th>
                <th class="staff__table--header">月次勤怠</th>
            </tr>
        </thead>

        <tbody class="staff__table--tbody">
            @foreach ($staffs as $staff)

                <tr class="staff__table-row">

                    <td class="staff__table--data">
                        {{ $staff->name }}
                    </td>

                    <td class="staff__table--data">
                        {{ $staff->email }}
                    </td>

                    <td class="staff__table--data">
                        <a href="/admin/attendance/staff/{{ $staff->id }}" class="staff__detail">詳細</a>
                    </td>

                </tr>

            @endforeach
        </tbody>

    </table>
@endsection
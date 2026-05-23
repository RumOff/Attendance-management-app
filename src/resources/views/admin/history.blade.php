@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/history.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="content">
            <h1 class="page__title">
                {{ $currentDate->format('Y年n月j日') }}の勤怠
            </h1>

            {{-- カレンダー --}}
            <div class="month-nav">
                <a href="{{ url()->current() }}?date={{ $prevDate }}" class="month-nav__link"><span>←</span> 前日</a>

                <div class="month-nav__display">
                    <img src="{{ asset('images/Schedule icon.png') }}" alt="カレンダー">
                    <span>{{ $currentDate->format('Y/m/d') }}</span>
                </div>

                <a href="{{ url()->current() }}?date={{ $nextDate }}" class="month-nav__link">翌日 <span>→</span></a>
            </div>

            {{-- テーブル --}}
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($attendances as $attendance)

                        <tr class="history__table-row">

                            {{-- 名前 --}}
                            <td class="history__table--data">
                                {{ $attendance->user->name }}
                            </td>

                            {{-- 出勤 --}}
                            <td class="history__table--data">
                                {{ $attendance->clock_in->format('H:i') ?? null }}
                            </td>

                            {{-- 退勤 --}}
                            <td class="history__table--data">
                                {{ $attendance->clock_out->format('H:i') ?? null }}
                            </td>

                            {{-- 休憩 --}}
                            <td class="history__table--data">
                                @if ($attendance && $attendance->break_minutes !== null)
                                    {{ floor($attendance->break_minutes / 60) }}:{{ str_pad($attendance->break_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @else

                                @endif
                            </td>

                            {{-- 合計 --}}
                            <td class="history__table--data">
                                @if ($attendance && $attendance->total_minutes !== null)
                                    {{ floor($attendance->total_minutes / 60) }}:{{ str_pad($attendance->total_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @else

                                @endif
                            </td>

                            {{-- 詳細 --}}
                            <td class="history__table--data">
                                @if($attendance && $attendance->id !== null)
                                    <a href="{{ route('admin.show', $attendance->id) }}" class="history__detail">詳細</a>
                                @else
                                    <p class="history__detail">詳細</p>
                                @endif
                            </td>

                        </tr>

                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

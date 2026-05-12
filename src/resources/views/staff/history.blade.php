
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/adminlogin.css') }}">
@endsection

@section('content')
    
<h1 class="history_title">
        勤怠一覧
    </h1>
    <input type="month" name="month" value="{{ $currentMonth->format('Y-m') }}">
    <p class="history_date">

    </p>

    <table class="history__table">
        <thead class="history__table--thead">
            <tr class="history__table--header-row">
                <th class="history__table--header">日付</th>
                <th class="history__table--header">出勤</th>
                <th class="history__table--header">退勤</th>
                <th class="history__table--header">休憩</th>
                <th class="history__table--header">合計</th>
                <th class="history__table--header">詳細</th>
            </tr>
        </thead>

        <tbody class="history__table--tbody">
            @foreach ($dates as $date)
                        @php
        $key = $date->format('Y-m-d');
        $attendance = $attendances[$key] ?? null;
                        @endphp

                        <tr class="history__table-row">

                            <td class="history__table--data">
                                {{ $date->format('m/d') }}
                            </td>

                            <td class="history__table--data">
                                {{ $attendance->clock_in ?? null }}
                            </td>

                            <td class="history__table--data">
                                {{ $attendance->clock_out ?? null }}
                            </td>

                            <td class="history__table--data">
                                @if ($attendance && $attendance->break_minutes !== null)
                                    {{ floor($attendance->break_minutes / 60) }}:{{ str_pad($attendance->break_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @else

                                @endif
                            </td>

                            <td class="history__table--data">
                                @if ($attendance && $attendance->total_minutes !== null)
                                    {{ floor($attendance->total_minutes / 60) }}:{{ str_pad($attendance->total_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @else

                                @endif
                            </td>

                            <td class="history__table--data">
                                @if($attendance && $attendance->id !== null)
                                    <a href="/attendance/detail/{{ $attendance->id }}" class="history__detail">詳細</a>
                                @else
                                    <p class="history__detail">詳細</p>
                                @endif
                            </td>

                        </tr>

            @endforeach
        </tbody>

    </table>
@endsection
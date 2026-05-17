@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/show.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="content-show">
            <h1 class="page__title">
                勤怠詳細
            </h1>

            <form action="{{ route('requests.storeRequests') }}" method="POST">
                @csrf

                <table class="attendance-table attendance-table__show">

                    <tr>
                        <th class="attendance-table__show--th">名前</th>
                        <td class="attendance-table__show--td">
                            {{ $attendance->user->name }}
                        </td>
                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">日付</th>
                        <td class="attendance-table__show--td">
                            <div class="date-range">
                                <p>{{ $attendance->date->format('Y年') }}</p>
                                <p>{{ $attendance->date->format('n月j日') }}</p>
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">出勤・退勤</th>
                        <td class="attendance-table__show--td">
                            <div class="time-range">
                                <input type="time" name="clock_in" value="{{ $attendance->clock_in ?? '-' }}">
                                <p>～</p>
                                <input type="time" name="clock_out" value="{{ $attendance->clock_out ?? '-' }}">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">休憩</th>
                        <td class="attendance-table__show--td">
                            <div class="time-range">
                                <input type="time" name="break_start" value="{{ $attendance->break_start ?? '-' }}">
                                <p>～</p>
                                <input type="time" name="break_end" value="{{ $attendance->break_end ?? '-' }}">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">備考</th>
                        <td class="attendance-table__show--td">
                            <textarea name="remarks" id=""></textarea>

                            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                        </td>
                    </tr>
                </table>
                <div class="button-area">
                    <button type="submit" class="btn-black btn-submit">修正</button>
                </div>

            </form>
        </div>
    </div>
@endsection
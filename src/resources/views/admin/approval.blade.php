@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/showApprove.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="content-show">
            <h1 class="page__title">
                勤怠詳細
            </h1>

            <table class="attendance-table attendance-table__show">

                <tr>
                    <th class="attendance-table__show--th">名前</th>
                    <td class="attendance-table__show--td">
                        {{ $attendanceRequest->user->name }}
                    </td>
                </tr>

                <tr>
                    <th class="attendance-table__show--th">日付</th>
                    <td class="attendance-table__show--td">
                        <div class="date-range">
                            <p>{{ $attendanceRequest->attendance->date->format('Y年') }}</p>
                            <p>{{ $attendanceRequest->attendance->date->format('n月j日') }}</p>
                        </div>
                    </td>

                </tr>

                <tr>
                    <th class="attendance-table__show--th">出勤・退勤</th>
                    <td class="attendance-table__show--td">
                        <div class="time-range">
                            <p>
                                {{ $attendanceRequest->attendance->clock_in->format('H:i') ?? '-' }}
                                ～
                                {{ $attendanceRequest->attendance->clock_out->format('H:i') ?? '-' }}
                            </p>
                        </div>
                    </td>
                </tr>

                @foreach ($attendanceRequest->attendance->breaks as $break)
                    <tr>
                        <th class="attendance-table__show--th">休憩</th>
                        <td class="attendance-table__show--td">
                            <div class="time-range">
                                <p>
                                    {{ $break->break_start->format('H:i') ?? '-' }}
                                    ～
                                    {{ $break->break_end->format('H:i') ?? '-' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- 空欄1行 --}}
                <tr>
                    <th class="attendance-table__show--th">休憩</th>
                    <td class="attendance-table__show--td">
                        <div class="time-range">

                        </div>
                    </td>
                </tr>

                <tr>
                    <th class="attendance-table__show--th">備考</th>
                    <td class="attendance-table__show--td">
                        <p>
                            {{ $attendanceRequest->attendance->remarks }}
                        </p>
                    </td>
                </tr>
            </table>

            <div class="button-area">
                @if($attendanceRequest->status === 'approved')

                    <p class="approved-text">
                        承認済み
                    </p>
                @else
                    <form action="{{ route('requests.approve', $attendanceRequest->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="button-area">
                            <button type="submit" class="btn-black btn-submit">承認</button>
                        </div>

                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
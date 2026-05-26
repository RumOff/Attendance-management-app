@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff/show.css') }}">
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
                                <input type="time" name="clock_in" value="{{ old('clock_in', optional($attendance->clock_in)->format('H:i')) }}">
                                <p>～</p>
                                <input type="time" name="clock_out" value="{{ old('clock_out', optional($attendance->clock_out)->format('H:i')) }}">
                                <p class="error">@error('clock_in'){{ $message }}@enderror</p>
                            </div>
                        </td>
                    </tr>


                    @foreach ($attendance->breaks as $index => $break)
                        <tr>
                            <th class="attendance-table__show--th">休憩</th>
                            <td class="attendance-table__show--td">
                                <div class="time-range">
                                    <input type="time" name="break_start[]" value="{{ old('break_start.' . $index, optional($break->break_start)->format('H:i')) }}">
                                    <p>～</p>
                                    <input type="time" name="break_end[]" value="{{ old('break_end.' . $index,optional($break->break_end)->format('H:i')) }}">
                                </div>

                                <p class="error">@error("break_start.$index"){{ $message }}@enderror</p>
                                <p class="error">@error("break_end.$index"){{ $message }}@enderror</p>

                            </td>
                        </tr>
                        @endforeach

                        {{-- 空欄1行 --}}
                        <tr>
                            <th class="attendance-table__show--th">休憩</th>
                            <td class="attendance-table__show--td">
                                <div class="time-range">
                                    <input type="time" name="break_start[]" value="{{ old('break_start.'.count($attendance->breaks)) }}">
                                    <p>～</p>
                                    <input type="time" name="break_end[]" value="{{ old('break_end.'.count($attendance->breaks)) }}">
                                </div>
                                <p class="error">
                                    @error('break_start.'.count($attendance->breaks)){{ $message }}@enderror
                                </p>
                                <p class="error">
                                    @error('break_end.'.count($attendance->breaks)){{ $message }}@enderror
                                </p>
                        </td>
                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">備考</th>
                        <td class="attendance-table__show--td">
                            <textarea name="remarks" id="">{{ old('remarks',$attendance->remarks) }}</textarea>
                            <p class="error">@error('remarks'){{ $message }}@enderror</p>
                            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                        </td>
                    </tr>
                </table>


                <div class="button-area">
                    @if($attendanceRequest && $attendanceRequest->status === 'pending')
                        <p class="error">
                            *承認待ちのため修正はできません。
                        </p>
                    @else
                        <button type="submit" class="btn-black btn-submit">修正</button>
                    @endif
                </div>

            </form>
        </div>
    </div>
@endsection
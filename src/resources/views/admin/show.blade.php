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
                                <input class="attendance-input" type="{{ old('clock_in', optional($attendance->clock_in)->format('H:i')) ? 'time' : 'text' }}" name="clock_in"
                                    value="{{ old('clock_in', optional($attendance->clock_in)->format('H:i')) }}" onfocus="this.type='time'"
                                    onblur="if(!this.value)this.type='text'">
                                ～
                                <input class="attendance-input" type="{{ old('clock_out', optional($attendance->clock_out)->format('H:i')) ? 'time' : 'text' }}" name="clock_out"
                                    value="{{ old('clock_out', optional($attendance->clock_out)->format('H:i')) }}" onfocus="this.type='time'"
                                    onblur="if(!this.value)this.type='text'">
                            </div>
                        </td>
                    </tr>

                    @foreach ($attendance->breaks as $index => $break)
                        <tr>
                            <th class="attendance-table__show--th">
                                {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                            </th>
                            <td class="attendance-table__show--td">
                                <div class="time-range">
                                    <input class="attendance-input" type="{{ old('break_start.' . $index, optional($break->break_start)->format('H:i')) ? 'time' : 'text' }}"
                                        name="break_start[]" value="{{ old('break_start.' . $index, optional($break->break_start)->format('H:i')) }}"
                                        onfocus="this.type='time'" onblur="if(!this.value)this.type='text'">
                                    ～
                                    <input class="attendance-input" type="{{ old('break_end.' . $index, optional($break->break_end)->format('H:i')) ? 'time' : 'text' }}"
                                        name="break_end[]" value="{{ old('break_end.' . $index, optional($break->break_end)->format('H:i')) }}"
                                        onfocus="this.type='time'" onblur="if(!this.value)this.type='text'">
                                    </div>
                                    @error("break_start.$index")<p class="error">{{ $message }}</p>@enderror
                                    @error("break_end.$index")<p class="error">{{ $message }}</p>@enderror
                            </td>
                        </tr>
                    @endforeach

                    {{-- 空欄1行 --}}
                    <tr>
                        <th class="attendance-table__show--th">休憩{{ count($attendance->breaks) + 1 }}</th>
                        <td class="attendance-table__show--td">
                            <div class="time-range">
                                @php
                                    $newIndex = count($attendance->breaks);
                                @endphp
                                <input class="attendance-input" type="{{ old('break_start.' . $newIndex) ? 'time' : 'text' }}" name="break_start[]"
                                    value="{{ old('break_start.' . $newIndex) }}" onfocus="this.type='time'"
                                    onblur="if(!this.value)this.type='text'">
                                ～
                                <input class="attendance-input" type="{{ old('break_end.' . $newIndex) ? 'time' : 'text' }}" name="break_end[]"
                                    value="{{ old('break_end.' . $newIndex) }}" onfocus="this.type='time'"
                                    onblur="if(!this.value)this.type='text'">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th class="attendance-table__show--th">備考</th>
                        <td class="attendance-table__show--td">
                            <textarea name="remarks" id="">{{ old('remarks', $attendance->remarks) }}</textarea>
                            @error('remarks')<p class="error">{{ $message }}</p>@enderror
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
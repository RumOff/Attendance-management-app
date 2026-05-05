勤怠詳細

<p>名前</p>
<p>{{ $attendance->user->name }}</p>

<p>日付</p>
<p>{{ $attendance->date }}</p>
<p></p>

<form action="{{ route('staff.storeRequests') }}" method="POST">
    @csrf
    <p>出勤・退勤</p>
    <input type="time" name="clock_in" value="{{ $attendance->clock_in ?? '-' }}">
    <input type="time" name="clock_out" value="{{ $attendance->clock_out ?? '-' }}">

    <p>休憩</p>
    <input type="time" name="break_start" value="{{ $attendance->break_start ?? '-' }}">
    <input type="time" name="break_end" value="{{ $attendance->break_end ?? '-' }}">

    <p>休憩2</p>


    <p>備考</p>
    <input type="text" name="remarks" value="">

    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

    <button type="submit">修正</button>

</form>
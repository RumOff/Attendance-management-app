申請一覧画面

<table class="request-table">

    <thead class="request-table__head">
        <tr class="request-table__row">
            <th class="request-table__header">状態</th>
            <th class="request-table__header">名前</th>
            <th class="request-table__header">対象日時</th>
            <th class="request-table__header">申請理由</th>
            <th class="request-table__header">申請日時</th>
            <th class="request-table__header">詳細</th>
        </tr>
    </thead>

    <tbody class="request-table__body">

        @foreach ($requests as $request)

            <tr class="request-table__row">

                {{-- 状態 --}}
                <td class="request-table__data">
                    @if ($request->status === 'pending')
                        承認待ち
                    @elseif ($request->status === 'approved')
                        承認済み
                    @endif
                </td>


                {{-- 名前 --}}
                <td class="request-table__data">
                    {{ $request->attendance->user->name }}
                </td>

                {{-- 対象日時 --}}
                <td class="request-table__data">
                    {{ $request->attendance->date }}
                </td>

                {{-- 申請理由 --}}
                <td class="request-table__data">
                    {{ $request->remarks }}
                </td>

                {{-- 申請日時 --}}
                <td class="request-table__data">
                    {{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}
                </td>

                {{-- 詳細 --}}
                <td class="request-table__data">
                    @if($request->attendance->id && $request->attendance->id !== null)
                        <a href="/attendance/detail/{{ $request->attendance->id }}" class="history__detail">詳細</a>
                    @else
                        <p class="history__detail">詳細</p>
                    @endif
                </td>

            </tr>

        @endforeach

    </tbody>

</table>
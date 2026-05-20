@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/request.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="content">
            <h1 class="page__title">
                申請一覧
            </h1>

            {{-- タブ --}}
            <div class="attendance-tab">
                <a href="/stamp_correction_request/list/pending" class="{{ $status === 'pending' ? 'active' : '' }} attendance-tab__link">承認待ち</a>
                <a href="/stamp_correction_request/list/approved" class="{{ $status === 'approved' ? 'active' : '' }}  attendance-tab__link">承認済み</a>
            </div>


            {{-- テーブル --}}
            <table class="attendance-table">

                <thead>
                    <tr>
                        <th>状態</th>
                        <th>名前</th>
                        <th>対象日時</th>
                        <th>申請理由</th>
                        <th>申請日時</th>
                        <th>詳細</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($requests as $request)
                        <tr>

                            {{-- 状態 --}}
                            <td>
                                @if ($request->status === 'pending')
                                    承認待ち
                                @elseif ($request->status === 'approved')
                                    承認済み
                                @endif
                            </td>

                            {{-- 名前 --}}
                            <td>
                                {{ $request->attendance->user->name }}
                            </td>

                            {{-- 対象日時 --}}
                            <td>
                                {{ \Carbon\Carbon::parse($request->attendance->date)->format('Y/m/d') }}
                            </td>

                            {{-- 申請理由 --}}
                            <td>
                                {{ $request->remarks }}
                            </td>

                            {{-- 申請日時 --}}
                            <td>
                                {{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}
                            </td>

                            {{-- 詳細 --}}
                            <td>
                                @if($request->attendance->id && $request->attendance->id !== null)
                                    @if (Auth::guard('admin')->check())
                                        <a href="/admin/attendance/{{ $request->attendance->id }}" class="history__detail">詳細</a>
                                    @elseif(Auth::guard('web')->check())
                                        <a href="/attendance/detail/{{ $request->attendance->id }}" class="history__detail">詳細</a>
                                    @endif
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
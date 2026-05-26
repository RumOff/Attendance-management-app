<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in' => ['nullable'],
            'clock_out' => ['nullable'],
            'remarks' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'remarks.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

        $clockIn = $this->clock_in;
        $clockOut = $this->clock_out;

        $breakStarts = $this->break_start ?? [];
        $breakEnds = $this->break_end ?? [];

        // 出勤・退勤チェック
        if ($clockIn && $clockOut && $clockIn >= $clockOut) {

            $validator->errors()->add(
                'clock_in',
                '出勤時間もしくは退勤時間が不適切な値です'
            );
        }

        foreach ($breakStarts as $index => $breakStart) {

            $breakEnd = $breakEnds[$index] ?? null;

            // 両方空ならスキップ
            if (empty($breakStart) && empty($breakEnd)) {
                continue;
            }

            $clockInTime = strtotime($clockIn);
            $clockOutTime = strtotime($clockOut);

            $breakStartTime = strtotime($breakStart);
            $breakEndTime = strtotime($breakEnd);

            /// 休憩開始チェック
            if (
                $breakStart &&
                (
                    $breakStartTime < $clockInTime ||
                    $breakStartTime > $clockOutTime
                )
            ) {

                $validator->errors()->add(
                    "break_start.$index",
                    '休憩時間が不適切な値です'
                );
            }

            // 休憩終了チェック
            if (
                $breakEnd &&
                (
                    $breakEndTime > $clockOutTime
                )
            ) {

                $validator->errors()->add(
                    "break_end.$index",
                    '休憩時間もしくは退勤時間が不適切な値です'
                );
            }
        }
    });
}
}

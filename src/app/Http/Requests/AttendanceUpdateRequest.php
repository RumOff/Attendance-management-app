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

        // $clockIn = filled($this->clock_in)
        //     ? Carbon::createFromFormat('H:i', $this->clock_in)
        //     : null;

        // $clockOut =  filled($this->clock_out)
        //     ? Carbon::createFromFormat('H:i', $this->clock_out)
        //     : null;

        $clockIn = $this->clock_in;
        $clockOut = $this->clock_out;

        $breakStarts = $this->break_start ?? [];
        $breakEnds = $this->break_end ?? [];

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

            // $breakStart =  filled($breakStart)
            // ? Carbon::createFromFormat('H:i', $breakStart)
            // : null;

            // $breakEnd =  filled($breakEnd)
            // ? Carbon::createFromFormat('H:i', $breakEnd)
            // : null;

            // 休憩開始チェック
            if ($breakStart && (strtotime($breakStart) < strtotime($clockIn) || strtotime($breakStart) > strtotime($clockOut))) {
dd('休憩終了エラー入った');
                $validator->errors()->add(
                    'break_start',
                    '休憩時間が不適切な値です'
                );
            }

            // 休憩終了チェック
            if ($breakEnd && $breakEnd > $clockOut) {

                $validator->errors()->add(
                    'break_end',
                    '休憩時間もしくは退勤時間が不適切な値です'
                );
            }
        }
    });
}
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'remarks' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

        $clockIn = $this->clock_in;
        $clockOut = $this->clock_out;
        $breakStart = $this->break_start;
        $breakEnd = $this->break_end;

        if ($clockIn >= $clockOut) {

            $validator->errors()->add(
                'clock_in',
                '出勤時間もしくは退勤時間が不適切な値です'
            );
        }


        if ($breakStart >= $clockIn || $clockOut >= $breakStart) {

            $validator->errors()->add(
                'break_start',
                '休憩時間が不適切な値です'
            );
        }

        if ($breakEnd >= $clockOut) {

            $validator->errors()->add(
                'clock_in',
                '休憩時間もしくは退勤時間が不適切な値です'
            );
        }
    });
}
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses', 'code')->ignore($this->route('course')),
            ],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'fee' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'min:1', 'max:500'],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,open,closed'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode kursus wajib diisi.',
            'code.string' => 'Kode kursus harus berupa teks.',
            'code.max' => 'Kode kursus maksimal 20 karakter.',
            'code.unique' => 'Kode kursus sudah digunakan.',
            'name.required' => 'Nama kursus wajib diisi.',
            'name.string' => 'Nama kursus harus berupa teks.',
            'name.max' => 'Nama kursus maksimal 120 karakter.',
            'fee.required' => 'Biaya kursus wajib diisi.',
            'fee.integer' => 'Biaya kursus harus berupa bilangan bulat.',
            'fee.min' => 'Biaya kursus minimal 0.',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'quota.integer' => 'Kuota peserta harus berupa bilangan bulat.',
            'quota.min' => 'Kuota peserta minimal 1 orang.',
            'quota.max' => 'Kuota peserta maksimal 500 orang.',
            'start_date.required' => 'Tanggal mulai kursus wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'status.required' => 'Status kursus wajib dipilih.',
            'status.in' => 'Status kursus harus berupa draft, open, atau closed.',
        ];
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        handleAvatar();
        request()->flash();
        if (!session()->has('admin')) {
            return redirect('auth');
        }
       return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'avatar'=>'sometimes|image|mimes:jpeg,jpg,svg,png,gif|max:2048',
            'team_id'=>'required|integer|max_digits:11',
            'email'=>'sometimes|email:filter|unique:employees,email,'.request()->get('id'),
            'first_name'=>'required|string|min:2|regex:/^[\pL\s\-]+$/u',
            'last_name'=>'required|string|min:2|regex:/^[\pL\s\-]+$/u',
            'password'=>'sometimes|nullable|min:8|alpha_num',
            'gender'=>'required',
            'birthday'=>'required|date',
            'address'=>'required|string',
            'salary'=>'required|integer|max-digits: 10',
            'position' => 'required',
            'type_of_work'=> 'required',
            'status' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'This field is required',
            'email' => 'This is not a correct form of email',
            'avatar.uploaded' => 'Your image size has exceed 2MB',
            'first_name.regex' => 'Your first name must only contain letters',
            'last_name.regex' => 'Your last name must only contain letters',
        ];
    }
}

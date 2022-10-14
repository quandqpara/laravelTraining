<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return mixed
     */
    public function authorize()
    {
        handleAvatar();
        request()->flash();
        if (!session()->has('admin')) {
            return redirect('auth')->with('success', 'You are not allow to access this page.');
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
            'avatar'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'team_id'=>'required|integer|max_digits:11',
            'email'=>'required|email:filter|unique:employees,email',
            'first_name'=>'required|string|min:2|regex:/^[\pL\s\-]+$/u',
            'last_name'=>'required|string|min:2|regex:/^[\pL\s\-]+$/u',
            'password'=>'required|min:8|alpha_num',
            'gender'=>'required',
            'birthday'=>'required|date',
            'address'=>'required|string',
            'salary'=>'required|integer|max_digits: 10',
            'position' => 'required',
            'type_of_work'=> 'required',
            'status' => 'required'
        ];

        if (Session('tempImgUrl') !== null){
            $rules['avatar'] = 'sometimes|image|mimes:jpeg,jpg,png,gif|max:2048';
        }

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

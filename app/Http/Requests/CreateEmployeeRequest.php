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
        return [
            'avatar'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'team_id'=>'required|integer|max_digits:11',
            'email'=>'required|email:filter|unique:employees,email',
            'first_name'=>'required|string|min:2',
            'last_name'=>'required|string|min:2',
            'password'=>'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,60}$/',
            'gender'=>'required',
            'birthday'=>'required|date',
            'address'=>'required|string',
            'salary'=>'required|integer|max: 100000000',
            'position' => 'required',
            'type_of_work'=> 'required',
            'status' => 'required'
        ];
    }
}

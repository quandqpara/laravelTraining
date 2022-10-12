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
            return redirect('auth')->with('success', 'You are not allow to access this page.');
            return false;
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
            'avatar'=>'sometimes|image|mimes:jpeg,jpg,png,gif|max:2048',
            'team_id'=>'required|integer|max_digits:11',
            'email'=>'sometimes|email:filter|unique:employees,email,'.request()->get('id'),
            'first_name'=>'required|string|min:2',
            'last_name'=>'required|string|min:2',
            'password'=>'sometimes|nullable|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,60}$/',
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

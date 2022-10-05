<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'avatar'=>'required|mimes:jpeg,jpg,png,gif|max:100000|size:2048',
            'team_id'=>'required|integer|max_digits:11',
            'email'=>'required|email:filter|unique',
            'first_name'=>'required|string|min:2',
            'last_name'=>'required|string|min:2',
            'password'=>'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
            'gender'=>'required',
            'birthday'=>'required|date',
            'address'=>'required|string',
            'salary'=>'required|integer',
            'position' => 'required|string|in:Manager, Team lead, BSE, DEV, Tester',
            'type_of_work'=> 'required|string|in:Full time, Part time, Probationary Staff, Intern',
            'status' => 'required'
        ];
    }
}

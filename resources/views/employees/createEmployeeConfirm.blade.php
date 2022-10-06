@extends('app')
@section('title', 'Create Employee')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="confirm-action-notify alert alert-primary d-flex justify-content-center">
            Do you want to confirm to create an Employee with this information?
        </div>
        <form class="the-form" method="POST" action="{{route('employee.create')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-box hide">
                {{--Avatar--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="avatar" class="col-form-label">Avatar*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="avatar" name="avatar" class="form-control" value="{{request()->get('avatar_url')}}"/>
                    </div>
                </div>

                {{--Team--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="team_id" class="col-form-label">Team*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="team_id" name="team_id" class="form-control"
                               value="{{request()->get('team_id')}}"/>
                    </div>
                </div>

                {{--email--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="email" class="col-form-label">Email*</label>
                    </div>
                    <div class="col-8">
                        <input type="email" id="email" name="email" class="form-control"
                              value="{{request()->get('email')}}"
                        >
                    </div>
                </div>

                {{--first name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="first_name" class="col-form-label">First Name*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="first_name" name="first_name" class="form-control"
                               value="{{request()->get('first_name')}}"
                        >
                    </div>
                </div>

                {{--last name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="last_name" class="col-form-label">Last Name*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="last_name" name="last_name" class="form-control"
                              value="{{request()->get('last_name')}}"
                        >
                    </div>
                </div>

                {{--password--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="password" class="col-form-label">Password*</label>
                    </div>
                    <div class="col-8">
                        <input type="password" id="password" name="password" class="form-control"
                               value="{{request()->get('password')}}"
                        >
                    </div>
                </div>

                {{--gender--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="gender" class="col-form-label">Gender*</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="male" name="gender" value="1" {{isChecked('gender', 1)}}/>
                        <label class="form-label" for="male">Male</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="female" name="gender" value="2" {{isChecked('gender', 2)}}/>
                        <label class="form-label" for="female">Female</label>
                    </div>
                </div>

                {{--birthday--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="birthday" class="col-form-label">Birthday*</label>
                    </div>
                    <div class="col-8">
                        <input type="date" id="birthday" name="birthday" class="form-control"
                               value="{{request()->get('birthday')}}"
                        >
                    </div>
                </div>

                {{--address--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="address" class="col-form-label">Address*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="address" name="address" class="form-control"
                               value="{{request()->get('address')}}"
                        >
                    </div>
                </div>

                {{--salary--}}
                <div class="create-row row g-3 align-items-center">
                    <div class="col-2">
                        <label for="salary" class="col-form-label">Salary*</label>
                    </div>
                    <div class="col-8">
                        <input type="number" id="salary" name="salary" class="form-control"
                               value="{{request()->get('salary')}}"
                        >
                    </div>
                    <div class="col-2"><span><strong> VND</strong></span></div>
                </div>

                {{--position--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="position" class="col-form-label">Position*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="position" name="position" class="form-control"
                               value="{{request()->get('position')}}"
                        >
                    </div>
                </div>

                {{--type_of_work--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="type_of_work" class="col-form-label">Type of work*</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="type_of_work" name="type_of_work" class="form-control"
                               value="{{request()->get('type_of_work')}}"
                        >
                    </div>
                </div>

                {{--status--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="status" class="col-form-label">Status*</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="on_working" name="status" value="1" {{isChecked('status', 1)}}/>
                        <label class="form-label" for="on_working">On working</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="retired" name="status" value="2" {{isChecked('status', 2)}}/>
                        <label class="form-label" for="retired">Retired</label>
                    </div>
                </div>
            </div>
            <div class="form-box">
                {{--Avatar--}}
                <div class="row g-2 align-items-center mt-1">
                    <div class="col-2">
                        <label for="avatar" class="col-form-label">Avatar*</label>
                    </div>
                    <div class="col-8 avatar-display border-round">
                        <img src="{{asset(displayImage())}}">
                    </div>
                </div>

                {{--Team--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="team_id" class="col-form-label">Team*</label>
                    </div>
                    <div class="col-8">
                        {{displayTeamName(request()->get('team_id'), $teams)}}
                    </div>
                </div>

                {{--email--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="email" class="col-form-label">Email*</label>
                    </div>
                    <div class="col-8">
                        {{request()->get('email')}}
                    </div>
                </div>

                {{--first name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="first_name" class="col-form-label">First Name*</label>
                    </div>
                    <div class="col-8">
                        {{request()->get('first_name')}}
                    </div>
                </div>

                {{--last name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="last_name" class="col-form-label">Last Name*</label>
                    </div>
                    <div class="col-8">
                        {{request()->get('last_name')}}
                    </div>
                </div>

                {{--password--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="password" class="col-form-label">Password*</label>
                    </div>
                    <div class="col-8">
                        {{displayPassword(request()->get('password'))}}
                    </div>
                </div>

                {{--gender--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="gender" class="col-form-label">Gender*</label>
                    </div>
                    <div class="col-8">
                       {{displayRadioInput('gender', request()->get('gender'))}}
                    </div>
                </div>

                {{--birthday--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="birthday" class="col-form-label">Birthday*</label>
                    </div>
                    <div class="col-8">
                        {{request()->get('birthday')}}
                    </div>
                </div>

                {{--address--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="address" class="col-form-label">Address*</label>
                    </div>
                    <div class="col-8">
                        {{request()->get('address')}}
                    </div>
                </div>

                {{--salary--}}
                <div class="create-row row g-3 align-items-center">
                    <div class="col-2">
                        <label for="salary" class="col-form-label">Salary*</label>
                    </div>
                    <div class="col-3">
                        {{request()->get('salary')}}
                    </div>
                    <div class="col-2"><span><strong> VND</strong></span></div>
                </div>

                {{--position--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="position" class="col-form-label">Position*</label>
                    </div>
                    <div class="col-8">
                        {{displayDropDownInput(request()->get('position'), $positionList)}}
                    </div>
                </div>

                {{--type_of_work--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="type_of_work" class="col-form-label">Type of work*</label>
                    </div>
                    <div class="col-8">
                        {{displayDropDownInput(request()->get('type_of_work'), $typeOfWork)}}
                    </div>
                </div>

                {{--status--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="status" class="col-form-label">Status*</label>
                    </div>
                    <div class="col-8">
                        {{displayRadioInput('status',request()->get('status'))}}
                    </div>
                </div>

            </div>
            <div class="col-auto submit-box d-flex justify-content-between">
                <button type="button" onclick="resetInput()" class="btn btn-dark">Back</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
        <script>
            function resetInput() {
                window.location.href = '/employees/createEmployee';
            }
        </script>
    </div>
@endsection


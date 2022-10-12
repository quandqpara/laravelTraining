@extends('app')
@section('title', 'Edit Employee Confirm')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(session()->has('message'))
            <div class="notice-message alert alert-danger d-flex justify-content-center">
                {{ session()->get('message') }}
            </div>
        @endif
        <form class="the-form" method="POST" action="{{route('employee.edit')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-box hide">

                {{--ID--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="avatar" class="col-form-label">ID</label>
                    </div>
                    <div class="col-6">
                        {{$data['id']}}
                    </div>
                    <div class="col-auto">
                        <input type="text" id="id" name="id" class="form-control hide"
                               value="{{$data['id']}}"
                        >
                    </div>
                </div>

                {{--Avatar--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="avatar" class="col-form-label">Avatar*</label>
                    </div>
                    <div class="col-6">
                        <input type="text" id="avatar" name="avatar" class="form-control"
                               value="{{$data['avatar']}}"
                        />
                    </div>
                </div>
                <div class="row g-2 align-items-center mt-0.5">
                    <div class="col-3"></div>
                    <div class="col-6 avatar-display border-round">
                        <img src="{{asset($data['avatar'])}}">
                    </div>
                </div>

                {{--Team--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="team_id" class="col-form-label">Team*</label>
                    </div>
                    <div class="col-6">
                        {{setDropdown($teams, 'team_id', 'team_id', $data)}}
                    </div>
                </div>

                {{--email--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="email" class="col-form-label">Email*</label>
                    </div>
                    <div class="col-6">
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{$data['email']}}"
                        >
                    </div>
                </div>

                {{--first name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="first_name" class="col-form-label">First Name*</label>
                    </div>
                    <div class="col-6">
                        <input type="text" id="first_name" name="first_name" class="form-control"
                               value="{{$data['first_name']}}"
                        >
                    </div>
                </div>

                {{--last name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="last_name" class="col-form-label">Last Name*</label>
                    </div>
                    <div class="col-6">
                        <input type="text" id="last_name" name="last_name" class="form-control"
                               value="{{$data['last_name']}}"
                        >
                    </div>
                </div>

                {{--password--}}
                @if(isset($data['password']) && !empty($data['password']))
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="password" class="col-form-label">Password*</label>
                    </div>
                    <div class="col-6">
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                </div>
                @endif

                {{--gender--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="gender" class="col-form-label">Gender*</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="male" name="gender" value="1" {{isChecked('gender', 1, $data)}}/>
                        <label class="form-label" for="male">Male</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="female" name="gender" value="2" {{isChecked('gender', 2, $data)}}/>
                        <label class="form-label" for="female">Female</label>
                    </div>
                </div>

                {{--birthday--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="birthday" class="col-form-label">Birthday*</label>
                    </div>
                    <div class="col-6">
                        <input type="date" id="birthday" name="birthday" class="form-control"
                               value="{{setDate($data['birthday'])}}"
                        >
                    </div>
                </div>

                {{--address--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="address" class="col-form-label">Address*</label>
                    </div>
                    <div class="col-6">
                        <input type="text" id="address" name="address" class="form-control"
                               value="{{$data['address']}}"
                        >
                    </div>
                </div>

                {{--salary--}}
                <div class="create-row row g-3 align-items-center">
                    <div class="col-3">
                        <label for="salary" class="col-form-label">Salary*</label>
                    </div>
                    <div class="col-6">
                        <input type="number" id="salary" name="salary" class="form-control"
                               value="{{$data['salary']}}"
                        >
                    </div>
                    <div class="col-3"><span><strong> VND</strong></span></div>
                </div>

                {{--position--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="position" class="col-form-label">Position*</label>
                    </div>
                    <div class="col-6">
                        {{setDropdown($positionList, 'position', 'position',$data)}}
                    </div>
                </div>

                {{--type_of_work--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="type_of_work" class="col-form-label">Type of work*</label>
                    </div>
                    <div class="col-6">
                        {{setDropdown($typeOfWork, 'type_of_work', 'type_of_work', $data)}}
                    </div>
                </div>

                {{--status--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">data                   <label for="status" class="col-form-label">Status*</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="on_working" name="status" value="1" {{isChecked('status', 1, $data)}}/>
                        <label class="form-label" for="on_working">On working</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="retired" name="status" value="2" {{isChecked('status', 2, $data)}}/>
                        <label class="form-label" for="retired">Retired</label>
                    </div>
                </div>

            </div>

            <div class="form-box">

                {{--ID--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="avatar" class="col-form-label">ID</label>
                    </div>
                    <div class="col-6">
                        {{$data['id']}}
                    </div>
                </div>

                {{--Avatar--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="avatar" class="col-form-label">Avatar*</label>
                    </div>
                    <div class="col-6 avatar-display border-round">
                        <img src="{{asset($data['avatar'])}}">
                    </div>
                </div>

                {{--Team--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="team_id" class="col-form-label">Team*</label>
                    </div>
                    <div class="col-6">
                        {{displayTeamName($data['team_id'], $teams)}}
                    </div>
                </div>

                {{--email--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="email" class="col-form-label">Email*</label>
                    </div>
                    <div class="col-6">
                       {{$data['email']}}
                    </div>
                </div>

                {{--first name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="first_name" class="col-form-label">First Name*</label>
                    </div>
                    <div class="col-6">
                      {{$data['first_name']}}
                    </div>
                </div>

                {{--last name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="last_name" class="col-form-label">Last Name*</label>
                    </div>
                    <div class="col-6">
                        {{$data['last_name']}}
                    </div>
                </div>

                {{--password--}}
                @if(isset($data['password']) && !empty($data['password']))
                    <div class="create-row row g-2 align-items-center">
                        <div class="col-3">
                            <label for="password" class="col-form-label">Password*</label>
                        </div>
                        <div class="col-6">
                            {{displayPassword($data['password'])}}
                        </div>
                    </div>
                @else
                    <div class="create-row row g-2 align-items-center">
                        <div class="col-3">
                            <label for="password" class="col-form-label">Password*</label>
                        </div>
                        <div class="col-6">
                            **********
                        </div>
                    </div>
                @endif

                {{--gender--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="gender" class="col-form-label">Gender*</label>
                    </div>
                    <div class="col-6">
                        {{displayRadioInput('gender', $data['gender'])}}
                    </div>
                </div>

                {{--birthday--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="birthday" class="col-form-label">Birthday*</label>
                    </div>
                    <div class="col-6">
                       {{$data['birthday']}}
                    </div>
                </div>

                {{--address--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="address" class="col-form-label">Address*</label>
                    </div>
                    <div class="col-6">
                       {{$data['address']}}
                    </div>
                </div>

                {{--salary--}}
                <div class="create-row row g-3 align-items-center">
                    <div class="col-3">
                        <label for="salary" class="col-form-label">Salary*</label>
                    </div>
                    <div class="col-2">
                        {{number_format($data['salary'], 0,'.', ',')}}
                    </div>
                    <div class="col-3"><span><strong>(VND)</strong></span></div>
                </div>

                {{--position--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="position" class="col-form-label">Position*</label>
                    </div>
                    <div class="col-6">
                        {{displayDropDownInput($data['position'],$positionList)}}
                    </div>
                </div>

                {{--type_of_work--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="type_of_work" class="col-form-label">Type of work*</label>
                    </div>
                    <div class="col-6">
                        {{displayDropDownInput($data['type_of_work'],$typeOfWork)}}
                    </div>
                </div>

                {{--status--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-3">
                        <label for="status" class="col-form-label">Status*</label>
                    </div>
                    <div class="col-3">
                       {{displayRadioInput('status',$data['status'])}}
                    </div>
                </div>
            </div>

            <div class="col-auto submit-box d-flex justify-content-between">
                <button type="button" onclick="history.back()" class="btn btn-dark"> Back</button>
                <button type="submit" onclick="return confirm('Edit?')" class="btn btn-primary"> Edit</button>
            </div>
        </form>
    </div>
@endsection

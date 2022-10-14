@extends('app')
@section('title', 'Edit Employee')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(session()->has('message'))
            <div class="notice-message alert alert-danger d-flex justify-content-center">
                {{ session()->get('message') }}
            </div>
        @endif
        <form class="the-form" method="POST" action="{{route('employee.editConfirm')}}" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="form-box">
                {{--ID--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="avatar" class="col-form-label">ID</label>
                    </div>
                    <div class="col-9">
                        {{$target['id']}}
                    </div>
                    <div class="col-auto">
                        <input type="text" id="id" name="id" class="form-control hide"
                               value="{{$target['id']}}"
                        >
                    </div>
                </div>

                {{--Avatar--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="avatar" class="col-form-label">Avatar*</label>
                    </div>
                    <div class="col-9">
                        <input type="file" id="avatar" name="avatar" class="form-control"
                               accept="image/png, image/jpg, image/jpeg, image/svg, image/svg image/gif"
                               value="{{Session()->get('tempImgUrl') !== null ? Session()->get('tempImgUrl') : $target['avatar']}}"
                        />
                    </div>
                    @if($errors->has('avatar'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('avatar')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>
                <div class="row g-2 align-items-center mt-0.5">
                    <div class="col-2"></div>
                    <div class="col-9 avatar-display border-round">
                        <img id="preview" src="{{Session()->get('tempImgUrl') !== null ? asset(Session()->get('tempImgUrl')) : asset($target['avatar'])}}">
                    </div>
                </div>
                <script>
                    avatar.onchange = evt => {
                        const [file] = avatar.files
                        if (file) {
                            preview.src = URL.createObjectURL(file)
                        }
                    }
                </script>


                {{--Team--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="team_id" class="col-form-label">Team*</label>
                    </div>
                    <div class="col-9">
                        {{setDropdown($teams, 'team_id', 'team_id', $target)}}
                    </div>
                    @if($errors->has('team_id'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('team_id')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--email--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="email" class="col-form-label">Email*</label>
                    </div>
                    <div class="col-9">
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{old('email') ??$target['email']}}"
                        >
                    </div>
                    @if($errors->has('email'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('email')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--first name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="first_name" class="col-form-label">First Name*</label>
                    </div>
                    <div class="col-9">
                        <input type="text" id="first_name" name="first_name" class="form-control"
                               value="{{old('first_name') ?? $target['first_name']}}"
                        >
                    </div>
                    @if($errors->has('first_name'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('first_name')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--last name--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="last_name" class="col-form-label">Last Name*</label>
                    </div>
                    <div class="col-9">
                        <input type="text" id="last_name" name="last_name" class="form-control"
                               value="{{old('last_name') ?? $target['last_name']}}"
                        >
                    </div>
                    @if($errors->has('last_name'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('last_name')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--password--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="password" class="col-form-label">Password*</label>
                    </div>
                    <div class="col-9">
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    @if($errors->has('password'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('password')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--gender--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="gender" class="col-form-label">Gender*</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="male" name="gender" value="1" {{isChecked('gender', 1, $target)}}/>
                        <label class="form-label" for="male">Male</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="female" name="gender" value="2" {{isChecked('gender', 2, $target)}}/>
                        <label class="form-label" for="female">Female</label>
                    </div>
                    @if($errors->has('gender'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('gender')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--birthday--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="birthday" class="col-form-label">Birthday*</label>
                    </div>
                    <div class="col-9">
                        <input type="date" id="birthday" name="birthday" class="form-control"
                               value="{{setDate(old('birthday') ?? $target['birthday'])}}"
                        >
                    </div>
                    @if($errors->has('birthday'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('birthday')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--address--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="address" class="col-form-label">Address*</label>
                    </div>
                    <div class="col-9">
                        <input type="text" id="address" name="address" class="form-control"
                               value="{{old('address') ?? $target['address']}}"
                        >
                    </div>
                    @if($errors->has('address'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('address')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--salary--}}
                <div class="create-row row g-3 align-items-center">
                    <div class="col-2">
                        <label for="salary" class="col-form-label">Salary*</label>
                    </div>
                    <div class="col-9">
                        <input type="number" id="salary" name="salary" class="form-control"
                               value="{{old('salary') ?? $target['salary']}}"
                        >
                    </div>
                    <div class="col-2"><span><strong> VND</strong></span></div>
                    @if($errors->has('salary'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('salary')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--position--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="position" class="col-form-label">Position*</label>
                    </div>
                    <div class="col-9">
                        {{setDropdown($positionList, 'position', 'position',$target)}}
                    </div>
                    @if($errors->has('position'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('position')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--type_of_work--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="type_of_work" class="col-form-label">Type of work*</label>
                    </div>
                    <div class="col-9">
                        {{setDropdown($typeOfWork, 'type_of_work', 'type_of_work', $target)}}
                    </div>
                    @if($errors->has('type_of_work'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('type_of_work')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

                {{--status--}}
                <div class="create-row row g-2 align-items-center">
                    <div class="col-2">
                        <label for="status" class="col-form-label">Status*</label>
                    </div>
                    <div class="col-3">
                        <input type="radio" id="on_working" name="status" value="1" {{isChecked('status', 1, $target)}}/>
                        <label class="form-label" for="on_working">On working</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="retired" name="status" value="2" {{isChecked('status', 2, $target)}}/>

                        <label class="form-label" for="retired">Retired</label>
                    </div>
                    @if($errors->has('status'))
                        <div class="col-2"></div>
                        <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('status')
                                 {{ $message }}
                                 @enderror
                            </span>
                        </div>
                    @endif
                </div>

            </div>

            <div class="col-auto submit-box d-flex justify-content-between">
                <a type="button" href="{{route('employee.editEmployee', ['id' => $target['id'], 'reset' => true])}}" class="btn btn-dark"> Reset</a>
                <button type="submit" class="btn btn-primary"> Confirm</button>
            </div>
        </form>
    </div>
@endsection

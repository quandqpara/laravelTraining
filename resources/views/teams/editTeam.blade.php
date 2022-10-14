@extends('app')
@section('title', 'Edit Team')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        @if(!empty($target))
            <form class="the-form" method="POST" action="{{route('team.editConfirm')}}"  novalidate>
                @csrf
                <div class="form-box">
                    <div class="row g-2 align-items-center mt-3">
                        <div class="col-4">
                            <label for="id" class="col-form-label">ID: </label>
                        </div>
                        <div class="col-8 hide">
                            <input type="text" id="id" name="id" class="form-control"
                                   value="{{$target['id']}}">
                        </div>
                        <div class="col-8">
                        <span>
                            {{$target['id']}}
                        </span>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-4">
                            <label for="name" class="col-form-label">Team Name:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" id="name" maxlength="128" name="name" class="form-control"
                                   @if(old('name') !== null && !str_contains(session()->get('_previous')['url'], 'search'))
                                       value="{{old('name')}}"
                                   @else
                                       value="{{$target['name']}}"
                                @endif
                            >
                        </div>
                        @if($errors->has('name'))
                            <div class="col-4"></div>
                            <div class="col-8">
                             <span class="err-span no-mg-top">
                                @error('name')
                                 {{ $message }}
                                 @enderror
                            </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="min-vw-60 col-auto submit-box d-flex justify-content-between">
                    <a href="{{route('team.editTeam', $target['id'])}}" class="btn btn-dark btn-submit"> Reset</a>
                    <button type="submit" class="btn btn-primary btn-submit"> Confirm</button>
                </div>
            </form>
        @endif
    </div>
    @dump(Session()->all())
@endsection

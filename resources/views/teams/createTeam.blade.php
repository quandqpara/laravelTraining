@extends('app')
@section('title', 'Create Team')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        <form class="the-form" method="POST" action="{{route('team.createConfirm')}}">
            @csrf
            <div class="form-box">
                <div class="create-row row g-2 align-items-center">
                    <div class="col-4">
                        <label for="name" class="col-form-label">Team Name:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" id="name" name="name" class="form-control"
                               @if(old('name') !== null)
                                   value="{{ old('name') }}"
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
            <div class="col-auto submit-box d-flex justify-content-between">
                <a href="{{route('team.createTeam')}}" class="btn btn-dark"> Reset</a>
                <button type="submit" class="btn btn-primary"> Confirm</button>
            </div>
        </form>
    </div>
@endsection

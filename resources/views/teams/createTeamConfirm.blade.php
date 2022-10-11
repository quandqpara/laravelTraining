@extends('app')
@section('title', 'Confirm Create Team')
@include('components.hnav')

@section('content')
    <div class="content-container">
        <div class="confirm-action-notify alert alert-primary d-flex justify-content-center">
            Do you want to confirm to create a Team with this information?
        </div>
        <form class="the-form" method="POST" action="{{route('team.create')}}">
            @csrf
            <div class="form-box hide">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="name" class="col-form-label">Team Name:</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="name" name="name" class="form-control" value="{{$name}}">
                    </div>
                </div>
            </div>
            <div class="form-box">
                <div>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="name" class="col-form-label">Team Name: </label>
                            <span>{{$name}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto submit-box d-flex justify-content-between">
                <button type="button" onclick="history.back()" class="btn btn-dark">Back</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
@endsection

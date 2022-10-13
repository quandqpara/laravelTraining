@extends('app')
@section('title', 'Edit Team Confirm')
@include('components.hnav')

@section('content')
    <div class="content-container">
        @if(isset($data))
            <div class="confirm-action-notify alert alert-primary d-flex justify-content-center">
                Do you want to confirm to edit team {{$data['id']}} with this information?
            </div>
            <form class="the-form" method="POST" action="{{route('team.edit')}}">
                @csrf
                <div class="form-box hide">
                    <div class="row g-2 align-items-center">
                        <div class="col-4">
                            <label for="id" class="col-form-label">ID: </label>
                        </div>
                        <div class="col-8">
                            <input type="text" id="id" name="id" class="form-control"
                                   value="{{$data['id']}}"
                            >
                        </div>
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-4">
                            <label for="name" class="col-form-label">Team Name:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{$data['name']}}"
                            >
                        </div>
                    </div>
                </div>

                <div class="form-box">
                    <div class="row g-2 align-items-center mt-3">
                        <div class="col-6">
                            <span>ID: </span>
                        </div>
                        <div class="col-4">
                            <span><strong>{{$data['id']}}</strong></span>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-6">
                            <span>New Team Name:</span>
                        </div>
                        <div class="col-4">
                            <span><strong>{{$data['name']}}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="col-auto submit-box d-flex justify-content-between">
                    <button type="button" onclick="history.back()" class="btn btn-dark">Back</button>
                    <button type="submit" onclick="return confirm('Edit?')" class="btn btn-primary">Edit</button>
                </div>
            </form>
        @endif
    </div>
@endsection

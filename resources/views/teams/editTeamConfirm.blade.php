@extends('app')
@section('title', 'Edit Team Confirm')
@include('components.hnav')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit This Team?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure?
                </div>
                <div class="modal-footer">
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

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        @if(isset($data))
            <div class="confirm-action-notify alert alert-primary d-flex justify-content-center">
                Do you want to confirm to edit team {{$data['id']}} with this information?
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
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary del-btn" data-url="{{ route('team.edit') }}"  data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>
                    </div>
                </div>
        @endif
    </div>
@endsection

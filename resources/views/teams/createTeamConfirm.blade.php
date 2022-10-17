@extends('app')
@section('title', 'Confirm Create Team')
@include('components.hnav')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create This Team?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure?
                </div>
                <div class="modal-footer">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        <div class="confirm-action-notify alert alert-primary d-flex justify-content-center">
            Do you want to confirm to create a Team with this information?
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
            <div class="col-auto">
                <a class="btn btn-dark" onclick="history.back()">Back</a>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary del-btn" data-url="{{ route('team.create') }}"  data-bs-toggle="modal" data-bs-target="#exampleModal">Create</button>
            </div>
        </div>

    </div>
@endsection

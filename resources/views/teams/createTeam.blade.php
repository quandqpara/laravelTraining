@extends('app')
@section('title', 'Search Team')
@include('components.hnav')

@section('content')
    <div class="content-container">
        <form class="the-form" method="POST" action="/team/createTeam">
            <div class="form-box">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Password</label>
                    </div>
                    <div class="col-auto">
                        <input type="password" id="inputPassword6" class="form-control"
                               aria-describedby="passwordHelpInline">
                    </div>
                    <span  class="form-text message {{isset($_SESSION['message']['name']) ? '' : 'hide'}}"><small>{{isset($_SESSION['message']['name']) ? handleMessage('name') : ''}}</small></span>
                </div>
            </div>
            <div class="col-auto submit-box d-flex justify-content-between">
                <button type="button" onclick="resetInput()" class="btn btn-dark"> Reset</button>
                <button type="submit" class="btn btn-primary"> Confirm</button>
            </div>
        </form>
    </div>
    <?php var_dump($_SESSION); ?>
@endsection

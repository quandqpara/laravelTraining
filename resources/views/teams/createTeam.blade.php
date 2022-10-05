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
                <div class="row g-2 align-items-center">
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
                <button type="button" onclick="resetInput()" class="btn btn-dark"> Reset</button>
                <button type="submit" class="btn btn-primary"> Confirm</button>
            </div>
        </form>
        <script>
            function resetInput() {
                const url = window.location.href;
                window.location.href = url;
            }
        </script>
    </div>
@endsection

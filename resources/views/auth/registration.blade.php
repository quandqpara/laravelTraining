@extends('app')
@section('title', 'Register')
@include('components.loginHnav')
@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center section-container" style="height: 90vh">
        <div class="login-container">
            <form method="POST" action="{{route('register.custom')}}" class="form-login" novalidate>
                @csrf
                <!-- Name input -->
                <div class="d-flex flex-column form-outline mt-2">
                    <label class="form-label" for="name">Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control"
                           value="{{old('name')}}"
                           required
                    />
                    @if($errors->has('name'))
                        <span class="alert alert-danger">
                        @error('name')
                            {{ $message }}
                            @enderror
                    </span>
                    @endif
                </div>

                <!-- Email input -->
                <div class="d-flex flex-column form-outline mt-2">
                    <label class="form-label" for="email">Email</label>
                    <input type="text"
                           id="email"
                           name="email"
                           class="form-control"
                           value="{{old('email')}}"
                           required
                    />
                    @if($errors->has('email'))
                        <span class="alert alert-danger">
                        @error('email')
                            {{ $message }}
                            @enderror
                    </span>
                    @endif
                </div>

                <!-- Password input -->
                <div class="d-flex flex-column form-outline mt-2">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required/>

                    @if($errors->has('password'))
                        <span class="alert alert-danger">
                        @error('password')
                            {{ $message }}
                            @enderror
                    </span>
                    @endif
                </div>

                <!-- Submit button -->
                <div class="d-flex flex-column form-outline align-items-center justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit" style="max-width: 50%">Sign in</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('app')
@section('title', 'Employee Search')
@include('components.hnav')

@section('content')

    <div class="h-100 w-100 flex-column mb-auto admin-home-sect">
        {{displayNotification()}}
        <div class="mt-3 mb-3 search-box border border-dark">
            <form method="GET" action="{{route('employee.searchEmployee')}}" class="search-form-box m-4 form-create">
                <div class="input-form-box">
                    <!-- team_id input -->
                    <div class="row g-2 align-items-center mb-3 mt-3">
                        <div class="col-2 m-3">
                            <label for="team_id" class="col-form-label">Team</label>
                        </div>
                        <div class="col-6 m-3">
                            {{setDropdown($teams, 'team_id', 'team_id')}}
                        </div>
                        @if($errors->has('team_id'))
                            <div class="col-4"></div>
                            <div class="col-8">
                             <span class="err-span no-mg-top">
                                @error('team_id')
                                 {{ $message }}
                                 @enderror
                            </span>
                            </div>
                        @endif
                    </div>

                    <!-- Name input -->
                    <div class="row g-2 align-items-center mb-3 mt-3">
                        <div class="col-2 m-3">
                            <label for="name" class="col-form-label">Name</label>
                        </div>
                        <div class="col-6 m-3">
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                {{setValue('name')}}
                            />
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

                    <!-- Email input -->
                    <div class="row g-2 align-items-center mb-3 mt-3">
                        <div class="col-2 m-3">
                            <label for="email" class="col-form-label">Email</label>
                        </div>
                        <div class="col-6 m-3">
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                {{setValue('email')}}
                            />
                        </div>
                        @if($errors->has('email'))
                            <div class="col-4"></div>
                            <div class="col-8">
                             <span class="err-span no-mg-top">
                                @error('email')
                                 {{ $message }}
                                 @enderror
                            </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between row g-2 align-items-end">
                    <div class="col-auto">
                        <button type="button" onclick="resetForm()" class="reset-button btn btn-primary btn-block mb-4">
                            Reset
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Search</button>
                    </div>
                </div>
            </form>
            <script>
                function resetForm() {
                    window.location.href = "/employees/searchEmployee";
                }
            </script>
        </div>
        <div class="d-flex flex-column result-container mb-2 mt-2 p-3 border border-dark">
{{--            @if(isset($teams))--}}
{{--                <div>--}}
{{--                    {!! $teams->links("pagination::bootstrap-5") !!}--}}
{{--                </div>--}}
{{--            @endif--}}
            <div class="table-cover border border-dark">
                <table id="searchTable"
                       class="result-table table table-sortable table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="thread-column" scope="col">
                            {{--                            <a href="{{setSortHrefTeam('id',$column??'id',$direction??'asc')}}">--}}
                            ID
                            {{--                            </a>--}}
                        </th>
                        <th class="thread-column" scope="col">
                            {{--                            <a href="{{setSortHrefTeam('name',$column??'id',$direction??'asc')}}">--}}
                            Name
                            {{--                            </a>--}}
                        </th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--                    {{displayTableResult($teams)}}--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @php
        dump(session()->all());
        dump(request()->all());
        @endphp
@endsection

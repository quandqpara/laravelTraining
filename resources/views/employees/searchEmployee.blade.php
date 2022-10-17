@extends('app')
@section('title', 'Search Employee')
@include('components.hnav')

@section('content')
    <div class="h-100 w-100 flex-column mb-auto admin-home-sect">
        {{displayNotification()}}

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete This Employee?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure?
                    </div>
                    <div class="modal-footer">
                        <form method="GET">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 mb-3 search-box border border-dark">
            <form method="GET"
                  action="{{route('employee.search', ['team_id'=>'', 'name'=>'', 'email'=>'','page'=>'1','column'=>'id','direction'=>'asc'])}}"
                  class="search-form-box m-4 form-create" novalidate>
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
                            <input type="text"
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
                @if(isset($employees))
                    <div>
                        <button type="submit" class="btn-export btn btn-secondary"
                                formaction="{{route('employee.exportCSV')}}">Export CSV
                        </button>
                    </div>
                @endif
            </form>
            <script>
                function resetForm() {
                    window.location.href = "/employees/searchEmployee";
                }
            </script>
        </div>

        <div class="d-flex flex-column result-container mb-2 mt-2 p-3 border border-dark">
            @if(isset($employees))
                <div>
                    {!! $employees->links("pagination::bootstrap-5") !!}
                </div>
            @endif
            <div class="table-cover border border-dark">
                <table id="searchTable"
                       class="result-table table table-sortable table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="thread-column" scope="col">
                            @if($employees->toArray()['total'] > 0)
                            <a href="{{setSortHrefEmployee('id',$column??'id',$direction??'desc')}}"> @endif
                                ID {{showSortingArrow('id', $column??'id', $direction??'desc', $employees)}}
                            </a>
                        </th>
                        <th class="thread-column" scope="col">
                            Avatar
                        </th>
                        <th class="thread-column" scope="col">
                                 @if($employees->toArray()['total'] > 0)
                            <a href="{{setSortHrefEmployee('team_id',$column??'id',$direction??'desc')}}"> @endif
                                Team      @if($employees->toArray()['total'] > 0) {{showSortingArrow('team_id', $column??'id', $direction??'desc', $employees)}}
                            </a> @endif
                        </th>
                        <th class="thread-column" scope="col">
                                 @if($employees->toArray()['total'] > 0)
                            <a href="{{setSortHrefEmployee('name',$column??'id',$direction??'desc')}}"> @endif
                                Name      @if($employees->toArray()['total'] > 0) {{showSortingArrow('name', $column??'id', $direction??'desc', $employees)}}
                            </a> @endif
                        </th>
                        <th class="thread-column" scope="col">
                                 @if($employees->toArray()['total'] > 0)
                            <a href="{{setSortHrefEmployee('email',$column??'id',$direction??'desc')}}"> @endif
                                Email      @if($employees->toArray()['total'] > 0) {{showSortingArrow('email', $column??'id', $direction??'desc', $employees)}}
                            </a> @endif
                        </th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($employees->toArray()['total'] > 0)
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>

                                <td> <img src="{{ asset($employee->avatar) }}"> </td>

                                <td>{{ setTeamNameByID($employee->team_id, $teams) ?? config('global.NO_TEAM_EMPLOYEE')}}</td>

                                <td>{{ $employee->last_name .' '. $employee->first_name }}</td>

                                <td>{{ $employee->email }}</td>

                                <td class="col-2">
                                    <div class="btn-container">
                                        <div class="col-auto">
                                            <a class="btn btn-dark" href="{{ route('employee.editEmployee', $employee->id) }}">EDIT</a>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger del-btn" data-url="{{ route('employee.delete', $employee->id) }}"  data-bs-toggle="modal" data-bs-target="#exampleModal">DELETE</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6"><span>No Results Found!</span></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

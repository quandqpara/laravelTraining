@extends('app')
@section('title', 'Search Team')
@include('components.hnav')

@section('content')
    <div class="h-90 w-60 flex-column mb-auto admin-home-sect">
        {{displayNotification()}}

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete This Team?</h5>
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
                  action="{{route('team.search', ['name'=>'','page'=>'1','column'=>'id','direction'=>'asc'])}}"
                  class="search-form-box m-4 form-create" novalidate>

                <div class="input-form-box">
                    <!-- Name input -->
                    <div class="row g-2 align-items-center mb-3 mt-3">
                        <div class="col-2">
                            <label for="name" class="col-form-label">Name</label>
                        </div>
                        <div class="col-9">
                            <input type="text"
                                   maxlength="128"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                {{setValue('name')}}
                            />
                        </div>
                        @if($errors->has('name'))
                            <div class="col-2"></div>
                            <div class="col-9">
                             <span class="err-span no-mg-top">
                                @error('name')
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
                    window.location.href = "/teams/searchTeam";
                }
            </script>
        </div>
        <div class="d-flex flex-column result-container mb-2 mt-2 p-3 border border-dark">
            @if(isset($teams))
                <div>
                    {!! $teams->links("pagination::bootstrap-5") !!}
                </div>
            @endif
            <div class="table-cover border border-dark">
                <table id="searchTable"
                       class="result-table table table-sortable table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="thread-column" scope="col">
                            @if($teams->toArray()['total'] > 0)
                                <a href="{{setSortHrefTeam('id',$column??'id',$direction??'desc')}}"> @endif
                                    ID @if($teams->toArray()['total'] > 0){{showSortingArrow('id', $column??'id', $direction??'desc', $teams)}}
                                </a>
                            @endif
                        </th>
                        <th class="thread-column" scope="col">
                            @if($teams->toArray()['total'] > 0)
                            <a href="{{setSortHrefTeam('name',$column??'id',$direction??'desc')}}"> @endif
                                Name  @if($teams->toArray()['total'] > 0) {{showSortingArrow('name', $column??'id', $direction??'desc', $teams)}}
                            </a> @endif
                        </th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($teams->toArray()['total'] > 0)
                        @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->id }}</td>
                                <td>{{ $team->name }}</td>
                                <td class="col-2">
                                    <div class="btn-container">
                                        <div class="col-auto">
                                            <a class="btn btn-dark" href="{{ route('team.editTeam', $team->id) }}">EDIT</a>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger del-btn" data-url="{{ route('team.delete', $team->id) }}"  data-bs-toggle="modal" data-bs-target="#exampleModal">DELETE</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3"><span>No Results Found!</span></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

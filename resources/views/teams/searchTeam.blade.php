@extends('app')
@section('title', 'Search Team')
@include('components.hnav')

@section('content')
    <div class="h-100 w-100 flex-column mb-auto admin-home-sect">
        <div class="mt-3 mb-3 search-box border border-dark">
            <form method="GET" action="{{ route('team.search') }}" class=" m-4 form-create">
                <!-- Name input -->
                <div class="row g-2 align-items-center mb-3 mt-3">
                    <div class="col-auto m-3">
                        <label for="name" class="col-form-label">Name</label>
                    </div>
                    <div class="col-auto m-3">
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               value="{{--oldData--}}"
                        />
                    </div>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between row g-2 align-items-center">
                    <div class="col-auto">
                        <button type="button" onclick="resetForm()" class="reset-button btn btn-primary btn-block mb-4">Reset</button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Search</button>
                    </div>
                </div>
            </form>
            <script>
                function resetForm() {
                    window.location.href = "/management/admin/home";
                }
            </script>
        </div>
        <div class="d-flex flex-column result-container mb-2 mt-2 p-3 border border-dark">
            <div class="pagination-cover flex-row-reverse m-2">
                <nav aria-label="Page navigation example" class="page-nav">
{{--                    pagination--}}
                </nav>
            </div>
            <div class="table-cover border border-dark">
                <table id="searchTable" class="result-table table table-sortable table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="thread-column" scope="col" >
                            <a href="">
                                ID
                            </a>
                        </th>
                        <th scope="col">Avatar</th>
                        <th class="thread-column" scope="col" >
                            <a href="">
                                Name
                            </a>
                        </th>
                        <th class="thread-column" scope="col" >
                            <a href="">
                                Email
                            </a>
                        </th>
                        <th class="thread-column" scope="col" >
                            <a href="">
                                Role
                            </a>
                        </th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        {{--display result--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

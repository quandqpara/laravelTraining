@extends('app')
@section('title', 'Search Team')
@include('components.hnav')

<?php
    $sortField = $_GET['$sortField'] ?? 'id';

    $sortType = $_GET['sortType'] == 'desc' ? 'asc' : 'desc';
?>

@section('content')
    <div class="h-100 w-100 flex-column mb-auto admin-home-sect">
        @if(session()->has('success'))
            <div class="alert alert-success d-flex justify-content-center">
                <span>  {{ session()->get('success') }} </span>
                @php session()->forget('success'); @endphp
            </div>
        @endif
        <div class="mt-3 mb-3 search-box border border-dark">
            <form method="GET" action="{{route('team.search')}}" class="search-form-box m-4 form-create">
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
                               @if(session()->has('success'))
                                   value="{{""}}"
                               @else
                                   value="{{old('name') ?? ""}}"
                            @endif
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

                <!-- Buttons -->
                <div class="d-flex justify-content-between row g-2 align-items-end">
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
                <table id="searchTable" class="result-table table table-sortable table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th class="thread-column" scope="col" >
                            <a @if(!isset($idHref))
                                   href="{{route('team.search')}}"
                               @else
                                   href="{{$idHref}}"
                                @endif
                            >
                                ID
                            </a>
                        </th>
                        <th class="thread-column" scope="col" >
                            <a @if(!isset($nameHref))
                                   href="/teams/searchTeam?name={{old('name')}}&column=name&direction=asc"
                               @else
                                   href="{{$nameHref}}"
                                @endif
                            >
                                Name
                            </a>
                        </th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!isset($teams))
                            <tr>
                                <td colspan="3"><span>No Results Found!</span></td>
                            </tr>
                        @else
                            @foreach($teams as $team)
                                <tr>
                                    <td>
                                        @php echo $team['id']; @endphp
                                    </td>
                                    <td>
                                        @php echo $team['name']; @endphp
                                    </td>
                                    <td class="col-2">
                                        <div class="btn-container">
                                            <div class="col-auto">
                                                <a class="btn btn-dark" href="@php echo "/teams/editTeam/".$team['id']; @endphp">Edit</a>
                                            </div>
                                            <div class="col-auto">
                                                <a class="btn btn-danger" href="@php echo "/teams/deleteTeam/".$team['id']; @endphp">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{--    @php--}}
{{--        var_dump($teams);--}}
{{--           dump($teams->toArray());--}}
{{--           die;--}}
{{--    @endphp--}}
@endsection

@extends('layouts.main')
@section('content')

    <div class="content-w">


        <div class="content-panel-toggler">
            <i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
        </div>


        <div class="content-i">
            <div class="content-box">


                <div class="row">
                    <div class="col-sm-12 col-xxxl-12">
                        <div class="element-wrapper">

{{--                            @if ($errors->any())--}}
{{--                                <div class="alert alert-danger my-4">--}}
{{--                                    <ul>--}}
{{--                                        @foreach ($errors->all() as $error)--}}
{{--                                            <li>{{ $error }}</li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                            @if (session()->has('message'))--}}
{{--                                <div class="alert alert-success">--}}
{{--                                    {{ session()->get('message') }}--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                            @if (session()->has('error'))--}}
{{--                                <div class="alert alert-danger mt-2">--}}
{{--                                    {{ session()->get('error') }}--}}
{{--                                </div>--}}
{{--                            @endif--}}

                            <h6 class="element-header">All users</h6>
                            <div class="element-box">

                                <div class="d-flex justify-content-start col-6">

                                    <form action="search_user" method="post">
                                        @csrf
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Search"
                                                name="email"
                                                data-search-service="#service-table-59"
                                            />
                                            <span
                                                class="input-group-append component_button_search"
                                            >
                                <button
                                    class="btn btn-secondary"
                                    type="submit"
                                    data-filter-serch-btn="true"
                                >
                                  Search <i class="fa fa-search"></i>
                                </button>
                                        </span>
                                        </div>
                                    </form>
                                </div>

                                <button
                                    class="btn btn-big-secondary"
                                    type="submit"
                                    data-filter-serch-btn="true"
                                >
                                    <i class="fas fa-search"></i>
                                </button>


                                <div class="table-responsive">
                                    <table class="table table-responsive-sm" id="service-table-59">
                                        <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Wallet</th>
                                            <th class="">Status</th>
                                            <th class="">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user as $data)
                                            <tr>
                                                <td style="font-size: 12px; color: grey;">{{$data->first_name ?? "null"}}</td>
                                                <td style="font-size: 12px; color: grey;">{{$data->last_name ?? "null"}}</td>
                                                <td style="font-size: 12px; color: grey;">{{$data->email ?? "null"}}</td>
                                                <td style="font-size: 12px; color: grey;">{{$data->phone ?? "null"}}</td>
                                                <td style="font-size: 12px; color: grey;">{{number_format($data->main_wallet, 2) }}</td>
                                                @if($data->status == 2)
                                                    <td><span style="font-size: 10px"
                                                              class="badge text-center text-small text-white p-2  rounded-pill badge-success">Active</span>
                                                    </td>
                                                @elseif($data->status == 0)
                                                    <td><span style="font-size: 10px"
                                                              class="badge text-center text-small text-white p-2  rounded-pill badge-warning">Pending</span>
                                                    </td>
                                                @elseif($data->status == 3)
                                                    <td><span style="font-size: 10px"
                                                              class="badge p-2 text-small text-white rounded-pill badge-info">Suspended</span>
                                                    </td>
                                                @elseif($data->status == 4)
                                                    <td><span style="font-size: 10px"
                                                              class="badge p-2 text-small text-white rounded-pill badge-danger">Blocked</span>
                                                    </td>
                                                @endif

                                                <td>
                                                    <a href="view_user?id={{$data->id}}" class="btn btn-info">View</a>
                                                    <a href="suspend_user?id={{$data->id}}" class="btn btn-warning">Suspend</a>
                                                    <a href="delete_user?id={{$data->id}}"
                                                       class="btn btn-danger">Delete</a>

                                                </td>




                                            </tr>
                                        @empty
                                            <td>No data found</td>
                                        @endforelse


                                        </tbody>
                                    </table>
                                    {{ paginateLinks($user) }}
                                </div>


                            </div>
                        </div>
                    </div>

                </div>


                <div class="floated-colors-btn second-floated-btn">
                    <div class="os-toggler-w">
                        <div class="os-toggler-i">
                            <div class="os-toggler-pill"></div>
                        </div>
                    </div>
                    <span>Dark </span><span>Mode</span>
                </div>

            </div>

        </div>
    </div>

@endsection





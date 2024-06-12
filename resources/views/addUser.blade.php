@extends('layouts.main')
@section('content')

    <div class="content-panel-toggler">
        <i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>


    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">




                        <h6 class="element-header">New Customer</h6>
                        <div class="element-content">

                            <div class="col-sm-12 col-xxxl-12">
                                <div class="element-wrapper">
                                    <div class="element-box">


                                        @if ($errors->any())
                                            <div class="alert alert-danger my-4">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if (session()->has('message'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message') }}
                                            </div>
                                        @endif
                                        @if (session()->has('error'))
                                            <div class="alert alert-danger mt-2">
                                                {{ session()->get('error') }}
                                            </div>
                                        @endif


                                        <h6 class="element-header">Add New Customer</h6>
                                        <div class="element-box">
                                            <form action="create_new_customer" method="post">
                                                @csrf
                                                <legend><span>Personal Information</span></legend>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for=""> First Name</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                name="first_name"
                                                                type="text"
                                                                required>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for=""> Last Name</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="last_name"
                                                                required>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for=""> Select Gender</label
                                                            ><select
                                                                class="form-control"
                                                                name="gender">
                                                                <option value="m">Male
                                                                </option>
                                                                <option value="f">Female
                                                                </option>


                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <legend><span>Address Information</span></legend>

                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="">No</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="hos_no"
                                                                required>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label for="">Address</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="address_line1"
                                                                required
                                                            >

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">State</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="state"
                                                                required
                                                            >

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">City</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="city"
                                                                required>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">LGA</label
                                                            >
                                                            <input
                                                                class="form-control"
                                                                type="text"
                                                                name="lga"
                                                                required>

                                                        </div>
                                                    </div>


                                                </div>


                                                <legend><span>Login Information</span></legend>

                                                <div class="row">

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Email</label
                                                            ><input
                                                                class="form-control"
                                                                name="email"
                                                                type="email"
                                                                placeholder="Email"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Phone No</label
                                                            ><input
                                                                class="form-control"
                                                                name="phone"
                                                                type="text"
                                                                value="+234"
                                                                placeholder="Phone No"
                                                            />
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Password</label
                                                            ><input
                                                                class="form-control"
                                                                name="password"
                                                                type="password"
                                                                placeholder="password"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">PIN</label
                                                            ><input
                                                                class="form-control"
                                                                name="pin"
                                                                type="password"
                                                                placeholder="pin"
                                                            />
                                                        </div>
                                                    </div>



                                                </div>


                                                <div class="form-buttons-w">
                                                    <button class="btn btn-primary" type="submit">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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


@endsection

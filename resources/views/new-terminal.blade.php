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


                        <h6 class="element-header">New Terminal</h6>
                        <div class="element-content">

                            <div class="col-sm-12 col-xxxl-12">
                                <div class="element-wrapper">
                                    <div class="element-box">

                                        <h6 class="element-header">Add New Terminal</h6>
                                        <div class="element-box">
                                            <form action="create_new_terminal" method="post">
                                                @csrf


                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for=""> Select Customer</label
                                                            ><select
                                                                class="form-control"
                                                                name="user_id" required>
                                                                @foreach($users as $data)
                                                                    <option value="{{$data->id}}">{{$data->first_name}} {{$data->last_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for=""> Select Customer</label
                                                            ><select
                                                                class="form-control"
                                                                name="user_id">
                                                                @foreach($users as $data)
                                                                    <option value="{{$data->id}}">{{$data->first_name}} {{$data->last_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">Confirm Password</label
                                                            ><input
                                                                class="form-control"
                                                                placeholder="Password"
                                                                type="password"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for=""> Email address</label
                                                    ><input
                                                        class="form-control"
                                                        placeholder="Enter email"
                                                        type="email"
                                                    />
                                                </div>

                                                <div class="form-group">
                                                    <label for=""> Regular select</label
                                                    ><select class="form-control">
                                                        <option>Select State</option>
                                                        <option>New York</option>
                                                        <option>California</option>
                                                        <option>Boston</option>
                                                        <option>Texas</option>
                                                        <option>Colorado</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for=""> Multiselect</label
                                                    ><select class="form-control select2" multiple="true">
                                                        <option selected="true">New York</option>
                                                        <option selected="true">California</option>
                                                        <option>Boston</option>
                                                        <option>Texas</option>
                                                        <option>Colorado</option>
                                                    </select>
                                                </div>
                                                <fieldset class="form-group">
                                                    <legend><span>Section Example</span></legend>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for=""> First Name</label
                                                                ><input
                                                                    class="form-control"
                                                                    placeholder="First Name"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="">Last Name</label
                                                                ><input
                                                                    class="form-control"
                                                                    placeholder="Last Name"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for=""> Date Picker</label>
                                                                <div class="date-input">
                                                                    <input
                                                                        class="single-daterange form-control"
                                                                        placeholder="Date of birth"
                                                                        value="04/12/1978"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="">Twitter Username</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">@</div>
                                                                    </div>
                                                                    <input
                                                                        class="form-control"
                                                                        placeholder="Twitter Username"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label> Example textarea</label
                                                        ><textarea class="form-control" rows="3"></textarea>
                                                    </div>
                                                </fieldset>
                                                <div class="form-check">
                                                    <label class="form-check-label"
                                                    ><input class="form-check-input" type="checkbox" />I
                                                        agree to terms and conditions</label
                                                    >
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

            <div class="row">


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
        <div class="content-panel">
            <div class="content-panel-close">
                <i class="os-icon os-icon-close"></i>
            </div>
            <div class="element-wrapper">
                <h6 class="element-header">Quick Links</h6>
                <div class="element-box-tp">
                    <div class="el-buttons-list full-width">
                        <a class="btn btn-white btn-sm" href="#"
                        ><i class="os-icon os-icon-delivery-box-2"></i
                            ><span>Create new terminal</span></a
                        ><a class="btn btn-white btn-sm" href="index.html#"
                        ><i class="os-icon os-icon-window-content"></i
                            ><span>Create new customer</span></a
                        ><a class="btn btn-white btn-sm" href="#"
                        ><i class="os-icon os-icon-settings"></i
                            ><span>System Settings</span></a
                        >
                    </div>
                </div>
            </div>
            <div class="element-wrapper">
                <h6 class="element-header">Bank Settings</h6>
                <div class="element-box-tp">

                </div>
            </div>
            <div class="element-wrapper">
                <h6 class="element-header">Charge Settings</h6>
                <div class="element-box-tp">

                </div>
            </div>


            <div class="element-wrapper">
                <h6 class="element-header">Active Terminals</h6>
                <div class="element-box less-padding">
                    <div class="el-chart-w">
                        <canvas
                            height="120"
                            id="donutChart1"
                            width="120"
                        ></canvas>
                        <div class="inside-donut-chart-label">
                            <strong>50</strong><span>Active</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection

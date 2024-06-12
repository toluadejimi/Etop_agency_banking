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

                                            <form action="create_new_terminal" method="post">
                                                @csrf


                                                <fieldset class="form-group">
                                                    <legend><span>Customer Information</span></legend>
                                                <div class="row">
                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for=""> Select Customer</label
                                                            >

                                                            <div>
                                                                <select class="form-control select2" live-search="true" multiple="false" name="user_id">
                                                                    @foreach($users as $data)
                                                                        <option value="{{$data->id}}">{{$data->first_name}} {{$data->last_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="">Merchant Name</label
                                                            ><input
                                                                class="form-control"
                                                                placeholder="Merchant Name"
                                                                name="merchantName"
                                                                type="text"
                                                            />
                                                        </div>
                                                    </div>


                                                </div>
                                                </fieldset>


                                                <fieldset class="form-group">
                                                    <legend><span>Terminal Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">TID Number  | {{$terminalno}}</label
                                                                ><input
                                                                    class="form-control"
                                                                    placeholder="Terminal No (TID)"
                                                                    name="terminalNo"
                                                                    type="text"
                                                                />
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Serial Number</label
                                                                ><input
                                                                    class="form-control"
                                                                    placeholder="Serial No"
                                                                    name="deviceSN"
                                                                    type="text"
                                                                />
                                                            </div>
                                                        </div>





                                                    </div>
                                                </fieldset>



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

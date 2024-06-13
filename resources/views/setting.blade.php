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



                                        <h6 class="element-header">Settings</h6>
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

                                            <form action="update_setting" method="post">
                                                @csrf


                                                <fieldset class="form-group">
                                                    <legend><span>POS Charge Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for=""> POS Charge</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->pos_charge}}" name="pos_charge">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for=""> Cap </label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->cap}}" name="cap">
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                    <fieldset class="form-group">
                                                        <legend><span>Bank Transfer Charge Information</span></legend>
                                                        <div class="row">
                                                            <div class="col-xl-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="">Transfer Out Charge</label
                                                                    >
                                                                    <div>
                                                                        <input type="number" class="form-control" value="{{$setting->transfer_out_charge}}" name="transfer_out_charge">
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-xl-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="">Transfer IN Charge</label
                                                                    >
                                                                    <div>
                                                                        <input type="number" class="form-control" value="{{$setting->transfer_in_charge}}" name="transfer_in_charge">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </fieldset>


                                                </fieldset>

                                                <fieldset class="form-group">
                                                    <legend><span>Airtime Charge Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">MTN</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->mtn_airtime}}" name="mtn_airtime">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">GLO</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->glo_airtime}}" name="glo_airtime">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">AIRTEL</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->airtel_airtime}}" name="airtel_airtime">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">9 Mobile</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->mobile9_airtime}}" name="mobile9_airtime">
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                </fieldset>

                                                <fieldset class="form-group">
                                                    <legend><span>Data Charge Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">MTN</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->mtn_data}}" name="mtn_data">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">GLO</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->glo_data}}" name="glo_data">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">AIRTEL</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->airtel_data}}" name="airtel_data">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">9 Mobile</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->mobile9_airtime}}" name="mobile9_airtime">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Spectranet</label>
                                                                <input type="number" class="form-control" value="{{$setting->spectranaect}}" name="spectranaect">
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Swift</label>
                                                                <input type="number" class="form-control" value="{{$setting->swift}}" name="swift">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </fieldset>




                                                <fieldset class="form-group">
                                                    <legend><span>Electric Charge Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Electricity Charge</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->eletric_charge}}" name="eletric_charge">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </fieldset>


                                                <fieldset class="form-group">
                                                    <legend><span>Cable Charge Information</span></legend>
                                                    <div class="row">
                                                        <div class="col-xl-3 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Cable Charge</label
                                                                >
                                                                <div>
                                                                    <input type="number" class="form-control" value="{{$setting->cable_charge}}" name="cable_charge">
                                                                </div>
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

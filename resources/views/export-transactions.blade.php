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

                            <h6 class="element-header">All Transactions</h6>
                            <div class="element-box">

                                <h6 class="element-header ">Filter</h6>

                                <form action="/export-trx" method="get">
                                    @csrf

                                    <div class="row">
                                        <div class="col-6">
                                            <label>Date From</label>
                                            <input type="date" class="form-control" name="from">
                                        </div>
                                        <div class="col-6">
                                            <label>Date To</label>
                                            <input type="date" class="form-control" name="to">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary my-4 w-100">Export</button>

                                </form>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
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
    </div>

@endsection





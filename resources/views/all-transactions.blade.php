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

                            <h6 class="element-header">All Transactions</h6>
                            <div class="element-box">

                                <h6 class="element-header ">Filter</h6>

                                <form action="search-trx" method="post">
                                    @csrf

                                    <div class="row">
                                        <div class="col-3">
                                            <label>Date From</label>
                                            <input type="date" class="form-control" name="from">
                                        </div>
                                        <div class="col-3">
                                            <label>Date To</label>
                                            <input type="date" class="form-control" name="to">
                                        </div>
                                        <div class="col-3">
                                            <label>Transaction Type</label>
                                            <select class="form-control" name="transaction_type">

                                                <option value="">Select type</option>
                                                <option value="TRANSFERIN">Transfer In</option>
                                                <option value="PURCHASE">Purchase</option>
                                                <option value="TRANSFEROUT">Transfer Out</option>
                                                <option value="BILLS">Bills</option>
                                                <option value="REVERSED">Reversal</option>


                                            </select>


                                        </div>


                                        <div class="col-3">
                                            <label>Transaction Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Select type</option>
                                                <option value="0">Pending</option>
                                                <option value="2">Successful</option>
                                                <option value="3">Failed</option>
                                                <option value="4">Reversed</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="row my-3">


                                        <div class="col-4">
                                            <label>Transaction Refrence</label>
                                            <input type="text" class="form-control" name="rrn"
                                                   placeholder="Enter Transaction Refrence">

                                        </div>

                                        <div class="col-4 mt-4">
                                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                                        </div>


                                    </div>


                                </form>


                                <div class="row">
                                    <div class="col-sm-12 col-xxxl-12">
                                        <div class="element-wrapper">


                                            <div class="element-box">

                                                <div class="table-responsive">
                                                    <table class="table table-responsive-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>Tran ID</th>
                                                            <th>Customer Name</th>
                                                            <th>Amount</th>
                                                            <th>Charge</th>
                                                            <th>Balance</th>
                                                            <th>Type</th>
                                                            <th class="">Status</th>
                                                            <th class="">Date</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($all_transactions as $data)
                                                            <td style="font-size: 12px; color: grey;"><a href="open-trx?id={{$data->ref_trans_id}}">{{$data->ref_trans_id}}</a></td>
                                                            <td style="font-size: 12px; color: grey;">{{$data->user->first_name ?? "name"}} {{$data->user->last_name ??
                                                                "name"}}</td>
                                                            @if($data->credit == 0)
                                                                <td style="font-size: 12px;" class="text-danger">
                                                                    ₦{{number_format($data->debit, 2)}}</td>
                                                            @else
                                                                <td style="font-size: 12px; " class="text-success">
                                                                    ₦{{number_format($data->credit, 2)}}</td>
                                                            @endif

                                                            <td style="font-size: 12px; color: black;">{{$data->charge}}</td>

                                                            <td style="font-size: 12px; color: grey;" class="">
                                                                ₦{{number_format($data->balance, 2)}}</td>
                                                            @if($data->transaction_type == "PURCHASE")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge text-small text-white p-2  rounded-pill badge-info">PURCHASE</span>
                                                                </td>
                                                            @elseif($data->transaction_type == "CASHIN")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-success">CASH-IN</span>
                                                                </td>
                                                            @elseif($data->transaction_type == "BANKTRANSFER")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-danger">BANK - TRANSFER</span>
                                                                </td>
                                                            @elseif($data->transaction_type == "BILLS")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-success">BILLS</span>
                                                                </td>
                                                            @elseif($data->transaction_type == "TRANSFERIN")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-success">TRANSFER IN</span>
                                                                </td>
                                                            @elseif($data->transaction_type == "TRANSFEROUT")
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-danger">TRANSFER OUT</span>
                                                                </td>
                                                            @endif

                                                            @if($data->status == 2)
                                                                <td><span style="font-size: 10px"
                                                                          class="badge text-center text-small text-white p-2  rounded-pill badge-success">Success</span>
                                                                </td>
                                                            @elseif($data->status == 0)
                                                                <td><span style="font-size: 10px"
                                                                          class="badge text-center text-small  p-2  rounded-pill badge-warning">Pending</span>
                                                                </td>

                                                                <td>
                                                                    <a href="/admin/reverse?ref={{$data->ref_trans_id}}">
                                                                        <span style="font-size: 10px"
                                                                              class="badge text-center text-small text-white p-2  rounded-pill badge-secondary">Reverse</span></a>
                                                                </td>
                                                            @elseif($data->status == 3)
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-info">Reversed</span>
                                                                </td>
                                                            @elseif($data->status == 4)
                                                                <td><span style="font-size: 10px"
                                                                          class="badge p-2 text-small text-white rounded-pill badge-danger">Failed</span>
                                                                </td>
                                                            @endif

                                                            <td style="font-size: 12px; color: grey;">{{$data->created_at}}</td>


                                                            </tr>
                                                        @empty
                                                            No data found
                                                        @endforelse


                                                        </tbody>
                                                    </table>
                                                    {{ paginateLinks($all_transactions) }}
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





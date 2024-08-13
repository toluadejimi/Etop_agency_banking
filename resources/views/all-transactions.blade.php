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
                                                            <th>Etop Charge</th>
                                                            <th>Balance</th>
                                                            <th>Type</th>
                                                            <th class="">Status</th>
                                                            <th class="">Date</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($all_transactions as $data)

                                                            <tr>

                                                                <div class="modal fade"
                                                                     id="exampleModal{{$data->ref_trans_id}}"
                                                                     tabindex="-1" role="dialog"
                                                                     aria-labelledby="exampleModalLabel"
                                                                     aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="exampleModalLabel">Transaction
                                                                                    Details</h5>
                                                                                <div>
                                                                                    @if($data->transaction_type == "PURCHASE")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge text-small text-white p-2  rounded-pill badge-info">PURCHASE</span>

                                                                                    @elseif($data->transaction_type == "CASHIN")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge p-2 text-small text-white rounded-pill badge-success">CASH-IN</span>

                                                                                    @elseif($data->transaction_type == "BANKTRANSFER")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge p-2 text-small text-white rounded-pill badge-danger">BANK - TRANSFER</span>

                                                                                    @elseif($data->transaction_type == "BILLS")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge p-2 text-small text-white rounded-pill badge-success">BILLS</span>

                                                                                    @elseif($data->transaction_type == "TRANSFERIN")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge p-2 text-small text-white rounded-pill badge-success">TRANSFER IN</span>

                                                                                    @elseif($data->transaction_type == "TRANSFEROUT")
                                                                                        <span style="font-size: 10px"
                                                                                              class="badge p-2 text-small text-white rounded-pill badge-danger">TRANSFER OUT</span>

                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <div class="element-content">
                                                                                    <div class="row">
                                                                                        <div
                                                                                            class="col-sm-6 col-xxxl-6">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Transaction
                                                                                                    Refrence</h6>
                                                                                                <p>{{$data->ref_trans_id}}</p>
                                                                                            </a>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-sm-6 col-xxxl-6">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Transaction
                                                                                                    Amount</h6>
                                                                                                @if($data->credit == 0)
                                                                                                    <p style="font-size: 12px;"
                                                                                                       class="text-danger">
                                                                                                        ₦{{number_format($data->debit, 2)}}</p>
                                                                                                @else
                                                                                                    <p style="font-size: 12px; "
                                                                                                       class="text-success">
                                                                                                        ₦{{number_format($data->credit, 2)}}</p>
                                                                                                @endif
                                                                                            </a>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-sm-6 col-xxxl-6">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Transaction
                                                                                                    Charge</h6>
                                                                                                <p>
                                                                                                    ₦{{number_format($data->charge, 2)}}</p>
                                                                                            </a>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-sm-6 col-xxxl-6">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Transaction
                                                                                                    Balance</h6>
                                                                                                <p>
                                                                                                    ₦{{number_format($data->balance, 2)}}</p>
                                                                                            </a>
                                                                                        </div>


                                                                                        <div
                                                                                            class="col-sm-12 col-xxxl-12">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Customer Name</h6>
                                                                                                <p> {{$data->user->first_name ?? "name"}} {{$data->user->last_name ?? "name"}}
                                                                                                </p>

                                                                                            </a>
                                                                                        </div>


                                                                                        <div
                                                                                            class="col-sm-12 col-xxxl-12">
                                                                                            <a class="element-box el-tablo"
                                                                                               href="#">
                                                                                                <h6>Transaction
                                                                                                    Note</h6>
                                                                                                <p> {{$data->note }} </p>


                                                                                            </a>
                                                                                        </div>


                                                                                    </div>
                                                                                </div>


                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-dismiss="modal">Close
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <td style="font-size: 12px; color: grey;">
                                                                    <a href="#" data-toggle="modal"
                                                                       data-target="#exampleModal{{$data->ref_trans_id}}">{{$data->ref_trans_id}}</a>
                                                                </td>

                                                                <td style="font-size: 12px; color: grey;">{{$data->user->first_name ?? "name"}} {{$data->user->last_name ??
                                                                "name"}}</td>
                                                                @if($data->credit == 0)
                                                                    <td style="font-size: 12px;" class="text-danger">
                                                                        ₦{{number_format($data->debit, 2)}}</td>
                                                                @else
                                                                    <td style="font-size: 12px; " class="text-success">
                                                                        ₦{{number_format($data->credit, 2)}}</td>
                                                                @endif

                                                                @if($data->transaction_type == "TRANSFERIN")
                                                                <td style="font-size: 12px; color: black;"> {{number_format($data->charge, 1)}}</td>
                                                                <td style="font-size: 12px; color: black;">{{number_format($data->etop_charge, 1)}}</td>
                                                                @elseif($data->transaction_type == "TRANSFEROUT")
                                                                    @php $res = $data->charge - 10.75 @endphp
                                                                    <td style="font-size: 12px; color: black;"> {{number_format($data->charge, 1)}}</td>
                                                                    <td style="font-size: 12px; color: black;">{{number_format($res, 1)}}</td>
                                                                @else
                                                                    <td style="font-size: 12px; color: black;">{{number_format($data->etop_charge, 1)}}</td>
                                                                    <td style="font-size: 12px; color: black;">{{number_format($data->charge, 1)}}</td>
                                                                @endif

                                                                @if
                                                                @endif


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
                                                    <div class="row my-5">

                                                        <div class="col d-flex justify-content-start">
                                                            <span class="text-primary"> Total: NGN {{number_format($total, 2) ?? 0 }} </span>
                                                        </div>

                                                        <div class="col d-flex justify-content-end">
                                                            <span class="text-primary"> Total: NGN {{number_format($profit, 2) ?? 0 }} </span>
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





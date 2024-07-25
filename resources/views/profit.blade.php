@extends('layouts.main')
@section('content')

    <div class="content-w" style="margin-bottom: 300px">


        <div class="content-panel-toggler">
            <i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
        </div>


        <div class="content-i">
            <div class="content-box">


                <div class="row">
                    <div class="col-sm-12 col-xxxl-12">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">

                                    <h6 class="element-header">Profit Dashboard</h6>
                                    <div class="element-content">
                                        <div class="row">
                                            <div class="col-sm-6 col-xxxl-3">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Total Transactions</div>
                                                    <div class="value">{{number_format($total_profit, 2)}}</div>

                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="element-wrapper">

                            <h6 class="element-header">Latest Transaction</h6>
                            <div class="element-box">

                                <div class="table-responsive">
                                    <table class="table table-responsive-sm" id="service-table-59">
                                        <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Bank Account</th>
                                            <th>Status</th>
                                            <th>Date</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($profit as $data)
                                            <tr>
                                                <td style="font-size: 12px; color: grey;">{{$data->trx_id}}</td>
                                                <td style="font-size: 12px; color: grey;">{{number_format($data->amount, 2) }}</td>
                                                @if($data->status == 2)
                                                    <td><span style="font-size: 10px"
                                                              class="badge text-center text-small text-white p-2  rounded-pill badge-success">Successful</span>
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
                                                <td style="font-size: 12px; color: grey;">{{$data->created_at}}</td>
                                                </td>


                                            </tr>
                                        @empty
                                            <td>No data found</td>
                                        @endforelse


                                        </tbody>
                                    </table>
                                    {{ paginateLinks($profit) }}
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
                            <a class="btn btn-white btn-sm" href="#" data-toggle="modal" data-target="#exampleModal"
                            ><i class="os-icon os-icon-delivery-box-2"></i
                                ><span>Add Profit</span></a
                            >
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Profit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form action="add-profit" method="post">
                            @csrf

                            <label>Enter Amount</label>
                            <input class="form-control" type="number" required name="amount">

                            <button type="submit" class="btn btn-primary my-4"> Add Profit</button>

                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>


                </div>
            </div>
        </div>


    </div>

@endsection





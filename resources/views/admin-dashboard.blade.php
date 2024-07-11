@extends('layouts.main')
@section('content')

    <div class="content-w">


        <div class="content-panel-toggler">
            <i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
        </div>


        <div class="content-i">
            <div class="content-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="element-wrapper">
                            <div class="element-actions">
                                <form class="form-inline justify-content-sm-end">
                                    <select id="selectPage" class="form-control form-control-sm">
                                        <option value="?date=all">All</option>
                                        <option value="?date=today">Today</option>
                                        <option value="?date=yesterday">Yesterday</option>
                                        <option value="Cancelled">Last 30 Days</option>
                                    </select>

                                    <script>
                                        document.getElementById('selectPage').addEventListener('change', function () {
                                            var selectedPage = this.value;
                                            if (selectedPage) {
                                                window.location.href = '{{url('')}}/admin/admin-dashboard' + selectedPage; // Replace '/' with your base URL
                                            }
                                        });
                                    </script>
                                </form>
                            </div>


                            <h6 class="element-header">Admin Dashboard</h6>
                            <div class="element-content">
                                <div class="row">
                                    <div class="col-sm-4 col-xxxl-3">
                                        <a class="element-box el-tablo" href="/admin/all-terminals">
                                            <div class="label">Total Terminal</div>
                                            <div class="value">{{number_format($all_terminals, 2)}}</div>

                                        </a>
                                    </div>

                                    <div class="col-sm-4 col-xxxl-3">
                                        <a class="element-box el-tablo" href="/admin/all-users">
                                            <div class="label">Total Customers</div>
                                            <div class="value">{{number_format($all_customers, 2)}}</div>

                                        </a>
                                    </div>

                                    <div class="col-sm-12 col-xxxl-6">
                                        <a class="element-box el-tablo" href="#">
                                            <div class="label">Total in Wallet</div>
                                            <div class="text-success value">₦ {{number_format($total_wallet, 2)}}</div>

                                        </a>
                                    </div>


                                    <div class="col-sm-12 col-xxxl-6">
                                        <a class="element-box el-tablo" href="/all-transaction">
                                            <div class="label">Total Inflow</div>
                                            <div class="value">₦ {{number_format($inflow, 2)}}</div>

                                        </a>
                                    </div>

                                    <div class="col-sm-12 col-xxxl-6">
                                        <a class="element-box el-tablo" href="/all-transaction">
                                            <div class="label">Total Outflow</div>
                                            <div class="text-danger value">₦ {{number_format($outflow, 2)}}</div>

                                        </a>
                                    </div>



                                </div>
                            </div>


                            <h6 class="element-header mt-3">Margin</h6>
                            <div class="element-content">
                                <div class="row">

                                    <div class="col-sm-12 col-xxxl-6">
                                        <a class="element-box el-tablo" href="#">
                                            <div class="label">9PSB Balance</div>
                                            <div class="value">₦ {{number_format($ninepsb_wallet_balance, 2)}}</div>

                                        </a>
                                    </div>


                                    <div class="col-sm-12 col-xxxl-6">

                                        @php

                                          $profit =    $ninepsb_wallet_balance - $total_wallet ;

                                            @endphp


                                        <a class="element-box el-tablo" href="/admin/all-terminals">
                                            <div class="label">Profit</div>
                                            <div class="value">₦ {{number_format($profit, 2)}}</div>

                                        </a>
                                    </div>


                                    <div class="col-sm-12 col-xxxl-6">

                                        <a class="element-box el-tablo" href="/admin/all-terminals">
                                            <div class="label" >Total Pending</div>
                                            <div  class="value">₦ {{number_format($pending, 2)}}</div>

                                        </a>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xxxl-12">
                        <div class="element-wrapper">
                            <h6 class="element-header">Latest Transactions</h6>
                            <div class="element-box">


                                <div class="table-responsive">
                                    <table class="table table-responsive-sm">
                                        <thead>
                                        <tr>
                                            <th>Tran ID</th>
                                            <th>Customer Name</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Type</th>
                                            <th class="">Status</th>
                                            <th class="">Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($all_transactions as $data)
                                                <a href="open-trx?id={{$data->ref_trans_id}}"><td style="font-size: 12px; color: grey;">{{$data->ref_trans_id}}</td></a>
                                                <td style="font-size: 12px; color: grey;">{{$data->user->first_name ?? "name"}} {{$data->user->last_name ??
                                            "name"}}</td>
                                                @if($data->credit == 0)
                                                    <td style="font-size: 12px;" class="text-danger">
                                                        ₦{{number_format($data->debit, 2)}}</td>
                                                @else
                                                    <td style="font-size: 12px; " class="text-success">
                                                        ₦{{number_format($data->credit, 2)}}</td>
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

                                                    <td><a href="/admin/reverse?ref={{$data->ref_trans_id}}"> <span style="font-size: 10px"  class="badge text-center text-small text-white p-2  rounded-pill badge-secondary">Reverse</span></a>
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

                <div class="floated-colors-btn second-floated-btn">
                    <div class="os-toggler-w">
                        <div class="os-toggler-i">
                            <div class="os-toggler-pill"></div>
                        </div>
                    </div>
                    <span>Dark </span><span>Mode</span>
                </div>
                {{--                    <div class="floated-customizer-btn third-floated-btn">--}}
                {{--                        <div class="icon-w"><i class="os-icon os-icon-ui-46"></i></div>--}}
                {{--                        <span>Customizer</span>--}}
                {{--                    </div>--}}
                {{--                    <div class="floated-customizer-panel">--}}
                {{--                        <div class="fcp-content">--}}
                {{--                            <div class="close-customizer-btn">--}}
                {{--                                <i class="os-icon os-icon-x"></i>--}}
                {{--                            </div>--}}
                {{--                            <div class="fcp-group">--}}
                {{--                                <div class="fcp-group-header">Menu Settings</div>--}}
                {{--                                <div class="fcp-group-contents">--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Menu Position</label--}}
                {{--                                        ><select class="menu-position-selector">--}}
                {{--                                            <option value="left">Left</option>--}}
                {{--                                            <option value="right">Right</option>--}}
                {{--                                            <option value="top">Top</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Menu Style</label--}}
                {{--                                        ><select class="menu-layout-selector">--}}
                {{--                                            <option value="compact">Compact</option>--}}
                {{--                                            <option value="full">Full</option>--}}
                {{--                                            <option value="mini">Mini</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field with-image-selector-w">--}}
                {{--                                        <label for="">With Image</label--}}
                {{--                                        ><select class="with-image-selector">--}}
                {{--                                            <option value="yes">Yes</option>--}}
                {{--                                            <option value="no">No</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Menu Color</label>--}}
                {{--                                        <div class="fcp-colors menu-color-selector">--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector menu-color-selector color-bright selected"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector menu-color-selector color-dark"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector menu-color-selector color-light"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector menu-color-selector color-transparent"--}}
                {{--                                            ></div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="fcp-group">--}}
                {{--                                <div class="fcp-group-header">Sub Menu</div>--}}
                {{--                                <div class="fcp-group-contents">--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Sub Menu Style</label--}}
                {{--                                        ><select class="sub-menu-style-selector">--}}
                {{--                                            <option value="flyout">Flyout</option>--}}
                {{--                                            <option value="inside">Inside/Click</option>--}}
                {{--                                            <option value="over">Over</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Sub Menu Color</label>--}}
                {{--                                        <div class="fcp-colors">--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector sub-menu-color-selector color-bright selected"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector sub-menu-color-selector color-dark"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector sub-menu-color-selector color-light"--}}
                {{--                                            ></div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="fcp-group">--}}
                {{--                                <div class="fcp-group-header">Other Settings</div>--}}
                {{--                                <div class="fcp-group-contents">--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Full Screen?</label--}}
                {{--                                        ><select class="full-screen-selector">--}}
                {{--                                            <option value="yes">Yes</option>--}}
                {{--                                            <option value="no">No</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Show Top Bar</label--}}
                {{--                                        ><select class="top-bar-visibility-selector">--}}
                {{--                                            <option value="yes">Yes</option>--}}
                {{--                                            <option value="no">No</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Above Menu?</label--}}
                {{--                                        ><select class="top-bar-above-menu-selector">--}}
                {{--                                            <option value="yes">Yes</option>--}}
                {{--                                            <option value="no">No</option>--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="fcp-field">--}}
                {{--                                        <label for="">Top Bar Color</label>--}}
                {{--                                        <div class="fcp-colors">--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector top-bar-color-selector color-bright selected"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector top-bar-color-selector color-dark"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector top-bar-color-selector color-light"--}}
                {{--                                            ></div>--}}
                {{--                                            <div--}}
                {{--                                                class="color-selector top-bar-color-selector color-transparent"--}}
                {{--                                            ></div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="floated-chat-btn">--}}
                {{--                        <i class="os-icon os-icon-mail-07"></i><span>Demo Chat</span>--}}
                {{--                    </div>--}}
                {{--                    <div class="floated-chat-w">--}}
                {{--                        <div class="floated-chat-i">--}}
                {{--                            <div class="chat-close">--}}
                {{--                                <i class="os-icon os-icon-close"></i>--}}
                {{--                            </div>--}}
                {{--                            <div class="chat-head">--}}
                {{--                                <div class="user-w with-status status-green">--}}
                {{--                                    <div class="user-avatar-w">--}}
                {{--                                        <div class="user-avatar">--}}
                {{--                                            <img alt="" src="img/avatar1.jpg" />--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="user-name">--}}
                {{--                                        <h6 class="user-title">John Mayers</h6>--}}
                {{--                                        <div class="user-role">Account Manager</div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="chat-messages">--}}
                {{--                                <div class="message">--}}
                {{--                                    <div class="message-content">Hi, how can I help you?</div>--}}
                {{--                                </div>--}}
                {{--                                <div class="date-break">Mon 10:20am</div>--}}
                {{--                                <div class="message">--}}
                {{--                                    <div class="message-content">--}}
                {{--                                        Hi, my name is Mike, I will be happy to assist you--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="message self">--}}
                {{--                                    <div class="message-content">--}}
                {{--                                        Hi, I tried ordering this product and it keeps showing--}}
                {{--                                        me error code.--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="chat-controls">--}}
                {{--                                <input--}}
                {{--                                    class="message-input"--}}
                {{--                                    placeholder="Type your message here..."--}}
                {{--                                />--}}
                {{--                                <div class="chat-extra">--}}
                {{--                                    <a href="index.html#"--}}
                {{--                                    ><span class="extra-tooltip">Attach Document</span--}}
                {{--                                        ><i class="os-icon os-icon-documents-07"></i></a--}}
                {{--                                    ><a href="index.html#"--}}
                {{--                                    ><span class="extra-tooltip">Insert Photo</span--}}
                {{--                                        ><i class="os-icon os-icon-others-29"></i></a--}}
                {{--                                    ><a href="index.html#"--}}
                {{--                                    ><span class="extra-tooltip">Upload Video</span--}}
                {{--                                        ><i class="os-icon os-icon-ui-51"></i--}}
                {{--                                        ></a>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
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





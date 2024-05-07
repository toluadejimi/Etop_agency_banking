
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Dashboard HTML Template</title>
        <meta charset="utf-8"/>
        <meta content="ie=edge" http-equiv="x-ua-compatible"/>
        <meta content="template language" name="keywords"/>
        <meta content="Tamerlan Soziev" name="author"/>
        <meta content="Admin dashboard html template" name="description"/>
        <meta content="width=device-width,initial-scale=1" name="viewport"/>
        <link href="favicon.png" rel="shortcut icon"/>
        <link href="apple-touch-icon.png" rel="apple-touch-icon"/>
        <link
            href="http://fast.fonts.net/cssapi/487b73f1-c2d1-43db-8526-db577e4c822b.css"
            rel="stylesheet"
        />
        <link
            href="{{url('')}}/public/assets/bower_components/select2/dist/css/select2.min.css"
            rel="stylesheet"
        />
        <link
            href="{{url('')}}/public/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css"
            rel="stylesheet"
        />
        <link href="{{url('')}}/public/assets/bower_components/dropzone/dist/dropzone.css" rel="stylesheet"/>
        <link
            href="{{url('')}}/public/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"
            rel="stylesheet"
        />
        <link
            href="{{url('')}}/public/assets/bower_components/fullcalendar/dist/fullcalendar.min.css"
            rel="stylesheet"
        />
        <link
            href="{{url('')}}/public/assets/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css"
            rel="stylesheet"
        />
        <link
            href="{{url('')}}/public/assets/bower_components/slick-carousel/slick/slick.css"
            rel="stylesheet"
        />
        <link href="{{url('')}}/public/assets/css/main.css%3Fversion=4.5.0.css" rel="stylesheet"/>
    </head>
    <body class="auth-wrapper">



    <div class="all-wrapper menu-side with-pattern">





        <div class="auth-box-w">

            <div class="logo-w">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="/"><img alt="" src="{{url('')}}/public/assets/img/logo.svg" height="80" width="200"/></a>
            </div>


            @if($data['user']->loginSecurity == null)
                <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Generate Secret Key to Enable 2FA
                        </button>
                    </div>
                </form>
            @elseif(!$data['user']->loginSecurity->google2fa_enable)
                1. Scan this QR code with your Google Authenticator App. Alternatively, you can use the code: <code>{{ $data['secret'] }}</code><br/>
                <img src="{{$data['google2fa_url'] }}" alt="">
                <br/><br/>
                2. Enter the pin from Google Authenticator app:<br/><br/>
                <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                        <label for="secret" class="control-label">Authenticator Code</label>
                        <input id="secret" type="password" class="form-control col-md-4" name="secret" required>
                        @if ($errors->has('verify-code'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('verify-code') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Enable 2FA
                    </button>
                </form>
            @elseif($data['user']->loginSecurity->google2fa_enable)
                <div class="alert alert-success">
                    2FA is currently <strong>enabled</strong> on your account.
                </div>
                <p>If you are looking to disable Two Factor Authentication. Please confirm your password and Click Disable 2FA Button.</p>
                <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                        <label for="change-password" class="control-label">Current Password</label>
                        <input id="current-password" type="password" class="form-control col-md-4" name="current-password" required>
                        @if ($errors->has('current-password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary ">Disable 2FA</button>
                </form>
            @endif




            <h4 class="auth-header">2fa Set Up</h4>

        </div>
    </div>
    </body>
    </html>







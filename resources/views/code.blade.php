<!DOCTYPE html>
<html>
<head>
    <title>ETOP | TMS</title>
    <meta charset="utf-8" />
    <meta content="ie=edge" http-equiv="x-ua-compatible" />
    <meta content="template language" name="keywords" />
    <meta content="TMS" name="author" />
    <meta content="Terminal Management System" name="description" />
    <meta content="width=device-width,initial-scale=1" name="viewport" />
    <link href="favicon.png" rel="shortcut icon" />
    <link href="apple-touch-icon.png" rel="apple-touch-icon" />
    <link
        href="http://fast.fonts.net/cssapi/487b73f1-c2d1-43db-8526-db577e4c822b.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/assets/bower_components/select2/dist/css/select2.min.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css"
        rel="stylesheet"
    />
    <link href="{{url('')}}/assets/bower_components/dropzone/dist/dropzone.css" rel="stylesheet"/>
    <link
        href="{{url('')}}/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/assets/bower_components/fullcalendar/dist/fullcalendar.min.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/assets/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/assets/bower_components/slick-carousel/slick/slick.css"
        rel="stylesheet"
    />
    <link href="{{url('')}}/assets/css/main.css%3Fversion=4.5.0.css" rel="stylesheet"/>
</head>
<body class="auth-wrapper">



<div class="all-wrapper menu-side with-pattern">





    <div class="auth-box-w">


        <div class="logo-w">
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

            <a href="/"><img alt="" src="{{url('')}}/assets/img/logo.svg" height="80" width="200"/></a>
        </div>




        <h4 class="auth-header">OTP CODE</h4>
        <form action="verify_code" method="POST">
            @csrf

            <div class="form-group">
                <label for="">OTP CODE</label
                ><input type="number" name="code" required class="form-control" placeholder="Enter OTP COde  "/>
                <div class="pre-icon os-icon os-icon-user-male-circle"></div>
            </div>

            <div class="buttons-w">
                <button class="btn btn-primary">Verify Code</button>
            </div>

            <div class="my-4">
                <a href="resend_code">Resend Code</a>
            </div>


        </form>



    </div>
</div>
</body>
</html>

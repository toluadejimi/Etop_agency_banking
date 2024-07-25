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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
          integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
          crossorigin="anonymous"/>


    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">


    <link
        href="{{url('')}}/public/assets/bower_components/select2/dist/css/select2.min.css"
        rel="stylesheet"
    />
    <link
        href="{{url('')}}/public/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css"
        rel="stylesheet"
    />
    <link href="{{url('')}}/public/assets/bower_components/dropzone/dist/dropzone.css" rel="stylesheet" />
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
<body
    class="menu-position-side menu-side-left full-screen with-content-panel"
>
<div class="all-wrapper with-side-panel solid-bg-all">
{{--    <div--}}
{{--        aria-hidden="true"--}}
{{--        class="onboarding-modal modal fade animated show-on-load"--}}
{{--        role="dialog"--}}
{{--        tabindex="-1"--}}
{{--    >--}}
{{--        <div class="modal-dialog modal-centered" role="document">--}}
{{--            <div class="modal-content text-center">--}}
{{--                <button--}}
{{--                    aria-label="Close"--}}
{{--                    class="close"--}}
{{--                    data-dismiss="modal"--}}
{{--                    type="button"--}}
{{--                >--}}
{{--              <span class="close-label">Skip Intro</span--}}
{{--              ><span class="os-icon os-icon-close"></span>--}}
{{--                </button>--}}
{{--                <div class="onboarding-slider-w">--}}
{{--                    <div class="onboarding-slide">--}}
{{--                        <div class="onboarding-media">--}}
{{--                            <img alt="" src="img/bigicon2.png" width="200px" />--}}
{{--                        </div>--}}
{{--                        <div class="onboarding-content with-gradient">--}}
{{--                            <h4 class="onboarding-title">--}}
{{--                                Example of onboarding screen!--}}
{{--                            </h4>--}}
{{--                            <div class="onboarding-text">--}}
{{--                                This is an example of a multistep onboarding screen, you can--}}
{{--                                use it to introduce your customers to your app, or collect--}}
{{--                                additional information from them before they start using--}}
{{--                                your app.--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="onboarding-slide">--}}
{{--                        <div class="onboarding-media">--}}
{{--                            <img alt="" src="img/bigicon5.png" width="200px" />--}}
{{--                        </div>--}}
{{--                        <div class="onboarding-content with-gradient">--}}
{{--                            <h4 class="onboarding-title">Example Request Information</h4>--}}
{{--                            <div class="onboarding-text">--}}
{{--                                In this example you can see a form where you can request--}}
{{--                                some additional information from the customer when they land--}}
{{--                                on the app page.--}}
{{--                            </div>--}}
{{--                            <form>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-sm-6">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="">Your Full Name</label--}}
{{--                                            ><input--}}
{{--                                                class="form-control"--}}
{{--                                                placeholder="Enter your full name..."--}}
{{--                                                value=""--}}
{{--                                            />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-6">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="">Your Role</label--}}
{{--                                            ><select class="form-control">--}}
{{--                                                <option>Web Developer</option>--}}
{{--                                                <option>Business Owner</option>--}}
{{--                                                <option>Other</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="onboarding-slide">--}}
{{--                        <div class="onboarding-media">--}}
{{--                            <img alt="" src="img/bigicon6.png" width="200px" />--}}
{{--                        </div>--}}
{{--                        <div class="onboarding-content with-gradient">--}}
{{--                            <h4 class="onboarding-title">Showcase App Features</h4>--}}
{{--                            <div class="onboarding-text">--}}
{{--                                In this example you can showcase some of the features of--}}
{{--                                your application, it is very handy to make new users aware--}}
{{--                                of your hidden features. You can use boostrap columns to--}}
{{--                                split them up.--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-sm-6">--}}
{{--                                    <ul class="features-list">--}}
{{--                                        <li>Fully Responsive design</li>--}}
{{--                                        <li>Pre-built app layouts</li>--}}
{{--                                        <li>Incredible Flexibility</li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-6">--}}
{{--                                    <ul class="features-list">--}}
{{--                                        <li>Boxed & Full Layouts</li>--}}
{{--                                        <li>Based on Bootstrap 4</li>--}}
{{--                                        <li>Developer Friendly</li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="search-with-suggestions-w">--}}
{{--        <div class="search-with-suggestions-modal">--}}
{{--            <div class="element-search">--}}
{{--                <input--}}
{{--                    class="search-suggest-input"--}}
{{--                    placeholder="Start typing to search..."--}}
{{--                />--}}
{{--                <div class="close-search-suggestions">--}}
{{--                    <i class="os-icon os-icon-x"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="search-suggestions-group">--}}
{{--                <div class="ssg-header">--}}
{{--                    <div class="ssg-icon">--}}
{{--                        <div class="os-icon os-icon-box"></div>--}}
{{--                    </div>--}}
{{--                    <div class="ssg-name">Projects</div>--}}
{{--                    <div class="ssg-info">24 Total</div>--}}
{{--                </div>--}}
{{--                <div class="ssg-content">--}}
{{--                    <div class="ssg-items ssg-items-boxed">--}}
{{--                        <a class="ssg-item" href="users_profile_big.html"--}}
{{--                        ><div--}}
{{--                                class="item-media"--}}
{{--                                style="background-image: url(img/company6.png)"--}}
{{--                            ></div>--}}
{{--                            <div class="item-name">--}}
{{--                                Integ<span>ration</span> with API--}}
{{--                            </div></a--}}
{{--                        ><a class="ssg-item" href="users_profile_big.html"--}}
{{--                        ><div--}}
{{--                                class="item-media"--}}
{{--                                style="background-image: url(img/company7.png)"--}}
{{--                            ></div>--}}
{{--                            <div class="item-name">--}}
{{--                                Deve<span>lopm</span>ent Project--}}
{{--                            </div></a--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="search-suggestions-group">--}}
{{--                <div class="ssg-header">--}}
{{--                    <div class="ssg-icon">--}}
{{--                        <div class="os-icon os-icon-users"></div>--}}
{{--                    </div>--}}
{{--                    <div class="ssg-name">Customers</div>--}}
{{--                    <div class="ssg-info">12 Total</div>--}}
{{--                </div>--}}
{{--                <div class="ssg-content">--}}
{{--                    <div class="ssg-items ssg-items-list">--}}
{{--                        <a class="ssg-item" href="users_profile_big.html"--}}
{{--                        ><div--}}
{{--                                class="item-media"--}}
{{--                                style="background-image: url(img/avatar1.jpg)"--}}
{{--                            ></div>--}}
{{--                            <div class="item-name">John Ma<span>yer</span>s</div></a--}}
{{--                        ><a class="ssg-item" href="users_profile_big.html"--}}
{{--                        ><div--}}
{{--                                class="item-media"--}}
{{--                                style="background-image: url(img/avatar2.jpg)"--}}
{{--                            ></div>--}}
{{--                            <div class="item-name">Th<span>omas</span> Mullier</div></a--}}
{{--                        ><a class="ssg-item" href="users_profile_big.html"--}}
{{--                        ><div--}}
{{--                                class="item-media"--}}
{{--                                style="background-image: url(img/avatar3.jpg)"--}}
{{--                            ></div>--}}
{{--                            <div class="item-name">Kim C<span>olli</span>ns</div></a--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="search-suggestions-group">--}}
{{--                <div class="ssg-header">--}}
{{--                    <div class="ssg-icon">--}}
{{--                        <div class="os-icon os-icon-folder"></div>--}}
{{--                    </div>--}}
{{--                    <div class="ssg-name">Files</div>--}}
{{--                    <div class="ssg-info">17 Total</div>--}}
{{--                </div>--}}
{{--                <div class="ssg-content">--}}
{{--                    <div class="ssg-items ssg-items-blocks">--}}
{{--                        <a class="ssg-item" href="index.html#"--}}
{{--                        ><div class="item-icon">--}}
{{--                                <i class="os-icon os-icon-file-text"></i>--}}
{{--                            </div>--}}
{{--                            <div class="item-name">Work<span>Not</span>e.txt</div></a--}}
{{--                        ><a class="ssg-item" href="index.html#"--}}
{{--                        ><div class="item-icon">--}}
{{--                                <i class="os-icon os-icon-film"></i>--}}
{{--                            </div>--}}
{{--                            <div class="item-name">V<span>ideo</span>.avi</div></a--}}
{{--                        ><a class="ssg-item" href="index.html#"--}}
{{--                        ><div class="item-icon">--}}
{{--                                <i class="os-icon os-icon-database"></i>--}}
{{--                            </div>--}}
{{--                            <div class="item-name">User<span>Tabl</span>e.sql</div></a--}}
{{--                        ><a class="ssg-item" href="index.html#"--}}
{{--                        ><div class="item-icon">--}}
{{--                                <i class="os-icon os-icon-image"></i>--}}
{{--                            </div>--}}
{{--                            <div class="item-name">wed<span>din</span>g.jpg</div></a--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                    <div class="ssg-nothing-found">--}}
{{--                        <div class="icon-w">--}}
{{--                            <i class="os-icon os-icon-eye-off"></i>--}}
{{--                        </div>--}}
{{--                        <span>No files were found. Try changing your query...</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



    <div class="layout-w">
        <div class="menu-mobile menu-activated-on-click color-scheme-dark">
            <div class="mm-logo-buttons-w">
                <a class="mm-logo" href="/admin/admin-dashboard"
                ><img src="{{url('')}}/public/assets/img/logo.svg" /><span></span></a
                >
                <div class="mm-buttons">
                    <div class="content-panel-open">
                        <div class="os-icon os-icon-grid-circles"></div>
                    </div>
                    <div class="mobile-menu-trigger">
                        <div class="os-icon os-icon-hamburger-menu-1"></div>
                    </div>
                </div>
            </div>
            <div class="menu-and-user">
                <div class="logged-user-w">
                    <div class="avatar-w"><img alt="" src="{{url('')}}/public/assets/img/smalllogo.svg" /></div>
                    <div class="logged-user-info-w">
                        <div class="logged-user-name">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</div>
                        <div class="logged-user-role">Administrator</div>
                    </div>
                </div>
                <ul class="main-menu">
                    <li class="has-sub-menu">
                        <a href="index.html"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-layout"></div>
                            </div>
                            <span>Dashboard</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="index.html">Dashboard 1</a></li>
                            <li>
                                <a href="apps_crypto.html"
                                >Crypto Dashboard
                                    <strong class="badge badge-danger">Hot</strong></a
                                >
                            </li>
                            <li><a href="apps_support_dashboard.html">Dashboard 3</a></li>
                            <li><a href="apps_projects.html">Dashboard 4</a></li>
                            <li><a href="apps_bank.html">Dashboard 5</a></li>
                            <li><a href="layouts_menu_top_image.html">Dashboard 6</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="layouts_menu_top_image.html"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-layers"></div>
                            </div>
                            <span>Menu Styles</span></a
                        >
                        <ul class="sub-menu">
                            <li>
                                <a href="layouts_menu_side_full.html">Side Menu Light</a>
                            </li>
                            <li>
                                <a href="layouts_menu_side_full_dark.html"
                                >Side Menu Dark</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_side_transparent.html"
                                >Side Menu Transparent
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="apps_pipeline.html">Side &amp; Top Dark</a></li>
                            <li><a href="apps_projects.html">Side &amp; Top</a></li>
                            <li>
                                <a href="layouts_menu_side_mini.html">Mini Side Menu</a>
                            </li>
                            <li>
                                <a href="layouts_menu_side_mini_dark.html"
                                >Mini Menu Dark</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_side_compact.html"
                                >Compact Side Menu</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_side_compact_dark.html"
                                >Compact Menu Dark</a
                                >
                            </li>
                            <li><a href="layouts_menu_right.html">Right Menu</a></li>
                            <li><a href="layouts_menu_top.html">Top Menu Light</a></li>
                            <li>
                                <a href="layouts_menu_top_dark.html">Top Menu Dark</a>
                            </li>
                            <li>
                                <a href="layouts_menu_top_image.html"
                                >Top Menu Image
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_sub_style_flyout.html"
                                >Sub Menu Flyout</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_sub_style_flyout_dark.html"
                                >Sub Flyout Dark</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_sub_style_flyout_bright.html"
                                >Sub Flyout Bright</a
                                >
                            </li>
                            <li>
                                <a href="layouts_menu_side_compact_click.html"
                                >Menu Inside Click</a
                                >
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="apps_bank.html"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-package"></div>
                            </div>
                            <span>Applications</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="apps_email.html">Email Application</a></li>
                            <li>
                                <a href="apps_support_dashboard.html">Support Dashboard</a>
                            </li>
                            <li><a href="apps_support_index.html">Tickets Index</a></li>
                            <li>
                                <a href="apps_crypto.html"
                                >Crypto Dashboard
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="apps_projects.html">Projects List</a></li>
                            <li>
                                <a href="apps_bank.html"
                                >Banking
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="apps_full_chat.html">Chat Application</a></li>
                            <li>
                                <a href="apps_todo.html"
                                >To Do Application
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="misc_chat.html">Popup Chat</a></li>
                            <li><a href="apps_pipeline.html">CRM Pipeline</a></li>
                            <li>
                                <a href="rentals_index_grid.html"
                                >Property Listing
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="misc_calendar.html">Calendar</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-file-text"></div>
                            </div>
                            <span>Pages</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="misc_invoice.html">Invoice</a></li>
                            <li>
                                <a href="ecommerce_order_info.html"
                                >Order Info
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li>
                                <a href="rentals_index_grid.html"
                                >Property Listing
                                    <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="misc_charts.html">Charts</a></li>
                            <li><a href="auth_login.html">Login</a></li>
                            <li><a href="auth_register.html">Register</a></li>
                            <li><a href="auth_lock.html">Lock Screen</a></li>
                            <li><a href="misc_pricing_plans.html">Pricing Plans</a></li>
                            <li><a href="misc_error_404.html">Error 404</a></li>
                            <li><a href="misc_error_500.html">Error 500</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-life-buoy"></div>
                            </div>
                            <span>UI Kit</span></a
                        >
                        <ul class="sub-menu">
                            <li>
                                <a href="uikit_modals.html"
                                >Modals <strong class="badge badge-danger">New</strong></a
                                >
                            </li>
                            <li><a href="uikit_alerts.html">Alerts</a></li>
                            <li><a href="uikit_grid.html">Grid</a></li>
                            <li><a href="uikit_progress.html">Progress</a></li>
                            <li><a href="uikit_popovers.html">Popover</a></li>
                            <li><a href="uikit_tooltips.html">Tooltips</a></li>
                            <li><a href="uikit_buttons.html">Buttons</a></li>
                            <li><a href="uikit_dropdowns.html">Dropdowns</a></li>
                            <li><a href="uikit_typography.html">Typography</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-mail"></div>
                            </div>
                            <span>Emails</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="emails_welcome.html">Welcome Email</a></li>
                            <li><a href="emails_order.html">Order Confirmation</a></li>
                            <li><a href="emails_payment_due.html">Payment Due</a></li>
                            <li><a href="emails_forgot.html">Forgot Password</a></li>
                            <li><a href="emails_activate.html">Activate Account</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-users"></div>
                            </div>
                            <span>Users</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="users_profile_big.html">Big Profile</a></li>
                            <li>
                                <a href="users_profile_small.html">Compact Profile</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-edit-32"></div>
                            </div>
                            <span>Forms</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="forms_regular.html">Regular Forms</a></li>
                            <li><a href="forms_validation.html">Form Validation</a></li>
                            <li><a href="forms_wizard.html">Form Wizard</a></li>
                            <li><a href="forms_uploads.html">File Uploads</a></li>
                            <li><a href="forms_wisiwig.html">Wisiwig Editor</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-grid"></div>
                            </div>
                            <span>Tables</span></a
                        >
                        <ul class="sub-menu">
                            <li><a href="tables_regular.html">Regular Tables</a></li>
                            <li><a href="tables_datatables.html">Data Tables</a></li>
                            <li><a href="tables_editable.html">Editable Tables</a></li>
                        </ul>
                    </li>
                    <li class="has-sub-menu">
                        <a href="index.html#"
                        ><div class="icon-w">
                                <div class="os-icon os-icon-zap"></div>
                            </div>
                            <span>Icons</span></a
                        >
                        <ul class="sub-menu">
                            <li>
                                <a href="icon_fonts_simple_line_icons.html"
                                >Simple Line Icons</a
                                >
                            </li>
                            <li><a href="icon_fonts_feather.html">Feather Icons</a></li>
                            <li><a href="icon_fonts_themefy.html">Themefy Icons</a></li>
                            <li><a href="icon_fonts_picons_thin.html">Picons Thin</a></li>
                            <li><a href="icon_fonts_dripicons.html">Dripicons</a></li>
                            <li>
                                <a href="icon_fonts_eightyshades.html">Eightyshades</a>
                            </li>
                            <li><a href="icon_fonts_entypo.html">Entypo</a></li>
                            <li>
                                <a href="icon_fonts_font_awesome.html">Font Awesome</a>
                            </li>
                            <li>
                                <a href="icon_fonts_foundation_icon_font.html"
                                >Foundation Icon Font</a
                                >
                            </li>
                            <li>
                                <a href="icon_fonts_metrize_icons.html">Metrize Icons</a>
                            </li>
                            <li>
                                <a href="icon_fonts_picons_social.html">Picons Social</a>
                            </li>
                            <li><a href="icon_fonts_batch_icons.html">Batch Icons</a></li>
                            <li><a href="icon_fonts_dashicons.html">Dashicons</a></li>
                            <li><a href="icon_fonts_typicons.html">Typicons</a></li>
                            <li>
                                <a href="icon_fonts_weather_icons.html">Weather Icons</a>
                            </li>
                            <li><a href="icon_fonts_light_admin.html">Light Admin</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="mobile-menu-magic">
                    <h4>Light Admin</h4>
                    <p>Clean Bootstrap 4 Template</p>
                    <div class="btn-w">
                        <a
                            class="btn btn-white btn-rounded"
                            href="https://themeforest.net/item/light-admin-clean-bootstrap-dashboard-html-template/19760124?ref=Osetin"
                            target="_blank"
                        >Purchase Now</a
                        >
                    </div>
                </div>
            </div>
        </div>
        <div
            class="menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link"
        >
            <div class="logo-w">
                <a class="mm-logo" href="/admin/admin-dashboard"
                ><img src="{{url('')}}/public/assets/img/smalllogo.svg" height="40" width="150" /><span>ETOP TMS</span></a
                >
            </div>
            <div class="logged-user-w avatar-inline">
                <div class="logged-user-i">
                    <div class="avatar-w"><img alt="" src="{{url('')}}/public/assets/img/smalllogo.svg" /></div>
                    <div class="logged-user-info-w">
                        <div class="logged-user-name">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</div>
                        <div class="logged-user-role">Administrator</div>
                    </div>
                    <div class="logged-user-toggler-arrow">
                        <div class="os-icon os-icon-chevron-down"></div>
                    </div>
                    <div class="logged-user-menu color-style-bright">
                        <div class="logged-user-avatar-info">
                            <div class="avatar-w">
                                <img alt="" src="{{url('')}}/public/assets/img/smalllogo.svg" />
                            </div>
                            <div class="logged-user-info-w">
                                <div class="logged-user-name">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</div>
                                <div class="logged-user-role">Administrator</div>
                            </div>
                        </div>
                        <div class="bg-icon">
                            <i class="os-icon os-icon-wallet-loaded"></i>
                        </div>
                        <ul>

                            <li>
                                <a href="/profile-setting"
                                ><i class="os-icon os-icon-user-male-circle2"></i
                                    ><span>Profile Settings</span></a
                                >
                            </li>

                            <li>
                                <a href="/logout"
                                ><i class="os-icon os-icon-signs-11"></i
                                    ><span>Logout</span></a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <h1 class="menu-page-header">Page Header</h1>
            <ul class="main-menu">
                <li class="sub-header"><span>Main</span></li>
                <li class="selected has-sub-menu">
                    <a href="/admin/admin-dashboard"
                    ><div class="icon-w">
                            <div class="os-icon os-icon-layout"></div>
                        </div>
                        <span>Dashboard</span></a
                    >
                    <div class="sub-menu-w">
                        <div class="sub-menu-header">Dashboard</div>
                        <div class="sub-menu-icon">
                            <i class="os-icon os-icon-layout"></i>
                        </div>
                        <div class="sub-menu-i">
                            <ul class="sub-menu">
                                <li><a href="/admin/admin-dashboard">Home</a></li>


                            </ul>
                        </div>
                    </div>
                </li>
                <li class="sub-header"><span>Operations</span></li>
                <li class="has-sub-menu">
                    <a href="#"
                    ><div class="icon-w">
                            <div class="os-icon os-icon-package"></div>
                        </div>
                        <span>Terminals</span></a
                    >
                    <div class="sub-menu-w">
                        <div class="sub-menu-header">Terminals</div>
                        <div class="sub-menu-icon">
                            <i class="os-icon os-icon-package"></i>
                        </div>
                        <div class="sub-menu-i">
                            <ul class="sub-menu">
                                <li><a href="/admin/new-terminal">Add New Terminal</a></li>
                                <li>
                                    <a href="/admin/list-terminals"
                                    >All Terminals</a
                                    >
                                </li>

                            </ul>

                        </div>
                    </div>
                </li>

                <li class="sub-header"><span>Onboarding</span></li>
                <li class="has-sub-menu">
                    <a href="#"
                    ><div class="icon-w">
                            <div class="os-icon os-icon-users"></div>
                        </div>
                        <span>Users</span></a
                    >
                    <div class="sub-menu-w">
                        <div class="sub-menu-header">Customers</div>
                        <div class="sub-menu-icon">
                            <i class="os-icon os-icon-users"></i>
                        </div>
                        <div class="sub-menu-i">
                            <ul class="sub-menu">
                                <li><a href="/admin/users">All Customer</a></li>
                                <li>
                                    <a href="/admin/new-users">Add New Customer</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>


                <li class="sub-header"><span>Report</span></li>
                <li class="has-sub-menu">
                    <a href="#"
                    ><div class="icon-w">
                            <div class="os-icon os-icon-bar-chart"></div>
                        </div>
                        <span>Transactions</span></a
                    >
                    <div class="sub-menu-w">
                        <div class="sub-menu-header">Transactions</div>
                        <div class="sub-menu-icon">
                            <i class="os-icon os-icon-bar-chart"></i>
                        </div>
                        <div class="sub-menu-i">
                            <ul class="sub-menu">
                                <li><a href="/admin/all-transactions">All Transactions</a></li>
                                <li>
                                    <a href="/admin/export-transactions">Export Transactions</a>
                                </li>
                                <li>
                                    <a href="/admin/profit">Profit Tracker</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="sub-header"><span>System</span></li>
                <li class="has-sub-menu">
                    <a href="#"
                    ><div class="icon-w">
                            <div class="os-icon os-icon-settings"></div>
                        </div>
                        <span>Settings</span></a
                    >
                    <div class="sub-menu-w">
                        <div class="sub-menu-header">Settings</div>
                        <div class="sub-menu-icon">
                            <i class="os-icon os-icon-bar-settings"></i>
                        </div>
                        <div class="sub-menu-i">
                            <ul class="sub-menu">
                                <li><a href="/admin/settings">System Settings</a></li>

                            </ul>
                        </div>
                    </div>
                </li>

            </ul>
{{--            <div class="side-menu-magic">--}}
{{--                <h4>Light Admin</h4>--}}
{{--                <p>Clean Bootstrap 4 Template</p>--}}
{{--                <div class="btn-w">--}}
{{--                    <a--}}
{{--                        class="btn btn-white btn-rounded"--}}
{{--                        href="https://themeforest.net/item/light-admin-clean-bootstrap-dashboard-html-template/19760124?ref=Osetin"--}}
{{--                        target="_blank"--}}
{{--                    >Purchase Now</a--}}
{{--                    >--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        @yield('content')



        <div class="display-type"></div>
    </div>
    <script src="{{url('')}}/public/assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/moment/moment.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/chart.js/dist/Chart.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/ckeditor/ckeditor.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap-validator/dist/validator.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/dropzone/dist/dropzone.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/editable-table/mindmup-editabletable.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/slick-carousel/slick/slick.min.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/util.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/alert.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/button.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/carousel.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/collapse.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/dropdown.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/modal.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/tab.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/tooltip.js"></script>
    <script src="{{url('')}}/public/assets/bower_components/bootstrap/js/dist/popover.js"></script>
    <script src="{{url('')}}/public/assets/js/demo_customizer.js%3Fversion=4.5.0"></script>
    <script src="{{url('')}}/public/assets/js/main.js%3Fversion=4.5.0"></script>

</body>
</html>

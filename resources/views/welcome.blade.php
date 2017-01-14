<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{"site.name"}}</title>
    <!-- Bootstrap core CSS -->
    <link href="assets/themes/admincustom/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom FireCrown -->
    <link href="assets/themes/admincustom/css/firecrown.css" rel="stylesheet">
    <link href="assets/themes/admincustom/css/remodal.css" rel="stylesheet">
    <link href="assets/themes/admincustom/css/remodal-default-theme.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.css">

    <link href="{{url("assets/tipr/tipr.css")}}" rel="stylesheet">
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,700,600' rel='stylesheet' type='text/css'>
    @yield('extracss')
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-default bg-darkblue-wb navbar-fc">
    <div class="container-fluid no-right-padding">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">{{trans("admin.toggle")}}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">{{"site.name"}}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-main">
                <li><a id="tickets">{{trans("common.tickets")}}</a></li>
                <li><a id="live">{{trans("common.livechat")}}</a></li>
                <li><a id="kb">{{trans("common.knowledgebase")}}</a></li>
                <li><a id="announcements">{{trans("common.announcements")}}</a></li>
                <li><a id="users">{{trans("common.users")}}</a></li>
                {{--
                @if(Auth::user()->isAdmin())
                    <li><a id="reports">{{trans("common.reports")}}</a></li>
                    <li><a id="settings">{{trans("common.settings")}}</a></li>
                @endif --}}
            </ul>
            <div class="pull-right nav-right">
                <ul class="nav navbar-nav">

                    {{--
                        @if(Auth::user()->isAdmin())
                            <li><a href="{{ url("admin/apps") }}">{{trans("admin.apps")}}</a></li>
                            <li><a href="{{ url("admin/software") }}">{{trans("admin.about")}}</a></li>
                        @endif
                     --}}
                </ul>

                <div class="search" id="magicsearch">
                    <i class="fa fa-search" id="magicwand"></i>
                    <div class="search-box">
                        <div class="magic-search">
                            <i class="fa fa-spinner fa-pulse" id="magicsearchloading"></i>
                            <span>{{trans("admin.magicsearch")}}</span>
                            <input class="" type="text" id="magicsearchtext"/>
                        </div>
                        <div class="search-results" id="magicsearchresults">
                        </div>
                    </div>

                </div>
                <div class="dropdown nav-drop">
                    <div id="nav-profile" class="dropdown dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="true">

                        @if(Auth::check())
                            {{--{{Auth::user()->getProfileImage()}}--}}
                            <img src="#" class="img-circle profile-picture-small"/> <span class="name">{{Auth::user()->name}}</span> <span class="caret"></span>
                        @endif
                    </div>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">


                        @if(Auth::check())

                        <li><a href="{{url("user/profile")}}"><i class="fa fa-user"></i> {{trans("common.myaccount")}}
                            </a></li>
                        <li><a href="{{url("admin/users/savedresponses")}}"><i
                                        class="fa fa-commenting"></i> {{trans("admin.savedresponses")}}</a></li>
                        <li><a href="http://support.firecrown.io" target="_blank"><i
                                        class="fa fa-support"></i> {{trans("common.help")}}</a></li>
                        <li class="divider"></li>

                        @endif

                        <li><a href="{{url("auth/logout")}}"><i class="fa fa-power-off"></i> {{trans("common.logout")}}
                            </a></li>
                    </ul>
                </div>
            </div>

        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="header-menu">

    <ul id="sub_tickets" class="subheader-menu">
        <li><a id="tickets_manage" href="{{url("admin/tickets/manage")}}">{{trans("admin.managetickets")}}</a></li>
        <li><a id="tickets_create" href="{{url("admin/tickets/create")}}">{{trans("admin.createticket")}}</a></li>
        <li><a id="tickets_search" href="{{url("admin/tickets/search")}}">{{trans("common.search")}}</a></li>
    </ul>

    <ul id="sub_live" class="subheader-menu">
        <li><a id="live_manage" href="{{url("admin/live/manage")}}">{{trans("admin.ongoingconv")}}</a></li>
        <li><a id="live_history" href="{{url("admin/live/history")}}">{{trans("admin.history")}}</a></li>
    </ul>

    <ul id="sub_kb" class="subheader-menu">
        <li><a id="kb_cat" href="{{url("admin/kb/categories")}}">{{trans("admin.catandart")}}</a></li>
        <li><a id="kb_new" href="{{url("admin/kb/new")}}">{{trans("admin.addart")}}</a></li>
        <li><a id="kb_newcategory" href="{{url("admin/kb/newcategory")}}">{{trans("admin.addcat")}}</a></li>
    </ul>

    <ul id="sub_announcements" class="subheader-menu">
        <li><a id="announcements_new" href="{{url("admin/announcements/new")}}">{{trans("admin.addanc")}}</a></li>
        <li><a id="announcements_manage"
               href="{{url("admin/announcements/manage")}}">{{trans("common.announcements")}}</a></li>
    </ul>

    <ul id="sub_reports" class="subheader-menu">
        <li><a id="reports_insights" href="{{url("admin/reports/insights")}}">{{trans("admin.insights")}}</a></li>
        <li><a id="reports_generator" href="{{url("admin/reports/generator")}}">{{trans("admin.reportgen")}}</a></li>
    </ul>

    <ul id="sub_users" class="subheader-menu">
        <li><a id="users_new" href="{{url("admin/users/new")}}">{{trans("admin.adduser")}}</a></li>
        <li><a id="user_search">{{trans("common.search")}}</a></li>
        <li><a id="users_manage" href="{{url("admin/users/manage")}}">{{trans("admin.userlist")}}</a></li>
        {{--@if(Auth::user()->isAdmin())
            <li><a id="users_departments" href="{{url("admin/users/departments")}}">{{trans("common.departments")}}</a>
            </li>
        @endif--}}
        <li><a id="users_customfields" href="{{url("admin/users/customfields")}}">{{trans("admin.customfields")}}</a>
        </li>
    </ul>

    <ul id="sub_settings" class="subheader-menu">
        <li><a id="settings_all" href="{{url("admin/settings/all")}}">{{trans("admin.gensettings")}}</a></li>
        <li><a id="settings_system" href="{{url("admin/settings/system")}}">{{trans("admin.systemsettings")}}</a></li>
        <li><a id="settings_languages" href="{{url("admin/languages/list")}}">{{trans("admin.language")}}</a></li>
        <li><a id="settings_recaptcha" href="{{url("admin/settings/recaptcha")}}">{{trans("admin.recaptcha")}}</a></li>
        <li><a id="settings_mail" href="{{url("admin/settings/mail")}}">{{trans("admin.mailsettings")}}</a></li>
    </ul>
</div>

<!-- Begin page content -->
<div class="container-fluid" id="mainpage">
    @if(Session::has('successmessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('successmessage') }}
        </div>
    @endif
    @if(Session::has('failuremessage'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('failuremessage') }}
        </div>
    @endif

    @yield("container")


    <div class="remodal" data-remodal-id="ring" data-remodal-options="hashTracking: false, closeOnOutsideClick: false">
        <h1>{{trans("newliveconversation")}} <span id="ringcid"></span></h1>
        <p>
            {{trans("message.liveassigned")}}
        </p>
        <br>
        <a class="gbutton" data-remodal-action="cancel">{{trans("admin.dismiss")}}</a>
        <a class="gbutton blue" data-remodal-action="confirm" id="ringlink" href="http://www.google.com"
           target="_blank">{{trans("admin.pickup")}}</a>
    </div>
    <audio id="livering">
        <source src="{{url("assets/audio/firemorse.wav")}}" type="audio/wav">
    </audio>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-muted">Beautiful, nice, footertekst.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{url("assets/themes/admincustom/js/bootstrap.min.js")}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
<script src="{{url("assets/tipr/tipr.min.js")}}"></script>
<script src="{{url("assets/js/jquery.timeago.js")}}"></script>
<script src="{{url("assets/themes/admincustom/js/moment.min.js")}}"></script>
<script src="{{url("assets/themes/admincustom/js/moment-timezone-with-data-2010-2020.min.js")}}"></script>
<script src="{{url("assets/js/jquery.toTextarea.min.js")}}"></script>
<script src="{{url("assets/js/bootbox.min.js")}}"></script>
<script src="{{url("assets/js/js.cookie.js")}}"></script>
<script src="{{url("assets/themes/admincustom/js/remodal.min.js")}}"></script>
<script src="{{url("assets/themes/admincustom/js/plugins.js")}}"></script>

<script type="text/javascript">

    $(document).ready(function () {

// CSRF Protection


        $.ajaxPrefilter(function (options) {
            if (!options.beforeSend) {
                options.beforeSend = function (xhr) {
                    xhr.setRequestHeader('csrftoken', '{{ csrf_token() }}');
                }
            }
        });


// Multiuse

        $(".timeago").timeago();


        $(".textarea").toTextarea({
            allowHTML: true,//allow HTML formatting with CTRL+b, CTRL+i, etc.
            allowImg: false,//allow drag and drop images
            singleLine: false,//make a single line so it will only expand horiontally
            pastePlainText: true//paste text without styling as source
        });

// Icon Picker; by FC
        $(".icon-picker li a").on('click', function () {
            var mydivcihld = $(this).children("div");
            var classIconName = $(mydivcihld).attr("class").split(' ')[1];
            $(this).closest(".input-icon-group").next("input").val(classIconName);
        });

        /* Header Menu helper
         ========================================================= */

        $('.navbar-main li').on("click", function () {
// show submenu
            var submenu_to_show = "sub_" + $(this).children("a").attr("id");
            $("#" + submenu_to_show).siblings().hide();
            $("#" + submenu_to_show).show();
// Chosen class
            var me = $(this);
            $(me).siblings().removeClass("chosen");
            $(me).addClass("chosen");


        });

        @if (!empty($highlight))
        $("#{{$highlight}}").parent().addClass("active");
        $("#{{$highlight}}").trigger("click");
        @endif
        @if (!empty($sub_highlight))
        $("#{{$sub_highlight}}").addClass("active");
        @endif

        /* Tips
         ========================================================= */

        $('.tip').tipr({
            'mode': 'bottom'
        });

        $('[data-toggle="tooltip"]').tooltip();


        /* Multipurpose
         ========================================================= */

        $('.selectpicker').selectpicker();

        /* Tables
         ========================================================= */

// Linked <tr>

        $('tr[data-href]').on("click", function (event) {
            if (!(event.target.className == "selectticket") && !(event.target.className == "tsfield center-text")) {
                document.location = $(this).data('href');
            }
        });


        /*
         Delete button
         */


        $('.deletebutton').on('click', function () {

            var form = $(this).parents('form:first');
            var deletemessage = "{{trans("message.abouttodelete")}}";

            bootbox.confirm(deletemessage, function (result) {

                if (result) {
                    var input = $("<input>").attr("type", "hidden").attr("name", "delete").val("delete");
                    form.append($(input));
                    form.submit();
                }

            });


        });

        $("#user_search").on("click", function (event) {
            $("#magicsearch").trigger("click");
        });
        /* Magic Search
         ========================================================= */

        $("#magicsearch").on("click", function (event) {

            if (event.target.id == "magicsearch" || event.target.id == "magicwand") {
                if ($("#magicsearch").hasClass("clicked")) {
                    $('.search-box').slideUp('fast');
                    $('#magicsearch').removeClass('clicked');
                }
                else {
                    $('#magicsearch').addClass('clicked');
                    $('.search-box').slideDown('fast');
                    $('#magicsearchtext').focus();
                }
            }

        });

// MagicSearch Linked LI

        $(document).on("click", "#magicsearch li", function (event) {
            event.stopPropagation();
            document.location = $(this).data('href');
        });

        $('#magicsearchtext').typing({

            start: function (event, $elem) {
            },
            stop: function (event, $elem) {

                $("#magicsearchloading").css('opacity', '1.0');

                var searchvalue = $("#magicsearchtext").val();

                var request = $.ajax({
                    url: "{{url("admin/home/magicsearch")}}",
                    type: "POST",
                    data: {query: searchvalue},
                    dataType: "html"
                });


                request.done(function (data) {

                    $("#magicsearchresults").html(data).show();

                    $("#magicsearchloading").css('opacity', '0.0');

                });

                request.fail(function (jqXHR, textStatus) {
                    console.log(textStatus);
                });


            },
            delay: 700
        });


        /* END ON DOC READY */
    });

    /* Wait and reload notification
     ========================================================= */

    function waitandreload() {

        var toast = toastr.info('<button type="button" class="btn clear dismisstoast">{{trans("admin.stop")}}</button>', 'Refreshing page', {
            timeOut: 3000,
            positionClass: "toast-top-center",
            progressBar: true
        })


        window.reloadFlag = true;

        setTimeout(function () {
            if (window.reloadFlag)
                reloadpage();
        }, 4000);

    }

    function reloadpage() {
        window.location.reload();
    }

    $(document).on('click', '.dismisstoast', function () {
        window.reloadFlag = false;
    });

    /* System-wide Events */
    $(document).ajaxComplete(function (event, xhr, settings) {

        if (xhr.status == "426") {
            var temp = JSON.parse(xhr.responseText);
            if (temp.result == "exception") {
                toastr.error(temp.message);
                waitandreload();
            }

        }
    });

</script>


@yield('extrajs')
</body>
</html>

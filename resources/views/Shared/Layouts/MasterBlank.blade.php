<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
<head>
    <!--
              _   _                 _ _
         /\  | | | |               | (_)
        /  \ | |_| |_ ___ _ __   __| |_ _______   ___ ___  _ __ ___
       / /\ \| __| __/ _ \ '_ \ / _` | |_  / _ \ / __/ _ \| '_ ` _ \
      / ____ \ |_| ||  __/ | | | (_| | |/ /  __/| (_| (_) | | | | | |
     /_/    \_\__|\__\___|_| |_|\__,_|_/___\___(_)___\___/|_| |_| |_|

    -->
    <title>
        @section('title')
            Attendize -
        @show
    </title>

@include('Shared.Layouts.ViewJavascript')

<!--Meta-->
@include('Shared.Partials.GlobalMeta')
<!--/Meta-->

    <!--JS-->
{!! HTML::script(config('attendize.cdn_url_static_assets').'/vendor/jquery/dist/jquery.min.js') !!}
<!--/JS-->

    <!--Style-->
{!! HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/application.css') !!}
<!--/Style-->

    @yield('head')
</head>
<body class="attendize">
@yield('pre_header')
@include('Shared.Partials.Topbar')

@yield('menu')

<!--Main Content-->
<section id="main" role="main">
    <div class="container-fluid">
        <div class="page-title">
            <h1 class="title">@yield('page_title')</h1>
        </div>
    @if(array_key_exists('page_header', View::getSections()))
        <!--  header -->
            <div class="page-header page-header-block row">
                <div class="row">
                    @yield('page_header')
                </div>
            </div>
            <!--/  header -->
    @endif

    <!--Content-->
    @yield('content')
    <!--/Content-->
    </div>

    <!--To The Top-->
    <a href="#" style="display:none;" class="totop"><i class="ico-angle-up"></i></a>
    <!--/To The Top-->

</section>
<!--/Main Content-->

<!--JS-->
@include("Shared.Partials.LangScript")
{!! HTML::script('assets/javascript/backend.js') !!}
<script>
  $(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
    });
  });

  @if(!Auth::user()->first_name || !Auth::user()->has_seen_first_modal)
  setTimeout(function () {
    $('.editUserModal').click();
  }, 1000);
    @endif

</script>
<!--/JS-->
@yield('foot')
<div class="landscape">
    <div class="container footer-nav">
        <p>Hello, world!</p>
        <p>Hello, world!</p>
        <p>Hello, world!</p>
        <p>Hello, world!</p>
        <p>Hello, world!</p>
        <p>Hello, world!</p>
    </div>
</div>
<div class="brand">
    <div class="container">
        <div class="row">
            <div class="col-md-10 brand-nav">
                <p>Hello, world!</p>
            </div>
            <div class="col-md-2 brand-icon">
                <object
                    type="image/svg+xml"
                    tabindex="-1"
                    role="img"
                    data="/assets/images/wmms-blk.svg"
                    aria-label="Symbol of the Government of Canada"
                ></object>
            </div>
        </div>
    </div>
</div>

@include('Shared.Partials.GlobalFooterJS')

</body>
</html>

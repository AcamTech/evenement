<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
<head>
    <title>
        @section('title')
            @lang('basic.ISED') -
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
<body class="attendize with-sidebar">
@yield('pre_header')
@include('Shared.Partials.Topbar')

@yield('menu')

<!--Main Content-->
<section id="main" role="main">
    <div class="container-fluid">

        @if(View::hasSection('page_title'))
            <div class="page-title">
                <h1 class="title">@yield('page_title')</h1>
            </div>
        @endif

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

@include('Shared.Partials.Footer')
@include('Shared.Partials.GlobalFooterJS')

</body>
</html>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    .nav-div{
    }
    @yield('style')
    </style>
  </head>
  <body>
  	<nav class="navbar nav-div navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('index') }}">{{session('USER_ID')->nickname}}的在线书签</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ url('add') }}">添加</a></li>
            <li><a href="{{ url('favorite') }}">常用书签</a></li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">更多 <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('userinfo') }}">个人资料</a></li>
            	<li><a href="{{ url('export') }}">导出html文件</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ url('logout') }}">logout</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right" method="get" action="{{url('search')}}" id="search-frm">
            <input type="text" class="form-control" value="{{$keyword or ''}}" placeholder="Search..." name="keyword" onkeydown="if(event.which==13)$('#search-frm').submit()">
          </form>
        </div>
      </div>
    </nav>
  	@yield('content')
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="text/javascript">
    @yield('script')
    </script>
  </body>
</html>

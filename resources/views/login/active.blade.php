
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>您的好友邀请你注册在线书签</title>
    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    .nav-div{
    }
    .box{
    border: 1px solid #DDD;
    border-radius: 4px;
    padding: 45px 15px 15px;
}
.bar-box{
	border-radius: 4px;
    padding: 15px;
}
.error{
	margin-left:20px;
}
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
          <a class="navbar-brand" href="{{url('index')}}">{{$user->nickname}}的在线书签[邀请注册]</a>
        </div>
      </div>
    </nav>
  	<div class="container bar-box">
<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default btn-edit">受邀注册</button>
  </div>
</div>
</div>
<div class="container box edit-box">
<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
    <div class="col-sm-10">
      <p class="form-control-static">{{$user->account}}</p>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">昵称</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="nickname" required autofocus value="{{$user->nickname}}" placeholder="昵称">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">新密码</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="newPass" placeholder="请输入新密码">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">新密码确认</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="newPass2" placeholder="请输入密码确认">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btnEdit btn-default">注册</button>
      <button onclick="location='{{url('login')}}'" type="button" class="btn btn-default">去登陆</button>
      <span class="text-danger error hide">请填写密码</span>
    </div>
  </div>
  {{ csrf_field() }}
</form>
</div>

    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="static/js/common.js"></script>
    <script type="text/javascript">
    $(function(){
	$(".btnEdit").on("click",function(){
		var n=$("#nickname"),np=$("#newPass"),np2=$("#newPass2");
		if(np.val()=='' || np.val().length<6){
			$(".error").removeClass("hide").html("请输入密码，不小于六位.");
			return false;
		}
		if(np.val()!=np2.val()){
			$(".error").removeClass("hide").html("二次密码不一致");
			return false;
		}
		$(".error").addClass("hide");
		var url=location.href.toString();
		var data={nickname:n.val(),newPass:np.val(),'_token':$("input:hidden[name='_token']").val()};
		ajax_post(url,data,function(j){
			if(j.error=='1'){
				$(".error").removeClass("hide").html(j.msg);
				return false;
			}
			if(j.error=='200'){
				$(".error").removeClass("hide").html("操作成功...gogogo");
				setTimeout(function(){
					location='{{url('login')}}';
				},1500);
			}
		});
	});
})
    </script>
  </body>
</html>

@extends('Common')

@section('title', '个人资料')

@section('style')
.box{
    border: 1px solid #DDD;
    border-radius: 4px;
    padding: 45px 15px 15px;
    display:none;
}
.bar-box{
	border-radius: 4px;
    padding: 15px;
}
.error{
	margin-left:20px;
}
@endsection

@section('script')
$(function(){
	$(".btnEdit").on("click",function(){
		var n=$("#nickname"),op=$("#oldPass"),np=$("#newPass"),np2=$("#newPass2");
		if(op.val().length>0){
			if(np.val()=='' || np.val().length<6){
				$(".error").removeClass("hide").html("新密码安全性过低，请重填");
				return false;
			}
			if(np.val()!=np2.val()){
				$(".error").removeClass("hide").html("二次密码不一致");
				return false;
			}
		}
		$(".error").addClass("hide");
		var url=location.href.toString();
		var data={nickname:n.val(),oldPass:op.val(),newPass:np.val(),'_token':$("input:hidden[name='_token']").val()};
		ajax_post(url,data,function(j){
			if(j.error=='1'){
				$(".error").removeClass("hide").html(j.msg);
				return false;
			}
			if(j.error=='200'){
				$(".error").removeClass("hide").html("操作成功...");
				setTimeout(function(){
					location=url;
				},1500);
			}
		});
	});
	
	$(".btn-edit,.btn-user").on("click",function(){
		var arg=$(this).hasClass('btn-edit') ? 'edit' : 'user';
		togger_box(arg);
		$(".btn-edit,.btn-user").removeClass("active")
		$(this).addClass("active");
	});
	$('.btn-edit').trigger('click');
})
function togger_box(arg){
	if(arg=='edit'){
		$(".edit-box").show();
		$(".user-box").hide();
	}else if(arg=='user'){
		$(".edit-box").hide();
		$(".user-box").show();
	}
}
@endsection

@section('content')
<div class="container bar-box">
<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default btn-edit">修改资料</button>
  </div>
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default btn-user">推荐好友</button>
  </div>
</div>
</div>
<div class="container box edit-box">
<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
    <div class="col-sm-10">
      <p class="form-control-static">{{$user->account or ''}}</p>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">昵称</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="nickname" required autofocus value="{{$user->nickname or ''}}" placeholder="昵称">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">创建时间</label>
    <div class="col-sm-10">
      <p class="form-control-static">{{$user->createDate or ''}}</p>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">旧密码</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="oldPass" placeholder="留空，则不修改密码">
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
      <button type="button" class="btn btnEdit btn-default">更新</button>
      <span class="text-danger error hide">旧密码不正确</span>
    </div>
  </div>
</form>
</div>

<div class="container box user-box">
<form class="form-horizontal" method="POST" action="{{ url('recommend') }}">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="user-account" required autofocus value="" placeholder="推荐人账号">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">昵称</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" name="user-name" required autofocus value="" placeholder="推荐人昵称">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">发送邮件</button>
      <span class="text-danger error hide">旧密码不正确</span>
    </div>
  </div>
  {{ csrf_field() }}
</form>
</div>

@endsection

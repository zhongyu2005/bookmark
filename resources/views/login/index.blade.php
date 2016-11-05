@extends('CommonBase')

@section('title', 'Sign to bookMark')

@section('style')
.container{
  width: 50%;
  min-width: 640px;
  margin-top: 10%;
}
@endsection

@section('script')
$(function(){
	$(".btn-block").on("click",function(){
		var a=$("#account"),p=$("#pass");
		if(a.val()=='' || a.val().length<4){
			a.focus();return false;
		}
		if(p.val()=='' || p.val().length<6){
			p.focus();return false;
		}
		$(".error").addClass("hide");
		var url=location.href.toString();
		var data={a:a.val(),p:p.val(),'_token':$("input:hidden[name='_token']").val()};
		ajax_post(url,data,function(j){
			if(j.error=='1'){
				$(".error").html(j.msg).removeClass('hide');
				return false;
			}
			if(j.error=='200'){
				location=j.url;
				return false;
			}
		});
	})
	$("form").on("keyup",function(e){
		var which=e.which;
		if(which==13){
			$(".btn-block").trigger("click");
		}
	})
})
@endsection

@section('content')
    
    <div class="container">

      <form class="form-signin" method="post" action="?" >
        <h2 class="form-signin-heading">bookMark</h2>
        <p class="text-danger hide error">账号密码不正确</p>
        <label for="inputEmail" class="sr-only">账号</label>
        <input type="email" id="account" maxlength="22" name="account" class="form-control" value="{{$mail or ''}}" placeholder="账号" required autofocus>
        <label for="inputPassword" class="sr-only">密码</label>
        <input type="password" id="pass" name="pass" maxlength="12" class="form-control" placeholder="密码" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> 记住账号
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="button">登录</button>
        {{ csrf_field() }}
      </form>

    </div> <!-- /container -->
@endsection

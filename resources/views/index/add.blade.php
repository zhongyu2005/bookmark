@extends('Common')

@section('title', '添加书签')

@section('style')
.edit-box{
    border: 1px solid #DDD;
    border-radius: 4px;
    padding: 45px 15px 15px;
}
.error{
	margin-left:20px;
}
@endsection

@section('script')
$(function(){
	$(".btnEdit").on("click",function(){
		var tn=$("#typeName"),n=$("#name"),u=$("#url"),desc=$("#desc");
		$(".error").addClass("hide");
		$(".has-error").removeClass("has-error");
		if(tn.val().length==0){
			tn.closest(".form-group").addClass("has-error");
			$(".error").removeClass("hide").html("请输入分类名称");
			return false;
		}
		if(n.val().length==0){
			n.closest(".form-group").addClass("has-error");
			$(".error").removeClass("hide").html("请输入名称");
			return false;
		}
		if(u.val().length==0){
			u.closest(".form-group").addClass("has-error");
			$(".error").removeClass("hide").html("请输入网址");
			return false;
		}
		var url=location.href.toString();
		var data={
			'_token':$("input:hidden[name='_token']").val(),
			typeName:tn.val(),name:n.val(),url:u.val(),desc:desc.val(),typeId:$("#typeId").val()
			
		};
		ajax_post(url,data,function(j){
			if(j.error=='1'){
				$(".error").removeClass("hide").html(j.msg);
				return false;
			}
			if(j.error=='200'){
				$(".error").removeClass("hide").html("操作成功...");
				setTimeout(function(){
					location.reload();
				},1500);
			}
		});
	});
	
	$("#type-ul .type-a").on("click",function(){
		var obj=$(this);
		$("#typeId").val(obj.attr("d-id"));
		$("#typeName").val(obj.text());
	})
})
@endsection

@section('content')
<div class="container edit-box">
<form class="form-horizontal">
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">分类</label>
    <div class="col-sm-10">
		<div class="input-group">
          <div class="input-group-btn">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">选择分类 <span class="caret"></span></button>
            <ul class="dropdown-menu" id="type-ul">
            	<?php if(empty($typeList)):?>
              <li><a href="#">木有分类</a></li>
              <?php
                else:
                    foreach ($typeList as $k=>$v):
              ?>
              <li><a href="#" class="type-a" d-id="{{$k}}">{{$v}}</a></li>
              <?php endforeach;endif;?>
            </ul>
          </div><!-- /btn-group -->
          <input type="text" class="form-control" id="typeName" aria-label="..." placeholder="分类名称">
          <input type="hidden" id="typeId" value="0">
        </div><!-- /input-group -->
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">名称</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="name" required autofocus value="" placeholder="名称">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">网址</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="url" required autofocus value="" placeholder="网址">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">描述</label>
    <div class="col-sm-10">
		<textarea class="form-control" id="desc" required autofocus value="" placeholder="描述"></textarea>
    </div>
  </div>

  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btnEdit btn-default">提交</button>
      <span class="text-danger error hide">请输入正确的内容</span>
    </div>
  </div>
  {{ csrf_field() }}
</form>
</div>
@endsection

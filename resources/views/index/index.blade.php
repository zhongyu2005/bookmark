@extends('Common')

@section('title', '我的书签')

@section('style')
.one{
	
}
@endsection

@section('script')
$(function(){
	$(".list-group").on("click",'.badge',function(){
		//弹出修改
		var id=$(this).attr("d-id");
		
		var jsm=$("#js-model");
		jsm.find(".modal-title").html("修改书签");
		jsm.find(".modal-body").html($("#tpl-book").html());
		jsm.modal();
		$("#book-name").val($(this).attr("d-name"));
		$("#book-url").val($(this).attr("d-url"));
		$("#book-desc").val($(this).parent().attr("title"));
		$("#book-type").val($(this).parent().prevAll(".active").attr("d-id"));
		$("#book-id").val(id);
		jsm.one("shown.bs.modal",function(){
			$("#type-name").focus().select();
		})
		$(".current-edit").removeClass("current-edit");
		$(this).addClass("current-edit");
		return false;
	});
	$(".list-type").on("click",function(){
		var id=$(this).attr("d-id");
		var jsm=$("#js-model");
		jsm.find(".modal-title").html("修改书签分类");
		jsm.find(".modal-body").html($("#tpl-type").html());
		jsm.modal();
		$("#type-name").val($(this).text());
		$("#type-id").val(id);
		jsm.one("shown.bs.modal",function(){
			$("#type-name").focus().select();
		})
		$(".current-edit").removeClass("current-edit");
		$(this).addClass("current-edit");
		return false;
	})
	$("#btnSave").on("click",function(){
		var tid=$("#type-id"),id=$("#book-id");
		$(".error").addClass("hide");
		$(".has-error").removeClass('has-error');
		if(tid.val()!=undefined){
			//type
			var tn=$("#type-name"),cbo=$("#type-cbo");
			tn.prop('readonly',true);
			var is_del=cbo.prop("checked") ? 1 : 0;
			var url='{{url('edit')}}';
			var data={
				typeId:tid.val(),typeName:tn.val(),'is_del':is_del,
				'_token':$("input:hidden[name='_token']").val()
			};
			ajax_post(url,data,function(j){
				if(j.error=='1'){
					$(".error").removeClass("hide").html(j.msg);
					return false;
				}else if(j.error=='200'){
					$(".current-edit").text(tn.val());
					if(is_del=='1'){
						$(".current-edit").closest('.one').hide();
					}
					$(".close").trigger("click");
					return false;
				}
			});
		}else if(id.val()!=undefined){
			var n=$("#book-name"),u=$("#book-url"),d=$("#book-desc"),t=$("#book-type");
			if(n.val()=='' || u.val()==''){
				n.closest(".form-group").addClass("has-error");
				$(".error").removeClass("hide").html("请输入名称");
			}
			if(u.val()=='' || u.val()==''){
				u.closest(".form-group").addClass("has-error");
				$(".error").removeClass("hide").html("请输入url");
			}
			n.prop('readonly',true);
			u.prop('readonly',true);
			d.prop('readonly',true);
			var is_del=$("#book-cbo").prop("checked") ? 1 : 0;
			var url='{{url('edit')}}';
			var data={
				bname:n.val(),burl:u.val(),bdesc:d.val(),'is_del':is_del,type:t.val(),
				'_token':$("input:hidden[name='_token']").val(),id:id.val()
			};
			ajax_post(url,data,function(j){
				if(j.error=='1'){
					$(".error").removeClass("hide").html(j.msg);
					return false;
				}else if(j.error=='200'){
					var obj=$(".current-edit");
					obj.attr({"d-name":n.val(),"d-url":u.val()});
					obj.parent().attr({"title":d.val(),"href":j.url});
					obj.parent().text(n.val()).append(obj);
					if(is_del=='1'){
						obj.parent().hide();
					}
					$(".close").trigger("click");
					location.reload();
					return false;
				}
			});
			
		}
		return false;
	});
	//fix height
	fix_bug()
})
function fix_bug(){
	var ar=[],div=$(".one");
	var j=1,t=[];
	div.each(function(){
		t.push($(this));
		if(j%4==0){
			ar.push(t);
			t=[];
		}
		j++;
	});
	//if(t.length>0)ar.push(t);
	t=null;
	$.each(ar,function(i,t){
		var tar=[];
		$.each(t,function(){
			tar.push($(this).height());
		})
		var max=Math.max.apply('max',tar);
		$.each(t,function(){
			$(this).height(max+"px");
		})
		console.log(max);
	});
	//div.height(max+"px");
}
@endsection

@section('content')
	<div class="container">
      <div class="row">
      	<?php if(empty($list)):?>
      	<p class="text-danger container" >啥都木有,去<a href="{{url('add')}}">添加</a>一个</p>
      	<?php 
      	 else:
      	     foreach ($list as $k=>$v):
      	?>
        <div class="col-xs-6 col-sm-3 one">
			<div class="list-group">
              <a href="javascript:;" d-id="{{$v['id']}}" class="list-group-item active list-type">{{$v['name']}}</a>
              <?php
                if(!empty($v['list'])):
                    foreach ($v['list'] as $lv):
              ?>
              <a href="{{$lv->goto}}" target="_blank" title="{{$lv->mark}}" class="list-group-item"><span d-id="{{$lv->id}}" d-url="{{$lv->url}}" d-name="{{$lv->name}}" class="badge">{{$lv->click}}</span>{{$lv->name}}</a>
              <?php endforeach; endif;?>
            </div>
        </div>
        <?php endforeach; endif;?>
       </div>
       {{ csrf_field() }}
	</div>
	
<div class="modal fade" id="js-model">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="btnSave">提交</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/template" id="tpl-type">
<form>
<input type="hidden" id="type-id">
<p class="text-danger hide error">操作失败.</p>
<div class="form-group">
<label for="type-name" class="control-label">分类名称:</label>
<input type="text" class="form-control" id="type-name">
</div>
<div class="checkbox">
    <label>
      <input type="checkbox" id="type-cbo"> 是否删除(将删除下面所有)
    </label>
  </div>
</form>
</script>
<script type="text/template" id="tpl-book">
<form>
<input type="hidden" id="book-id">
<p class="text-danger hide error">操作失败.</p>
<div class="form-group">
<label for="type-name" class="control-label">名称:</label>
<input type="text" class="form-control" id="book-name">
</div>
<div class="form-group">
<label for="type-name" class="control-label">url:</label>
<input type="text" class="form-control" id="book-url">
</div>
<div class="form-group">
<label for="type-name" class="control-label">描述:</label>
<input type="text" class="form-control" id="book-desc">
</div>
<div class="form-group">
<label for="type-name" class="control-label">移动到:</label>
<select class="form-control" id="book-type">
    <?php if(!empty($typeList)){
        foreach ($typeList as $k=>$v){
            echo sprintf("<option value='%s'>%s</option>\n",$k,$v);
        }
    }?>
</select>
</div>
<div class="checkbox">
    <label>
      <input type="checkbox" id="book-cbo"> 是否删除
    </label>
  </div>
</form>
</script>
@endsection

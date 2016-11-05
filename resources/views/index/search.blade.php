@extends('Common')

@section('title', '搜索书签')

@section('style')
@endsection

@section('script')
$(function(){
})
@endsection

@section('content')
	<div class="container">
      <div class="row">
      	<?php if(empty($arr)):?>
      	<p class="text-danger container" >啥都木有,换个姿势,重新搜索</p>
      	<?php 
      	 else:
      	?>
        <div class=" one">
			<div class="list-group">
              <a href="javascript:;" class="list-group-item active list-type">top50</a>
              <?php
                    foreach ($arr as $lv):
              ?>
              <a href="{{$lv->goto}}" target="_blank" title="{{$lv->mark}}" class="list-group-item"><span class="badge">{{$lv->click}}</span>{{$lv->name}}</a>
              <?php endforeach;?>
            </div>
        </div>
        <?php endif;?>
       </div>
       {{ csrf_field() }}
	</div>
@endsection

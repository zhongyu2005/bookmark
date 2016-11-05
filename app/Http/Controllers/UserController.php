<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * //$request->ajax()
     * //$request->method();
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询当前用户的资料
        $user=session('USER_ID');
        $where=sprintf("id='%u' AND deleted=0",$user->id);
        $sql="SELECT id,pass,nickname,account,create_at FROM bk_user WHERE {$where}";
        $user=DB::selectOne($sql);
        if($request->method()=='POST'){
            $nickname=$request->input('nickname','');
            $oldPass=$request->input('oldPass','');
            $newPass=$request->input('newPass','');
            
            //如果匹配
            if(!empty($oldPass) && strlen($newPass)<6){
                return response()->json(['error'=>1,'msg'=>'新密码安全性过低，请重填']);
            }
//             $saveData=[time(),];
            $sql="update bk_user SET update_at='".time()."' ";
            if(!empty($nickname)){
                $sql.=",nickname='{$nickname}'";
//                 $saveData[]=$nickname;
            }
            if(!empty($oldPass)){
                if(md5($oldPass)!=$user->pass){
                    return response()->json(['error'=>1,'msg'=>'旧密码不匹配，请重填']);
                }
                $sql.=",pass='".md5($newPass)."'";
//                 $saveData[]=md5($newPass);
            }
            $sql.=" WHERE id=".$user->id;
//             $saveData[]=$user->id;
            $affected = DB::update($sql);
            return response()->json(['error'=>200]);
        }
        if(!empty($user->create_at)){
            $user->createDate=date("Y-m-d H:i",$user->create_at);
        }
        return view('user.index',compact('user'));
    }
    
    /**
     * 邀请
     */
    public function recommend(Request $request){
        $account=$request->input('user-account','');
        $name=$request->input('user-name','');
        if(empty($account) || empty($name)){
            return '不行不行,添加资料错误.3秒后<a href="javascript:history.bakc();">返回</a>;
                <script>setTimeout(function(){history.back();},3000);</script>';
        }
        if(!filter_var($account, FILTER_VALIDATE_EMAIL)){
            return '不行不行,邮件格式错误.3秒后<a href="javascript:history.bakc();">返回</a>;
                <script>setTimeout(function(){history.back();},3000);</script>';
        }
        //查询是否重复
        $sql="SELECT id FROM bk_user where account=? ";
        $id=DB::selectOne($sql,[$account]);
        if(!empty($id)){
            return '不行不行,好友已经存在啦.3秒后<a href="javascript:history.bakc();">返回</a>;
                <script>setTimeout(function(){history.back();},3000);</script>';
        }
        //防止重复发生,一天只能邀请一次
        $sql="SELECT id,create_at FROM bk_user_reg where account=?  ORDER BY id DESC";
        $user=DB::selectOne($sql,[$account]);
        if(!empty($user) && $user->create_at>strtotime(date("Y-m-d")) ){
            return '不行不行,同一个好友一天只能推荐一次.3秒后<a href="javascript:history.bakc();">返回</a>;
                <script>setTimeout(function(){history.back();},3000);</script>';
        }
        $code=str_random(12);
        $sql="INSERT INTO bk_user_reg(account,nickname,code,userid,create_at,update_at) values(?,?,?,?,?,?)";
        DB::insert($sql,[$account,$name,$code,session('USER_ID')->id,time(),time()]);
        $id=DB::getPdo()->lastInsertId();
        if(empty($id)){
            return '不行不行,服务器发脾气了,操作失败哦！.3秒后<a href="javascript:history.bakc();">返回</a>重试;
                <script>setTimeout(function(){history.back();},3000);</script>';
        }
        //发送邮件
        $data = ['email'=>$account, 'name'=>$name, 'uid'=>$id, 'activationcode'=>md5($code.$id)];
        Mail::send('activemail', $data, function($m) use($data)
        {
            $m->from('dust2014@126.com', 'bookMark');
            $m->to($data['email'], $data['name'])->subject('在线书签，来之您的好友['.session('USER_ID')->nickname.']的邀请！');
        });
        return '操作成功哦.3秒后<a href="'.url('userinfo').'">返回</a>继续;
                <script>setTimeout(function(){location="'.url('userinfo').'";},3000);</script>';
    }

}

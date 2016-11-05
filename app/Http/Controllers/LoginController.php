<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     * //$request->ajax()
     * //$request->method();
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //如果已经登陆
        $obj=session('USER_ID');
        if (!empty($obj)) {
            return redirect('logout');
        }
        
        if($request->method()=='POST'){
            $account=$request->input('a','');
            $pass=$request->input('p','');
            //如果匹配
            if(empty($account) || empty($pass) || strlen($account)<4 || strlen($pass)<6){
                return response()->json(['error'=>1,'msg'=>'请填写正确的账号密码']);
            }
            $sql="SELECT id,pass,salt,nickname,level FROM bk_user WHERE deleted=0 AND account='{$account}'";
            $user=DB::selectOne($sql);
            if(empty($user) || $user->pass!=md5($pass) ){
                return response()->json(['error'=>1,'msg'=>'账号密码不正确']);
            }
            session(['USER_ID'=>$user]);
            return response()->json(['error'=>200,'url'=>url('index')]);
        }
        $mail='';
        if($request->input('zy')){
            $mail='hello8080@qq.com';
        }
        return view('login.index',compact('mail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->forget('USER_ID');
        $request->session()->flush();
        return redirect('login');
    }
    
    /**
     * 好友邀请注册
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $uid=$request->input('uid',0);
        $activationcode=$request->input('activationcode','');
        $sql="SELECT id,code,account,nickname FROM bk_user_reg where id=? AND deleted=0  ORDER BY id DESC";
        $user=DB::selectOne($sql,[$uid]);
        if(empty($user) || md5($user->code .$uid )!=$activationcode){
            if($request->isXmlHttpRequest()){
                return response()->json(['error'=>1,'msg'=>'功能似乎不再开放.']);
            }
            return '不知道发生了什么事儿？去问问你邮件里的好友吧！';
        }
        //查询账号是否注册
        $sql="SELECT id FROM bk_user where account=? ";
        $bk_row=DB::selectOne($sql,[$user->account]);
        if(!empty($bk_row)){
            if($request->isXmlHttpRequest()){
                return response()->json(['error'=>1,'msg'=>'账号已经注册过了.去登陆看看']);
            }
            return '账号已经注册了,去登陆啦.<script>setTimeout(function(){location="'.url('login').'"},2500)</script>';
        }
        if($request->isXmlHttpRequest()){
            $nickname=$request->input('nickname',0);
            $newPass=$request->input('newPass',0);
            if(empty($nickname) || empty($newPass) || strlen($newPass)<6){
                return response()->json(['error'=>1,'msg'=>'昵称和密码都设置了么？密码会不会太简单了']);
            }
            $nickname=!empty($nickname) ? substr($nickname, 0,32) : '';
            $pass=md5($newPass);
            $sql="INSERT INTO bk_user(account,pass,nickname,create_at,update_at) values(?,?,?,?,?) ";
            $save=[$user->account,$pass,$nickname,time(),time()];
            DB::insert($sql,$save);
            $id=DB::getPdo()->lastInsertId();
            if(empty($id)){
                return response()->json(['error'=>1,'msg'=>'系统居然不稳定哦[投诉他],要不过会再试试？']);
            }
            //更新当前的为已经注册
            $sql="UPDATE bk_user_reg SET deleted=2 where id=?";
            DB::update($sql,[$uid]);
            return response()->json(['error'=>200]);
        }
        
        return view('login.active',compact('user'));
    }
}

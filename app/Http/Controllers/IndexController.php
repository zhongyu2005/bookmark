<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class IndexController extends Controller
{
    /**
     * favorite
     */
    public function favorite(){
        $user=session('USER_ID');
        $where=sprintf("userid='%u'",$user->id);
        $sql="SELECT id,name,url,mark,click FROM bk_favorite WHERE {$where} ORDER BY click DESC limit 50";
        $arr=DB::select($sql);
        if(!empty($arr)){
            foreach ($arr as &$lv){
                $lv->goto=url('go').'?'.http_build_query(['url'=>$lv->url,'id'=>$lv->id]);
            }
            unset($lv);
        }
        return view('index.favorite',compact('arr'));
    }
    
    /**
     * favorite
     */
    public function search(Request $request){
        $user=session('USER_ID');
        $where=sprintf("userid='%u'",$user->id);
        $keyword=$request->input('keyword','');
        if(!empty($keyword)){
            $keyword=trim($keyword);
            $where.=" AND name like '%{$keyword}%'";
        }
        
        $sql="SELECT id,name,url,mark,click FROM bk_favorite WHERE {$where} ORDER BY click DESC limit 50";
        $arr=DB::select($sql);
        if(!empty($arr)){
            foreach ($arr as &$lv){
                $lv->goto=url('go').'?'.http_build_query(['url'=>$lv->url,'id'=>$lv->id]);
            }
            unset($lv);
        }
        return view('index.favorite',compact('arr','keyword'));
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=session('USER_ID');
        //查询所有的分类，从高到底
        $typeList=$this->getTypeList();
        //查询所有的分类下的书签，从高到底
        if(!empty($typeList)){
            $list=[];
            foreach ($typeList as $k=>$v){
                //查询分类下所有的内容
                $where=sprintf("userid='%u' AND cls_id='%u' AND deleted=0",$user->id,$k);
                $sql="SELECT id,name,url,mark,click FROM bk_favorite WHERE {$where} ORDER BY click DESC";
                $arr=DB::select($sql);
                if(!empty($arr)){
                    foreach ($arr as &$lv){
                        $lv->goto=url('go').'?'.http_build_query(['url'=>$lv->url,'id'=>$lv->id]);
                    }
                    unset($lv);
                }
                $list[]=['name'=>$v,'id'=>$k,'list'=>$arr];
            }
        }
        return view('index.index',compact('list','typeList'));
    }
    
    /***
     * add bookMark
     * @param Request $request
     */
    public function add(Request $request){
        
        $user=session('USER_ID');
        //查询当前所有的用户的分类
        $typeList=$this->getTypeList();
        if($request->method()=='POST'){
            $typeName=$request->input('typeName','');
            $typeId=$request->input('typeId','');
            $name=$request->input('name','');
            $url=$request->input('url','');
            $desc=$request->input('desc','');
            
            if(empty($typeName) || empty($name) || empty($url)){
                return response()->json(['error'=>1,'msg'=>'请确认输入完整']);
            }
            if(!filter_var($url, FILTER_VALIDATE_URL)){
                return response()->json(['error'=>1,'msg'=>'输入的网址不合法']);
            }
            $tid=0;
            if(!empty($typeId) && isset($typeList[$typeId]) && $typeList[$typeId]==$typeName ){
                $tid=$typeId;
            }
            if(empty($tid) && !empty($typeList)){
                $_typeList=array_flip($typeList);
                if(isset($_typeList[$typeName])){
                    $tid=$_typeList[$typeName];
                }
            }
            if(empty($tid)){
                //add - type
                $save=[$typeName,0,0,$user->id,time()];
                DB::insert("insert into bk_type(name,parent,sort,userid,create_at) values(?,?,?,?,?)",$save);
                $tid=DB::getPdo()->lastInsertId();
                if(empty($tid)){
                    return response()->json(['error'=>1,'msg'=>'暂时无法连接到服务器,请稍后再试.']);
                }
            }
            //add bookmark
            $sql="insert into bk_favorite(cls_id,name,url,mark,click,userid,create_at,update_at) values(?,?,?,?,?,?,?,?)";
            $save=[$tid,$name,$url,$desc,0,$user->id,time(),time()];
            DB::insert($sql,$save);
            $id=DB::getPdo()->lastInsertId();
            if(empty($id)){
                return response()->json(['error'=>1,'msg'=>'暂时无法连接到服务器,请稍后再试.']);
            }
            return response()->json(['error'=>200]);
        }
        return view('index.add',compact('typeList'));
    }
    /***
     * edit bookMark
     * @param Request $request
     */
    public function edit(Request $request){
        
        if($request->method()=='POST'){
            
            $typeId=$request->input('typeId');
            $id=$request->input('id');
            if(!empty($typeId)){
                //edit-type
                $typeName=$request->input('typeName','');
                $isDel=$request->input('is_del','0');
                $isDel=empty($isDel) ? 0 : 1;
                if(empty($typeName)){
                    return response()->json(['error'=>1,'msg'=>'输入的名称不合法']);
                }
                $where=sprintf("id='%u' AND deleted=0",$typeId);
                $sql="SELECT id FROM bk_type WHERE {$where}";
                $id=DB::selectOne($sql);
                if(empty($id)){
                    return response()->json(['error'=>1,'msg'=>'分类不存在']);
                }
                $sql="UPDATE bk_type SET name=?,deleted=? WHERE id=?";
                DB::update($sql,[$typeName,$isDel,$id->id]);
                return response()->json(['error'=>200]);
            }
            if(!empty($id)){
                $bname=$request->input('bname','');
                $burl=$request->input('burl','');
                $bdesc=$request->input('bdesc','');
                $isDel=$request->input('is_del','0');
                $type=$request->input('type','0');
                $isDel=empty($isDel) ? 0 : 1;
                if(empty($bname) || empty($burl)){
                    return response()->json(['error'=>1,'msg'=>'输入的名称不合法']);
                }
                if(!filter_var($burl, FILTER_VALIDATE_URL)){
                    return response()->json(['error'=>1,'msg'=>'输入的网址不合法']);
                }
                $typeList=$this->getTypeList();
                if(!isset($typeList[$type])){
                    return response()->json(['error'=>1,'msg'=>'移动到的新类别不存在哦']);
                }
                $where=sprintf("id='%u' AND deleted=0",$id);
                $sql="SELECT id FROM bk_favorite WHERE {$where}";
                $id=DB::selectOne($sql);
                if(empty($id)){
                    return response()->json(['error'=>1,'msg'=>'书签不存在']);
                }
                $sql="UPDATE bk_favorite SET name=?,url=?,mark=?,deleted=?,cls_id=? WHERE id=?";
                DB::update($sql,[$bname,$burl,$bdesc,$isDel,$type,$id->id]);
                $url=url('go').'?'.http_build_query(['url'=>$burl,'id'=>$id->id]);
                return response()->json(['error'=>200,'url'=>$url]);
            }
            
            return response()->json(['error'=>1,'msg'=>'操作失败']);
        }
    }
    
    /**
     * 跳转
     */
    public function redirect(Request $request){
        $url=$request->input('url','');
        $id=$request->input('id','');
        
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            echo '网址不合法';
            return ;
        }
        $id=intval($id);
        if($id>0){
            $sql="UPDATE bk_favorite SET click=click+1 WHERE id='".$id."'";
            DB::update($sql);
        }
        return redirect($url);
    }
    
    /***
     * 查询所有的分类
     */
    private function getTypeList(){
        $user=session('USER_ID');
        $sql="SELECT id,name FROM bk_type WHERE deleted=0 AND userid='".$user->id."' ORDER BY sort DESC";
        $list=DB::select($sql);
        if(!empty($list)){
            $typeList=[];
            foreach ($list as $v){
                $typeList[$v->id]=$v->name;
            }
            unset($list);
            return $typeList;
        }
        return false;
    }

}

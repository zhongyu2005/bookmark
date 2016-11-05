<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     * //$request->ajax()
     * //$request->method();
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=session('USER_ID');
        //查询所有的type
        $sql="SELECT id,name,create_at FROM bk_type 
            WHERE deleted=0 AND userid='".$user->id."'";
        $list=DB::select($sql);
        if(empty($list)){
            return '没有数据需要导出.';
        }
        if(!empty($list)){
            $time=time();
            $body=[];
            foreach ($list as $v){
                $v=(array)$v;
                $row=['create_at'=>$v['create_at'],'update_at'=>$time,'name'=>$v['name']];
                $dir=$this->makeDir($row);
                //查询下面的所有链接
                $sql="SELECT name,url,create_at FROM bk_favorite WHERE "
                    .sprintf("deleted=0 AND cls_id='%u' AND userid='%u'"
                        ,$v['id'],$user->id);
                $alist=DB::select($sql);
                if(empty($alist)){
                    $dir=sprintf($dir,'');
                }else{
                    $ar=[];
                    foreach ($alist as $av){
                        $av=(array)$av;
                        $ar[]=$this->makeHref($av);
                    }
                    $dir=sprintf($dir,implode("\n",$ar));
                }
                $body[]=$dir;
            }
            $this->echoHtml(implode($body));
        }
    }
    
    /**
     * 生成文件夹
     */
    private function makeDir($row){
        $atime=$row['create_at'];
        $utime=$row['update_at'];
        $name=$row['name'];
        $dir=PHP_EOL.'<DT><H3 ADD_DATE="'.$atime.'" LAST_MODIFIED="'.$utime.'">'.$name.'</H3>
        <DL><p>%s</DL><p>'.PHP_EOL;
        return $dir;
    }
    
    /**
     * 生成超链接
     */
    private function makeHref($row){
        $url=$row['url'];
        $atime=$row['create_at'];
        $name=$row['name'];
        $href=PHP_EOL.'<DT><A HREF="'.$url.'" ADD_DATE="'.$atime.'" >'.$name.'</A>'.PHP_EOL;
        return $href;
    }
    
    /**
     * 输出下载
     * @param string $body
     */
    private function echoHtml($body){
        $html=<<<EOF
        <!DOCTYPE NETSCAPE-Bookmark-file-1>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>Bookmarks</TITLE>
<H1>Bookmarks</H1>
<DL><p>

$body

</DL><p>
EOF;
        header ( "Cache-Control: max-age=0" );
        header ( "Content-Description: File Transfer" );
        header ( 'Content-disposition: attachment; filename=bookmark.html'); // 文件名
        header ( "Content-Type: application/html" ); // zip格式的
        header ( "Content-Transfer-Encoding: binary" );
        echo $html;
    }
    
}

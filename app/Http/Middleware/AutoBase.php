<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AutoBase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $obj=session('USER_ID');
        $id=!isset($obj->id) ? 0 : $obj->id;
        $sql="SELECT id FROM bk_user WHERE deleted=0 AND id='".$id."'";        
        $user=DB::selectOne($sql);
        if (empty($user)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        return $next($request);
    }
}

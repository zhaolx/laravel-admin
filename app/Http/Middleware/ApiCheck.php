<?php

namespace App\Http\Middleware;

use Closure;

class ApiCheck
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
        $session_user = session('USERINFO');
        $no_auth_list = config('global.no_auth_rute');
       /* if($request->path()!=='api/user/login'&&(!$session_user||!$session_user['uid'])){
            return  response()->json(['code'=>-1,'msg'=>'请先登录']);
        }
        $route = str_replace('api/', '', $request->path());
        if($session_user['is_admin']!=1&&!in_array($route, $no_auth_list)){
            if(!$this->checkPermission($route)){
                return  response()->json(['code'=>401,'msg'=>'无权访问']);
            }
        }*/
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            'http://localhost',
        ];
      
        if (in_array($origin, $allow_origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
       // return $next($request);
    }
    //检查权限
    protected function checkPermission($route){

        $permission_list = session('PERMISSIONS');
        if(empty($permission_list)){
            return false;
        }
        if(in_array($route, $permission_list)){
            return true;
        }
        return false;
    }
}

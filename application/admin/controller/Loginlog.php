<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;
use think\facade\Request;
use app\admin\common\model\Loginlog as LoginLogModel;
use think\facade\Session;

class Loginlog extends Base
{
    // 登录日志首页
    // 管理员管理首页
    public function index()
    {
        $this -> view -> assign('title', '登录日志管理');
        return $this -> view -> fetch('index');
    }

    // 登录日志列表
    public function logList()
    {
        // 定义全局查询条件
        $map = []; // 将所有的查询条件封装到这个数组中

        // 搜索功能
        $keywords = Request::param('keywords');
        if ( !empty($keywords) ) {
            $map[] = ['login_username', 'like', '%'.$keywords.'%'];
        }

        // 定义分页参数
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // 判断是否超级管理员
        if ( Session::get('admin_role_id') == 1 ) {
            // 获取到所有登录日志
            $logList = LoginLogModel::where($map)
                -> order('id', 'desc')
                -> page($page, $limit)
                -> select();
        } else {
            $map[] = ['login_username', '=', Session::get('admin_username')];
        }

        // 获取到所有登录日志
        $logList = LoginLogModel::where($map)
            -> order('id', 'desc')
            -> page($page, $limit)
            -> select();



        $total = count(LoginLogModel::where($map)->select());
        $result = array("code" => 0, "msg" => "查询成功", "count" => $total, "data" => $logList);
        return json($result);

        // 3. 设置模板变量
        $this -> view -> assign('logList', $logList);

        // 4. 渲染模板
        return $this -> view -> fetch('index');
    }

    // 删除登录日志
    public function delete()
    {
        if ( Request::isAjax() ) {
            // 执行删除操作
            try {
                $id = Request::param('id');
                LoginLogModel::where('id', $id) -> delete();
            } catch (\Exception $e) {
                return resMsg(0, '日志删除失败' . '<br>' . $e->getMessage(), 'index' );
            }
            return resMsg(1, '日志删除成功', 'index');
        } else {
            return resMsg(-1, '请求类型错误', 'index');
        }
    }
}
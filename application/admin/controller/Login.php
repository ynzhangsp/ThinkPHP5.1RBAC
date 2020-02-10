<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;
use think\Controller;
use think\facade\Request;
use app\admin\common\model\Loginlog;
use app\admin\common\model\Admin;
use think\facade\Session;
use Jenssegers\Agent\Agent;
use app\admin\controller\IPQuery;

class Login extends Controller
{
    // 登录页面
    public function index()
    {
        $this -> view -> assign('title', '管理员登录');
        return $this -> view ->fetch('index');
    }

    // 插入登录日志
    public function insertLoginLog($username, $status)
    {
        // 获取登录客户端信息
        $agent = new Agent();
        $os = $agent -> platform() . $agent -> version($agent -> platform());
        $browser = $agent -> browser() . $agent -> version($agent -> browser());

        // 实例化纯真IP查询类
        $ipQuery = new IPQuery();

        // 管理员登录信息
        $ip = $this -> request -> ip();
        $data = [
            'login_username' => $username,
            'login_status'   => $status,
            'login_time'     => time(),
            'login_ip'       => $ip,
            'login_area'     => $ipQuery -> query($ip)['pos'] . $ipQuery -> query($ip)['isp'],
            'login_client_os'=> $os,
            'login_client_browser'  => $browser
        ];

        // 插入登录日志
        try {
            Loginlog::create($data);
        } catch (\Exception $e) {
            return resMsg(0, '登录日志写入失败' . '<br>' . $e->getMessage(), 'index' );
        }
        return true;
    }

    // 管理员登录验证
    public function checkLogin()
    {
        if ( Request::isPost() ) {
            // 获取管理员提交的登录数据
            $data = Request::param();

            // 判断验证是否正确
            if ( !captcha_check($data['captcha']) ) {
                return resMsg(-2, '验证码错误', 'index');
            }

            // 获取管理员信息
            $adminInfo = Admin::where('username', $data['username']) -> find();

            // 判断用户登录信息是否存在
            if ( empty($adminInfo) ) {
                // 写入登录日志
                $this->insertLoginLog($data['username'], 0);
                return resMsg(0, '用户名或密码错误', 'index');
            }

            // 判断用户密码是否正确
            if ( !checkPassword($data['password'], $adminInfo['password']) ) {
                // 写入登录日志
                $this->insertLoginLog($data['username'], 0);
                return resMsg(0, '用户名或密码错误', 'index');
            }

            // 登录成功，存储管理员登录信息到session中
            Session::set('admin_username', $adminInfo['username']);
            Session::set('admin_id', $adminInfo['id']);
            Session::set('admin_role_id', $adminInfo['role_id']);

            // 更新管理员表的最后登录时间
            Admin::where('id', $adminInfo['id']) -> update(['last_login_time' => time()]);

            // 插入登录日志表
            // 写入登录日志
            $this->insertLoginLog($data['username'], 1);

            // 返回登录成功信息到前台
            return resMsg(1, '登录成功', '/admin');
        } else {
            return resMsg(-1, '请求类型错误', 'index');
        }
    }

    // 管理员退出
    public function logout()
    {
        session('admin_id', null);
        session('admin_username', null);
        session('admin_role_id', null);

        $this -> redirect('login/index');
    }
}
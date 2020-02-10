<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;
use rbac\Rbac;
use think\App;
use think\facade\Session;
use think\facade\Request;
use app\admin\common\model\Admin;


class Index extends Base
{
    // 后台管理首页
    public function index()
    {
        // 实例化RBAC类
        $rbac = Rbac::instance();

        // 根据角色获取菜单
        $menu = $rbac -> getAuthMenu(Session::get('admin_role_id'));

        // 设置模板变量
        $this -> view -> assign('title', 'ThinkPHP5.1 RBAC权限管理系统');
        $this -> view -> assign('menu', $menu);

        // 渲染模板
        return $this -> fetch('index');
    }

    // 后台管理控制台
    public function home()
    {
        // 设置模板变量
        $this -> view -> assign([
            'title' => '控制台',
            'tp_version' => App::VERSION
        ]);

        // 渲染模板
        return $this -> view -> fetch('home');

    }

    // 后台管理员修改密码
    public function editPassword()
    {
        // 获取当前管理员信息
        $id = Session::get('admin_id');
        $username = Session::get('admin_username');

        // 设置模板变量
        $this -> view -> assign([
            'id' => $id,
            'username' => $username,
            'title' => '修改密码'
        ]);

        // 渲染模板
        return $this -> view -> fetch('editpassword');
    }

    // 执行修改密码操作
    public function doEditPwd()
    {
        // 获取的用户提交的信息
        $data = Request::param();

        // 判断原密码是否正确
        $password = Admin::where('id', $data['id']) -> find()['password'];

        if ( !checkPassword($data['original_password'], $password) ) {
            return resMsg(-1, '原密码错误，请重新输入', 'editPassword');
        }

        $datas = [
            'password' => makePassword($data['password']),
            'update_time' => time()
        ];

        // 执行密码修改操作
        try {
            $admin = Admin::where('id', $data['id']) -> update($datas);
        } catch (\Exception $e) {
            return resMsg(0, '密码修改失败' . '<br>' . $e->getMessage(), 'edit' );
        }
        return resMsg(1, '密码修改成功', 'index');
    }
}
<?php
/**
 * RBAC权限管理类
 */

namespace rbac;


use app\admin\common\model\Role;
use app\admin\common\model\Node;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\Loader;
use think\Db;

class Rbac
{
    /**
     * @var object 对象实例
     */
    protected static $instance;

    /**
     * 当前请求实例
     * @var Request
     */
    protected $request;

    // 默认配置
    protected $config = [
        'auth_on' => 1, // 权限开关
        'auth_type' => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group' => 'think_role', // 用户组数据表名
        'auth_group_access' => 'think_admin_role', // 用户-用户组关系表
        'auth_rule' => 'think_node', // 权限规则表
        'auth_user' => 'think_admin', // 用户信息表
    ];


    // 定义无需权限检测的方法
    private $skipAuthMap = [
        'login/index' => 1,
        'index/index' => 1,
        'index/home' => 1
    ];

    /**
     * 类架构函数
     * Auth constructor.
     */
    public function __construct()
    {
        //可设置配置项 auth, 此配置项为数组。
        if ($rbac = Config::get('rbac')) {
            $this -> config = array_merge($this -> config, $rbac);
        }
        // 初始化request
        $this -> request = Request::instance();
    }

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return \think\facade\Request
     */
//    public static function instance($options = [])
//    {
//        if (is_null(self::$instance)) {
//            self::$instance = new static($options);
//        }
//        return self::$instance;
//    }

    // 初始化权限检测实例
    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 权限检测方法
     * @param $rules
     * @param $roleId
     * @return bool
     */
    public function authCheck($rules, $roleId)
    {
        if ( 1 == $roleId ) {
            return true;
        }

        $roleModel = new Role();
        $roleAuthNodeMap = $roleModel -> getRoleAuthNodeMap($roleId)['data'];

        if (empty($roleAuthNodeMap)) {
            return false;
        }
        if (!isset($roleAuthNodeMap[$rules])) {
            return false;
        }
        return true;
    }

    /**
     * 获取权限菜单
     * @param $roleId
     * @return array
     */
    public function getAuthMenu($roleId)
    {
        $nodeModel = new Node();
        $menu = $nodeModel->getRoleMenuMap($roleId)['data'];
        return makeTree($menu);
    }

    /**
     * 获取无需验证权限
     * @return array
     */
    public function getSkipAuthMap()
    {
        return $this->skipAuthMap;
    }

    /**
     * 设置无需验证权限
     * @param array $skipAuthMap
     */
    public function setSkipAuthMap($skipAuthMap)
    {
        $this->skipAuthMap = array_merge($this->getSkipAuthMap(), $skipAuthMap);
    }
}
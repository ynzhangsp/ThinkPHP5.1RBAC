<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use rbac\Rbac;
use think\Db;
use ipquery\IPQuery;

/**
 * 通过加盐方式生成加密密码
 * @param $password
 * @return string
 */
function makePassword($password) {
    return sha1(md5($password . config('whisper.salt')));
}

/**
 * 检测密码
 * @param $dbPassword
 * @param $inputPassword
 * @return bool
 */
function checkPassword($inputPassword, $dbPassword) {
    return ( makePassword($inputPassword) == $dbPassword );
}

/**
 * 获取当前MySQL版本
 * @return string
 */
function getMysqlVersion() {
    $res = Db::query('select VERSION() as sqlversion');
    $data['MySQL_Version'] = $res[0]['sqlversion'];
    return $res[0]['sqlversion'];
}

/**
 * 生成Layui子孙树，格式化后台左侧菜单列表
 * @param $data
 * @return array
 */
function makeTree($data) {

    $res = [];
    $tree = [];

    // 整理数组
    foreach ($data as $key => $vo) {
        $res[$vo['id']] = $vo;
        $res[$vo['id']]['children'] = [];
    }
    unset($data);

    // 查询子孙
    foreach ($res as $key => $vo) {
        if($vo['pid'] != 0){
            $res[$vo['pid']]['children'][] = &$res[$key];
        }
    }

    // 去除杂质
    foreach ($res as $key => $vo) {
        if($vo['pid'] == 0){
            $tree[] = $vo;
        }
    }
    unset($res);

    return $tree;
}

/**
 * 格式化dump调试函数
 * @param $data
 */
function dump($data) {
    echo "<pre>";
    print_r($data);
}

/**
 * 按钮检测
 * @param $input
 * @return bool
 */
function buttonCheck($input)
{
    $rbac = Rbac::instance();
    return  $rbac->authCheck($input, session('admin_role_id'));
}

/**
 * 返回json格式信息
 * @param $status
 * @param $message
 * @param $url
 * @return array
 */
function resMsg($status, $message, $url)
{
    return ['status' => $status, 'message' => $message, 'url' => $url];
}

/**
 * 模型返回标准函数
 * @param $code
 * @param $data
 * @param $msg
 * @return array
 */
function modelReMsg($code, $data, $msg) {

    return ['code' => $code, 'data' => $data, 'msg' => $msg];
}

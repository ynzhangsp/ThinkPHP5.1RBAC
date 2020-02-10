<?php
/**
 * 管理员表的验证器
 */

namespace app\admin\common\validate;


use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|length:5,16|alphaNum',
        'password|密码'   => 'require|length:5,16|alphaNum',
        'captcha|验证码'  => 'require|chatcha'
    ];
}
<?php
/**
 * 管理员登录日志表模型
 */

namespace app\admin\common\model;


use think\Model;
use think\facade\Log;

class Loginlog extends Model
{
    // 定义主键和数据表
    protected $pk = 'id';
    protected $table = 'think_login_log';

    // 定义自动时间戳和数据格式
    protected $autoWriteTimestamp = true;
    protected $createTime = 'login_time';
    protected $dateFormat = 'Y-m-d H:i:s';
}
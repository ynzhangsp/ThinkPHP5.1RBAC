<?php
/**
 * 系统日志类
 */
namespace log;

class Log
{
    public static function write($content)
    {
        $controller = lcfirst(request()->controller());
        $action = request()->action();
        $checkInput = $controller . '/' . $action;

        $logModel = new Operate();
        $logModel->writeLog([
            'operator' => session('admin_user_name'),
            'operator_ip' => request()->ip(),
            'operate_method' => $checkInput,
            'operate_desc' => $content,
            'operate_time' => date('Y-m-d H:i:s')
        ]);
    }
}
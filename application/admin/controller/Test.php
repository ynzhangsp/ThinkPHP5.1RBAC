<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;

class Test extends Base
{
    /**
     * 测试方法
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        return $this -> view -> fetch('index', ['title' => '测试页面']);
    }
}
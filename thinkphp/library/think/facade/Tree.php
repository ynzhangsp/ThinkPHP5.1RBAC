<?php


namespace think\facade;


use think\Facade;

class Tree extends Facade
{
    static public $treeList = array(); // 存放无限级分类结果

    protected static function createTree($data, $pid = 0)
    {
        foreach ($data as $key => $value)
        {
            if ( $value['pid'] == $pid ) {
                self::$treeList[] = $value;
                unset($data[$key]);
                self::createTree($data, $value['id']);
            }
        }
        return self::$treeList;
    }
}
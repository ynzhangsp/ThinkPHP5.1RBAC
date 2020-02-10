<?php
/**
 * 管理员权限节点表模型
 */

namespace app\admin\common\model;


use think\Model;

class Node extends Model
{
    // 定义主键和数据表
    protected $pk = 'id';
    protected $table = 'think_node';

    // 定义自动时间戳和数据格式
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 获取角色菜单集合
     * @param $roleId
     * @return array
     */
    public function getRoleMenuMap($roleId)
    {
        try {
            $res = [];
            if (1 == $roleId) {
                $res = $this -> field('id, name as title, pid, path, icon')
                    ->where('is_menu', 2)->select()->toArray();
            } else {
                $roleModel = new Role();
                $roleInfo = $roleModel->getRoleInfoById($roleId)['data'];

                if (!empty($roleInfo)) {
                    $res = $this->field('id, name as title, pid, path, icon')
                        -> whereIn('id', $roleInfo['rules'])
                        -> where('is_menu', 2)
                        -> select()
                        -> toArray();
                }
            }
        } catch (\Exception $e) {
            return modelReMsg(-1, [], $e->getMessage());
        }
//        dump($res);
        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 根据节点id获取节点信息
     * @param $ids
     * @return array
     */
    public function getNodeInfoByIds($ids)
    {
        try {
            $res = $this -> whereIn('id', $ids) -> select() -> toArray();
        }catch (\Exception $e) {
            return modelReMsg(-1, '', $e -> getMessage());
        }
        return modelReMsg(0, $res, 'ok');
    }
}
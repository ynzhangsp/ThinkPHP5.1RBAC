<?php
/**
 * 角色表模型
 */

namespace app\admin\common\model;


use think\Model;
use think\facade\Cache;
//use app\admin\common\model\Node;

class Role extends Model
{
    // 定义主键和数据表
    protected $pk = 'id';
    protected $table = 'think_role';

    // 定义自动时间戳和数据格式
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 根据角色ID获取角色信息
     * @param $id
     * @return array
     */
    public function getRoleInfoById($id)
    {
        try {
            $info = $this -> where('id', $id) -> findOrEmpty() -> toArray();
        }catch (\Exception $e) {
            return modelReMsg(-1, '', $e->getMessage());
        }
        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 根据角色ID获取角色对应的权限节点数组
     * @param $roleId
     * @return array
     */
    public function getRoleAuthNodeMap($roleId)
    {
        $map = Cache::get("role_" . $roleId . "_map");

        if (empty($map)) {
            try {
                $res = $this -> where('id', $roleId) -> find();
//                dump($res);exit;
                if (!empty($res)) {
                    $map = $this -> cacheRoleNodeMap($res['rules'], $roleId);
                }
            }catch (\Exception $e) {
                return modelReMsg(-1, $map, $e->getMessage());
            }
        }
        return modelReMsg(0, $map, 'ok');
    }

    /**
     * 缓存角色对应节点信息
     * @param $roleNode
     * @param $roleId
     * @return array
     */
    public function cacheRoleNodeMap($roleNode, $roleId)
    {
        // 获取节点信息
        $nodeModel = new Node();
        $nodeInfo = $nodeModel -> getNodeInfoByIds($roleNode);

        $map = [];
        if ( !empty($nodeInfo['data']) ) {
            foreach ( $nodeInfo['data'] as $node ) {
                if (empty($node['path']) || '#' == $node['path']) continue;
                $map[$node['path']] = $node['id'];
            }
            Cache::set("role_" . $roleId . "_map", $map);
        }
        return $map;
    }
}
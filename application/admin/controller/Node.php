<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;
use app\admin\common\model\Node as NodeModel;
use think\facade\Request;

class Node extends Base
{
    // 权限节点管理首页
    public function index()
    {
        $this -> view -> assign('title', '权限管理');
        return $this -> view -> fetch('index');
    }

    // 权限节点列表
    public function nodeList()
    {
        $map = [];
        $keywords = Request::param('keywords');
        if ( !empty($keywords) ) {
            $map[] = ['name', 'like', '%' . $keywords . '%'];
        }

        $nodeList = NodeModel::where($map)
            -> order('sort', 'asc')
            -> field('id, name as title, path, pid, sort, icon, is_menu, status, create_time, update_time')
            -> select();
//            -> toArray();
        $total = count($nodeList);
        $result = array("code" => 0, "count" => $total, "data" => $nodeList);
        return json($result);
    }

    // 添加权限节点
    public function add()
    {
        $pid = Request::param('pid');
        $parentNode = Request::param('parentNode');

        if ( $pid == 0 ) {
            $title = "添加顶级节点";
        } else {
            $title = "添加子节点";
        }
        $this -> view -> assign(
            [
                'title' => $title,
                'pid' => $pid,
                'parentNode' => $parentNode
            ]
        );
        return $this -> view -> fetch('add');
    }

    // 选择图标页面
    public function icon()
    {
        return $this -> view -> fetch('icon');
    }

    // 执行添加节点操作
    public function doAdd()
    {
        // 获取用户提交的信息
        $data = Request::param();

        // 执行添加操作
        try {
            $node = NodeModel::where('name', $data['name']) -> find();
            if ( !empty($role)) {
                return resMsg(-1, '节点名称已经存在，不能重复添加', 'add');
            }
            NodeModel::create($data);
        } catch (\Exception $e) {
            return resMsg(0, '节点添加失败' . '<br>' . $e->getMessage(), 'add' );
        }
        return resMsg(1, '节点添加成功', 'index');
    }

    // 编辑节点
    public function edit()
    {
        // 获取节点id
        $nodeId = Request::param('id');

        // 根据节点id查询要更新的节点信息
        $nodeInfo = NodeModel::where('id', $nodeId) -> find();

        // 根据父ID获取父节点名称
        if ( $nodeInfo['pid'] == 0 ) {
            $parentNode = '顶级节点';
        } else {
            $parentNode = NodeModel::where('id', $nodeInfo['pid']) -> field('name') -> find()['name'];
        }

        // 设置模板变量
        $this -> view -> assign('title', '编辑节点');
        $this -> view -> assign('nodeInfo', $nodeInfo);
        $this -> view -> assign('parentNode', $parentNode);

        // 渲染模板
        return $this -> view -> fetch('edit');
    }

    // 执行编辑节点操作
    public function doEdit()
    {
        // 1. 获取的用户提交的信息
        $data = Request::param();

        // 执行编辑操作
        try {
            $node = NodeModel::where('name', $data['name']) -> where('id', '<>', $data['id']) -> find();
            if ( !empty($node)) {
                return resMsg(-1, '节点名称已经存在，请重新修改', 'edit');
            }
            NodeModel::update($data);
        } catch (\Exception $e) {
            return resMsg(0, '节点编辑失败' . '<br>' . $e->getMessage(), 'edit' );
        }
        return resMsg(1, '节点编辑成功', 'index');
    }

    // 删除节点
    public function delete()
    {
        if ( Request::isAjax() ) {
            // 执行删除操作
            try {
                $id = Request::param('id');
                NodeModel::where('id', $id) -> delete();
            } catch (\Exception $e) {
                return resMsg(0, '节点删除失败' . '<br>' . $e->getMessage(), 'index' );
            }
            return resMsg(1, '节点删除成功', 'index');
        } else {
            return resMsg(-1, '请求类型错误', 'index');
        }
    }

    // 变更状态
    public function setStatus()
    {
        // 1. 获取用户提交的数据
        $data = Request::param();

        // 2. 取出数据
        $id = $data['id'];
        $status = $data['status'];

        // 3. 更新数据，判断显示状态，如果为1则更改为0，如果为0则更改为1
        try {
            if ( $status == 1 ) {
                NodeModel::where('id', $id)
                    ->data('status', 0)
                    ->update();
            } else {
                NodeModel::where('id', $id)
                    -> data('status', 1)
                    -> update();
            }
        } catch (\Exception $e) {
            return resMsg(0, '<i class="iconfont">&#xe646;</i> 操作失败，请检查' . '<br>' . $e->getMessage(), 'index' );
        }
        return resMsg(1, '<i class="iconfont">&#xe645;</i> 状态变更成功', 'index');
    }
}
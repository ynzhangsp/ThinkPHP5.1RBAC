<?php


namespace app\admin\controller;


use app\admin\common\controller\Base;
use app\admin\common\model\Role as RoleModel;
use app\admin\common\model\Admin;
use app\admin\common\model\Node;
use think\facade\Request;
use think\facade\Tree;

class Role extends Base
{
    // 角色管理首页
    public function index()
    {
        $this -> view -> assign('title', '角色管理');
        return $this -> view -> fetch('index');
    }

    // 角色列表
    public function roleList()
    {
        $map = [];
        // 搜索功能
        $keywords = Request::param('keywords');
        if ( !empty($keywords) ) {
            $map[] = ['name', 'like', '%'.$keywords.'%'];
        }

        // 定义分页参数
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // 获取角色信息
        $roleList = RoleModel::where($map)
            -> page($page, $limit)
            -> order('id', 'desc')
            -> select();
        $total = count(RoleModel::where($map)->select());
        $result = array("code" => 0, "msg" => "查询成功", "count" => $total, "data" => $roleList);
        return json($result);

        // 3. 设置模板变量
        $this -> view -> assign('roleList', $roleList);

        // 4. 渲染模板
        return $this -> view -> fetch('index');
    }

    // 添加角色
    public function add()
    {
        $this -> view -> assign('title', '添加角色');
        return $this -> view -> fetch('add');
    }

    // 执行角色添加
    public function doAdd()
    {
        // 获取的用户提交的信息
        $data = Request::param();

        // 执行添加操作
        try {
            $role = RoleModel::where('name', $data['name']) -> find();
            if ( !empty($role)) {
                return resMsg(-1, '角色名称已经存在，不能重复添加', 'add');
            }
            $data['rules'] = '1,2,3';
            RoleModel::create($data);
        } catch (\Exception $e) {
            return resMsg(0, '角色添加失败' . '<br>' . $e->getMessage(), 'add' );
        }
        return resMsg(1, '角色添加成功', 'index');
    }

    // 编辑角色页面
    public function edit()
    {
        // 获取角色id
        $roleId = Request::param('id');

        // 根据角色id查询要更新的角色信息
        $roleInfo = RoleModel::where('id', $roleId) -> find();

        // 设置模板变量
        $this -> view -> assign('title', '编辑角色');
        $this -> view -> assign('roleInfo', $roleInfo);

        // 渲染模板
        return $this -> view -> fetch('edit');
    }

    // 执行编辑角色操作
    public function doEdit()
    {
        // 1. 获取的用户提交的信息
        $data = Request::param();

        // 执行编辑操作
        try {
            $role = RoleModel::where('name', $data['name']) -> where('id', '<>', $data['id']) -> find();
            if ( !empty($role)) {
                return resMsg(-1, '角色名称已经存在，请重新修改', 'edit');
            }
            RoleModel::update($data);
        } catch (\Exception $e) {
            return resMsg(0, '角色编辑失败' . '<br>' . $e->getMessage(), 'edit' );
        }
        return resMsg(1, '角色编辑成功', 'index');
    }

    // 删除角色
    public function del()
    {
        $id = Request::param('id');

        // 执行删除操作
        try {
            // 根据角色id查询管理员信息
            $admins = Admin::where('role_id', $id) -> select() -> toArray();
            if ( !empty($admins) ) {
                return resMsg(-1, '当前角色下存在生效的管理员，不允许删除', 'index' );
            }
            RoleModel::where('id', $id) -> delete();
        } catch (\Exception $e) {
            return resMsg(0, '角色删除失败' . '<br>' . $e->getMessage(), 'index' );
        }
        return resMsg(1, '角色删除成功', 'index');
    }

    // 角色授权页面
    public function auth(){
        // 获取角色id
        $roleId = Request::param('id');

        // 根据角色id查询角色信息
        $roleInfo = RoleModel::where('id', $roleId) -> find();

        // 获取权限列表
        $nodes = Node::order('sort', 'asc') -> select();
        // 调用think\facade\Tree自定义无限级分类方法
        $nodes = Tree::createTree($nodes);

        $json = array();  // $json用户存放最新数组，里面包含当前用户组是否有相应的权限
        $rules = explode(',', $roleInfo['rules']);
        foreach ($nodes as $node) {
            $res = in_array($node['id'], $rules);
            $data = array(
                'nid' => $node['id'],
                'checked' => $node['id'],
                'parentid' => $node['pid'],
                'name' => $node['name'],
//                'id' => $node['id'] . '_' . $node['level'],
                'id' => $node['id'],
                'checked' => $res ? true : false
            );
            $json[] = $data;
        }

        // 5. 设置模板变量
        $this -> view -> assign('title', '角色授权');
        $this -> view -> assign('roleInfo', $roleInfo);
        $this -> view -> assign('json', json_encode($json));
        $this -> view -> assign('roleId', $roleId);

        // 渲染模板
        return $this -> view -> fetch('auth');
    }

    // 处理角色授权 添加角色-权限表
    public function doAuth()
    {
        if ( Request::isAjax() ) {
            // 1. 获取的用户提交的信息
            $data = Request::post();

            // 2. 取出数据
            $id = $data['id'];
            $rules = $data['rules'];

            // 3. 变更当前角色拥有的权限规则
            if ( isset($rules) ) {
                $datas = '';
                foreach ( $rules as $value ) {
                    $tmp = explode('_', $value);
                    $datas .= ',';
                    $datas .= $tmp[0];
                }
                $datas = substr($datas, 1);
                $res = RoleModel::where('id', $id) -> update(['rules' => $datas]);

                if ( true == $res ) {
                    return ['status' => 1, 'message' => '角色授权操作成功', 'url' => 'index'];
                }
                return ['status' => 0, 'message' => '角色授权操作失败，请检查'];
            } else {
                return ['status' => 0, 'message' => '未接收到权限节点数据，请检查'];
            }
        } else {
            $this -> error("请求类型错误");
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
                RoleModel::where('id', $id)
                    ->data('status', 0)
                    ->update();
            } else {
                RoleModel::where('id', $id)
                    -> data('status', 1)
                    -> update();
            }
        } catch (\Exception $e) {
            return resMsg(0, '<i class="iconfont">&#xe646;</i> 操作失败，请检查' . '<br>' . $e->getMessage(), 'index' );
        }
        return resMsg(1, '<i class="iconfont">&#xe645;</i> 状态变更成功', 'index');
    }
}
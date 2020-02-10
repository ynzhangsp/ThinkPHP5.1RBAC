![](https://box.kancloud.cn/5a0aaa69a5ff42657b5c4715f3d49221) 

ThinkPHP 5.1.39（LTS版本） —— RBAC权限管理系统
===============

#### 介绍
ThinkPHP 5.1 RBAC权限管理系统，实现了结余角色的权限管理，本系统是基于权限节点进行权限认证，权限控制菜单显示隐藏。

#### 软件架构
1.  前端框架：layui 2.5.6 
2.  后端框架：ThinkPHP 5.1.39 LTS
3.  后端界面基于layuimini：http://layuimini.99php.cn/ 感谢作者

#### 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件
│  └─tags.php           应用行为扩展定义文件
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

#### 安装教程

1.  搭建开发环境，推荐使用PhpStudy；
2.  下载或Git项目代码到本地，将代码拷贝至：E:/phpstudy_pro/www/目录下；
3.  通过PhpStudy面板添加网站，并指向项目根目录的public/目录下；
4.  新建数据库think_auth（可自定义数据库名称，然后在config/database.php文件中修改数据库配置），导入数据库文件think_auth.sql;
5.  使用浏览器（推荐chrome浏览器）访问：http://yourdomain.com/admin，默认用户名/密码：admin。

#### 使用说明

1.  角色管理：添加角色、编辑角色、删除角色、角色授权
2.  权限管理：添加权限、编辑权限、删除权限、
3.  用户管理：添加用户、编辑用户、删除用户
4.  菜单管理：添加菜单、编辑菜单、删除菜单
5.  文章管理：模拟菜单，未开发功能

#### 预览截图
![后台首页](https://images.gitee.com/uploads/images/2019/1226/142138_da813866_1163529.png "01.png")
![角色管理](https://images.gitee.com/uploads/images/2019/1226/142151_345a4c50_1163529.png "02.png")
![权限管理](https://images.gitee.com/uploads/images/2019/1226/142202_79b09316_1163529.png "03.png")
![角色授权](https://images.gitee.com/uploads/images/2019/1226/142210_4b84dfc0_1163529.png "04.png")
![菜单管理](https://images.gitee.com/uploads/images/2019/1226/142219_14e84413_1163529.png "05.png")
![用户管理](https://images.gitee.com/uploads/images/2019/1226/142228_5a7b5f4d_1163529.png "06.png")

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request


#### 码云特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  码云官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解码云上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是码云最有价值开源项目，是码云综合评定出的优秀开源项目
5.  码云官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  码云封面人物是一档用来展示码云会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)


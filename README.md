# laravel-admin
Laravel Admin 是用 laravel + element admin前端框架前后端分离开发，自带用户组权限系统及动态菜单管理

## 安装

- composer install  
- 配置nginx 本地域名local.admin.com 指向/public目录 
- npm install
- 修改.env的配置信息
- php artisan key:generate
- php artisan migrate 导入数据库
- npm install 安装前端依赖
- npm run dev

## 使用
访问地址
http://local.admin.com/admin 注：可根据实际修改

默认管理员账号 admin/123456 

npm run watch 可在开发时运行，实时兼控前端文件修改

npm run prod 生产环境打包

## 注意

设置新的权限后需要重新登录才会生效。

本项目未经严格测试，可能存在bug，仅供参考学习。

如果感觉此项目帮助了您，请给颗星支持一下。

## 后端
### 技术栈
- Laravel 7.0

## 前端
### 技术栈
- Vue全家桶
- [Vue-element-admin](https://panjiachen.github.io/vue-element-admin-site/zh/)
- Axios
- Laravel Mix

## License

This framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

这是一个基于thinkphp5开发的一个监听域名https证书有效期的系统，可以自动发送邮件提醒管理员。 

核心功能就两个：查询域名https信息、发送邮件提醒

#### 页面展示
![域名](./readme/domain.png "域名管理.png")
![邮件记录](./readme/mail.png "邮件记录.png")
![通知栏](./readme/notice.jpg "通知栏.png")
![邮件内容](./readme/email-info.jpg "邮件内容.png")


## 安装


1、使用composer安装

~~~
composer install
~~~

2、配置域名（下面以monitor.test.top为例）
~~~
server {
        listen        80;
        server_name  abc.manager.top;
        root   "/https-manager/public";        
        location ~ \.php(.*)$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO  $fastcgi_path_info;
            fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
            include        fastcgi_params;
        }
}
~~~
3、Nginx伪静态
~~~
location / {
   if (!-e $request_filename) {
   	rewrite  ^(.*)$  /index.php?s=/$1  last;
    }
}
~~~

4、复制sqlite数据库文件

（1）自动复制
    - composer install 命令执行完成后，自动复制数据库文件

（2）如果发生未知错误，可以进行手动复制
    - 可以直接执行 `php think copy_db_file` 快速进行复制
      
    - 复制install/struct.db到data/db/manager.db 数据库基础文件
    (main.sql是结构文件，struct.db是数据库文件可以直接用)
 

### 具体检测

### 方式一：workerman定时器

具体命令：
```
# 守护进程方式启动，每15秒执行一次定时器，定时检查域名，以及发送提醒邮件
php think timer start --d --i 15

# 停止
php think timer stop

# 重启
php think timer restart --d --i 15

```

注：如果修改了代码，请务必重启定时器

### 方式二：curl请求或linux定时任务

### 一、检测域名有效期
##### 定时任务api接口地址（如果域名数量小于30建议每天一次）
 + curl模式获取域名证书状态
~~~
http://abc.manager.top/task/check_domain
~~~
 + php命令行模式（linux添加crontab,每5分钟执行一次）
 ~~~
*/5 * * * * php think check_domain
 ~~~


### 二、发送邮件通知

##### 定时任务api接口地址（视任务多少，建议间隔大于5分钟）
 + curl模式发送（宝塔模式）
~~~
http://abc.manager.top/task/send_email
~~~
 + php命令行模式（linux添加crontab,每5分钟执行一次）
 ~~~
*/5 * * * * php think send_email
 ~~~

### 三、后台管理
浏览器中访问admin进入管理后台

~~~
http://abc.manager.top/admin
~~~

### 四、其它说明
  + config/admin.php 管理员的登录账号信息
  + config/menus.php 后台管理菜单
  + config/notice.php 配置距离多少天过期可以发送邮件，以及是否立即发送提醒邮件（默认开启）
  + data/db/manager.db 以及sqlite数据库文件（不添加到版本控制）
  + 确保runtime、data目录可写

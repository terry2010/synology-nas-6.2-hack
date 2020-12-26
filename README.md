# synology-nas-6.2-hack
对群晖nas的一些修改，使其使用更方便

使用环境 synology ds920+

### sshd

将sshd替换为centos-release-7-6.1810.2.el7.centos.x86_64 上的相同版本sshd， 使其支持sftp


### nas-file-list

列出某个目录下所有文件大小的php脚本。 需要在群晖上开启ssh登陆， 并登陆到群晖以后，在命令行下执行

### lrzsz

将文件拷入/usr/bin/ 即可使用 rz 和sz 命令

### telnet

将文件拷入/usr/bin/ 即可使用 telnet 命令

### 使用golang

去golang 官网， 按照教程正常安装即可， export的环境变量需要写到/etc/profile 里才能生效

### 修改cron
```
vim /etc/crontab
synoservice -restart crond
```

### 增加ipkg 支持（替代yum/apt）
套件中心添加源
```
http://www.cphub.net/
```
安装套件 
```
perl
Easy Bootstrap Installer
iPKGui
```
安装完毕后，登陆nas，在 /etc/profile 最后一行增加
```
export PATH="$PATH:/opt/bin"
```
之后就可以用  

ipkg list |grep golang 搜索包

ipkg install screen 安装包

### 查看docker 启动参数
```
ipkg install py3-pip
pip3 install --upgrade pip
pip3 install runlike
```
此刻可能出现找不到pip命令和runlike命令的情况。使用 
```
pip3 uninstall runlike
```
查看安装路径， 把安装路径增加到 /etc/profile里面

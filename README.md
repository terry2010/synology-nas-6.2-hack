# synology-nas-6.2-hack
对群晖nas的一些修改，使其使用更方便

使用环境 synology ds920+

### sshd

将sshd替换为centos-release-7-6.1810.2.el7.centos.x86_64 上的相同版本sshd， 使其支持sftp


### nas-file-list

列出某个目录下所有文件大小的php脚本。 需要在群晖上开启ssh登陆， 并登陆到群晖以后，在命令行下执行

### lrzsz

将文件拷入/usr/bin/ 即可使用 rz 和sz 命令

### 使用golang

去golang 官网， 按照教程正常安装即可， export的环境变量需要写到/etc/profile 里才能生效



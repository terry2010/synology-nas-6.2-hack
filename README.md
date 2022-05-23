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

用docker启动的centos镜像， 如果想用systemctl等工具， 需要

1. 把启动参数结尾的
```
/bin/bash
```
改成
```
 /usr/sbin/init
```

2. 增加 --privileged=true   （在创建界面里选择高权限没用）

yum install NetworkManager  NetworkManager-tui
 systemctl start NetworkManager  
 nmcli c add type bridge ifname docker0 con-name docker0
 
 ### 安装 youtube-dl
 > 首先要安装python
 
```
sudo curl -L https://yt-dl.org/downloads/latest/youtube-dl -o /usr/local/bin/youtube-dl
sudo chmod a+rx /usr/local/bin/youtube-dl
```

使用方式： 

查看分辨率：
```
youtube-dl --proxy 192.168.50.181:7890 -F https://www.youtube.com/watch?v=xxxxxxxxx

[youtube] xxxxx: Downloading webpage
[info] Available formats for eBD8Cz6IJRg:
format code  extension  resolution note
249          webm       audio only tiny   50k , webm_dash container, opus @ 50k (48000Hz), 152.11MiB
250          webm       audio only tiny   64k , webm_dash container, opus @ 64k (48000Hz), 193.78MiB
251          webm       audio only tiny  125k , webm_dash container, opus @125k (48000Hz), 375.61MiB
140          m4a        audio only tiny  129k , m4a_dash container, mp4a.40.2@129k (44100Hz), 388.96MiB
160          mp4        256x144    144p   75k , mp4_dash container, avc1.4d400c@  75k, 30fps, video only, 227.84MiB
278          webm       256x144    144p   78k , webm_dash container, vp9@  78k, 30fps, video only, 235.08MiB
242          webm       426x240    240p  141k , webm_dash container, vp9@ 141k, 30fps, video only, 424.65MiB
133          mp4        426x240    240p  172k , mp4_dash container, avc1.4d4015@ 172k, 30fps, video only, 518.50MiB
243          webm       640x360    360p  264k , webm_dash container, vp9@ 264k, 30fps, video only, 795.31MiB
134          mp4        640x360    360p  343k , mp4_dash container, avc1.4d401e@ 343k, 30fps, video only, 1.01GiB
244          webm       854x480    480p  468k , webm_dash container, vp9@ 468k, 30fps, video only, 1.38GiB
135          mp4        854x480    480p  651k , mp4_dash container, avc1.4d401f@ 651k, 30fps, video only, 1.91GiB
247          webm       1280x720   720p 1010k , webm_dash container, vp9@1010k, 30fps, video only, 2.96GiB
136          mp4        1280x720   720p 1286k , mp4_dash container, avc1.64001f@1286k, 30fps, video only, 3.77GiB
248          webm       1920x1080  1080p 1713k , webm_dash container, vp9@1713k, 30fps, video only, 5.03GiB
137          mp4        1920x1080  1080p 2568k , mp4_dash container, avc1.640028@2568k, 30fps, video only, 7.54GiB
271          webm       2560x1440  1440p 5973k , webm_dash container, vp9@5973k, 30fps, video only, 17.53GiB
313          webm       3840x2160  2160p 12277k , webm_dash container, vp9@12277k, 30fps, video only, 36.02GiB
18           mp4        640x360    360p  423k , avc1.42001E, 30fps, mp4a.40.2 (44100Hz), 1.24GiB
22           mp4        1280x720   720p 1414k , avc1.64001F, 30fps, mp4a.40.2 (44100Hz) (best)
```

下载需要的分辨率
```
youtube-dl --proxy 192.168.50.181:7890 -f 137 https://www.youtube.com/watch?v=xxxxxxxx
```

使用aria2 
```
 youtube-dl --proxy 192.168.50.181:7890  --external-downloader aria2c --external-downloader-args "--all-proxy=http://192.168.50.181:7890  --file-allocation=prealloc -s 16 -j 16 -x 16 -k 1M" -f 248 https://www.youtube.com/watch?v=xxxxxxxx
 ```
> aria2在群晖7.1 上下载https 会提示 
> ```-> [SocketCore.cc:1021] errorCode=1 SSL/TLS handshake failure: unable to get local issuer certificate```
> 这是没有证书管理软件导致的， 安装 ca-certificates 即可
> 
> ```opkg install ca-certificates```

### 安装 opkg 
```
mkdir -p /volume1/@Entware/opt
rm -rf /opt
mkdir /opt
mount -o bind "/volume1/@Entware/opt" /opt
wget -O - https://bin.entware.net/x64-k3.2/installer/generic.sh | /bin/sh
```
在计划任务里以root用户建立开机脚本任务
```
#!/bin/sh

# Mount/Start Entware
mkdir -p /opt
mount -o bind "/volume1/@Entware/opt" /opt
/opt/etc/init.d/rc.unslung start

# Add Entware Profile in Global Profile
if grep  -qF  '/opt/etc/profile' /etc/profile; then
    echo "Confirmed: Entware Profile in Global Profile"
else
    echo "Adding: Entware Profile in Global Profile"
cat >> /etc/profile <<"EOF"

# Load Entware Profile
. /opt/etc/profile
EOF
fi

# Update Entware List
/opt/bin/opkg update
```


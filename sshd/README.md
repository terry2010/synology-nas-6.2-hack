群晖的sshd 不支持sftp传文件， 在centos 上找到了相同版本， 替换了sshd，使其能传文件

文件对应位置：

```
sshd : /bin/sshd
```

其他so文件：
```
/usr/lib64 
```

需要在 /etc/ssh/sshd_config 中增加
```
UsePrivilegeSeparation no
```

最后这项修改会降低ssh的安全性。 如果需要更安全的方式，网上有修改内容

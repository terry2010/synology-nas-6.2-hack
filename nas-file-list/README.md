# 找出文件名超长的文件， 或者生成某个目录的文件列表
------
因为在往群晖nas 里面传文件的时候发现， 要求文件名长度必须短于255， 所以写了这个小工具


## 使用方式:

          php scan.php path=/volumeUSB1/usbshare/你的目录
          
          php scan.php order=namelengthdesc path="/volumeUSB1/usbshare/你的目录"
          
           usb路径一般为 
           
                       /volumeUSB1/usbshare/你的目录
                       
                       /volumeUSB2/usbshare/目录名
                       
            volumeUSB后面的数字是第几个usb设备，这个需要自己尝试一下才知道

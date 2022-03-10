wget http://www.rarlab.com/rar/rarlinux-x64-5.3.0.tar.gz
tar -zxvf rarlinux-x64-5.3.0.tar.gz
cd rar
mkdir -p /usr/local/bin
mkdir -p /usr/local/lib
cp rar unrar /usr/local/bin
cp rarfiles.lst /etc
cp default.sfx /usr/local/lib
ln -s /usr/local/bin/rar /usr/bin/rar
rar

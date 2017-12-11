后端采用的是go+mysql编写，不支持虚拟主机运行。运行需要先导入db.sql文件，然后配置config.json，最后在启动应用程序。

首先本项目一共编译了5个应用程序。

linux32 代表32位linux
linux64 代表64位linux
windows32.exe 代表32位windows
windows64.exe 代表64位windows
macos 代表64位macos

linux下后台运行程序
```
nohup ./linux32 >/dev/null 2>&1 &
```


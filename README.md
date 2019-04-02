# phpwebddns-for-dnspod
PHP网页版的动态域名解析系统 for DNSPOD
一个非常方便的DNSPOD在线动态域名管理系统

1.Windows用户，将提交后生成的加密链接收藏，更新动态域名时点击一下收藏夹中的这个链接就行了。

2.Linux用户：使用curl（也可以使用 wget -c） "在本站生成的加密连接" 脚本内容加入开机执行脚本就行了！
例如：
方式一：curl "http://dnspod.xxkwz.cn/?user=test@xxkwz.cn&pwd=Yk8pX9FGsjpO7iR&domain=xxkwz.cn&sdomain=www"
方式二：wget -c "http://dnspod.xxkwz.cn/?user=test@xxkwz.cn&pwd=Yk8pX9FGsjpO7iR&domain=xxkwz.cn&sdomain=www"
方式三：桌面版用户将提交后生成的加密链接加入你浏览器的收藏夹用的时候点击一下！ 

3.Tomato、OpenWrt、DD-WRT用户将 在本站生成的加密连接 加入你的ddns选项自定义（Custom Url）即可！

4.OpenWrt中最方便的方法是在计划任务中添加每10分钟执行一次curl（需要提前安装curl组件）
例如添加:
*/10 * * * * curl "http://dnspod.xxkwz.cn/?user=test@xxkwz.cn&pwd=Yk8pX9FGsjpO7iR&domain=xxkwz.cn&sdomain=www"

本程序自动获取你的当前ip无需自己设置，解决了自己添加ip的困难！

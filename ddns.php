<?php
header("Content-type: text/html; charset=utf8");
//$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
//$url_this = $http_type . $_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']; 
function get_url_this(){ 
    $current_url='http://'; 
    if(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'){ 
        $current_url='https://'; 
    } 
    if($_SERVER['SERVER_PORT']!='80'){ 
        $current_url.=$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']; 
    }else{ 
        $current_url.=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
    } 
    return $current_url; 
}
/*********************************************************************
    函数名称:encrypt
    函数作用:加密解密字符串
    使用方法:
    加密     :encrypt('str','E','nowamagic');
    解密     :encrypt('被加密过的字符串','D','nowamagic');
    参数说明:
    $string   :需要加密解密的字符串
    $operation:判断是加密还是解密:E:加密   D:解密
    $key      :加密的钥匙(密匙); 修改:bbs.swdyz.com 演示:www.xxkwz.cn
    *********************************************************************/
	function encrypt($string,$operation,$key='')
    {
        $key=md5($key);
        $key_length=strlen($key);
        $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
        $string_length=strlen($string);
        $rndkey=$box=array();
        $result='';
        for($i=0;$i<=255;$i++)
        {
            $rndkey[$i]=ord($key[$i%$key_length]);
            $box[$i]=$i;
        }
        for($j=$i=0;$i<256;$i++)
        {
            $j=($j+$box[$i]+$rndkey[$i])%256;
            $tmp=$box[$i];
            $box[$i]=$box[$j];
            $box[$j]=$tmp;
        }
        for($a=$j=$i=0;$i<$string_length;$i++)
        {
            $a=($a+1)%256;
            $j=($j+$box[$a])%256;
            $tmp=$box[$a];
            $box[$a]=$box[$j];
            $box[$j]=$tmp;
            $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
        }
        if($operation=='D')
        {
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8))
            {
                return substr($result,8);
            }
            else
            {
                return'';
            }
        }
        else
        {
            return str_replace('=','',base64_encode($result));
        }
    }

if (!empty ($_POST['duser'])){
$dnspod_euser = str_replace('+','%2B',encrypt($_POST['duser'],'E','abcd1234')); //加密并替换+号为%2B
$dnspod_epwd = str_replace('+','%2B',encrypt($_POST['dpwd'],'E','abcd1234'));
$dnspod_edomain = str_replace('+','%2B',encrypt($_POST['ddomain'],'E','abcd1234'));
$dnspod_esdomain = str_replace('+','%2B',encrypt($_POST['dsdomain'],'E','abcd1234'));
echo "<html><header><meta http-equiv='content-type' content='text/html;charset=utf-8'><title>复制你的专用链接</title></header><body><table width='100%' height='auto' border='2' bordercolor='green'><tr bordercolor='green'>";
echo '<B><font color="red">加密连接（连续双击链接选中,Ctrl+c复制）：</font></b><td>'.$url_this.'?euser='.$dnspod_euser.'&epwd='.$dnspod_epwd.'&edomain='.$dnspod_edomain.'&esdomain='.$dnspod_esdomain;
echo "</td></tr></table><br /><br />如需使用明文链接请按格式修改（不安全不建议使用）：<br />$url_this?user=你的DNSPod用户名(如:xxx@xx.com)&pwd=你的DNSPod密码(如:123456)&domain=你的主域名(如:xxkwz.cn)&sdomain=你的主机头(如:test)<br /><br />例子（如需解析的域名：test.xxkwz.cn）：<br /><input type='textarea' style='width:100%; height:AUTO;' value='$url_this?user=xxx@xx.com&pwd=123456&domain=xxkwz.cn&sdomain=test' /></body></html>";
print <<<EOT
<h2>说明：<br /><br />1.Windows用户，将提交后生成的加密链接收藏，更新动态域名时点击一下收藏夹中的这个链接就行了。<br /><br />2.Linux用户：使用<font color="red">curl（也可以使用 wget -c） "在本站生成的加密连接"</font> 脚本内容加入开机执行脚本就行了！<br /></h2>
</h3>例如：<br />
方式一：<font color="green">curl "$url_this?euser=$dnspod_euser&epwd=$dnspod_epwd&edomain=$dnspod_edomain&esdomain=$dnspod_esdomain"</font><br />
方式二：<font color="green">wget -c "$url_this?euser=$dnspod_euser&epwd=$dnspod_epwd&edomain=$dnspod_edomain&esdomain=$dnspod_esdomain"</font><br />
方式三：桌面版用户将提交后生成的加密链接加入你浏览器的收藏夹用的时候点击一下！
<br /></h3><h2>
3.Tomato、openwrt、ddwrt用户将 <font color="red">在本站生成的加密连接</font> 加入你的ddns选项自定义（Custom Url）即可！<br><br />本程序自动获取你的当前ip无需自己设置，解决了自己添加ip的困难！
</h2>
EOT;
echo "<br /><br /><p align='center'>Copyright © 2010-2018 <a href='http://www.xxkwz.cn' target='_blank'>情绪21℃'s Blog</a>  保留所有权利. 浙ICP备11017358号-1</p>";
exit;
}

if((!empty ($_GET['user']))||(!empty ($_GET['euser']))){
class Dns
{

       function getMyIp()
       {
      $ip = $_SERVER['REMOTE_ADDR']; //是你的客户端跟你的服务器“握手”时候的IP。如果使用了“匿名代理”，REMOTE_ADDR将显示代理服务器的IP。
      if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
       } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];//可以知道代理服务器的服务器名以及端口
       } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];//是代理服务器发送的HTTP头。如果是“超级匿名代理”，则返回none值。同样，REMOTE_ADDR也会被替换为这个代理服务器的IP。
    }
	

      preg_match_all("/([0-9]?[0-9]?[0-9]).([0-9]?[0-9]?[0-9]).([0-9]?[0-9]?[0-9]).([0-9]?[0-9]?[0-9])/", $ip, $tl); 
      $ip=$tl[0][0];
    return $ip;
        }
		 

        function api_call($api, $data) 
        {
		//Dnspod账户
	    if (!empty ($_GET['euser'])){
		 $dnspod_user = encrypt($_GET['euser'],'D','abcd1234');
		 }else{$dnspod_user = $_GET['user'];}
         
        //Dnspod密码
		if (!empty ($_GET['epwd'])){
		 $dnspod_pwd = encrypt($_GET['epwd'],'D','abcd1234');
		 }else{$dnspod_pwd = $_GET['pwd'];}
		 
		 

                if ($api == '' || !is_array($data)) { 
                exit('内部错误：参数错误');
                }
 
                $api = 'https://dnsapi.cn/' . $api;

                $data = array_merge($data, array('login_email' => $dnspod_user, 'login_password' => $dnspod_pwd, 'format' => 'json', 'lang' => 'cn', 'error_on_empty' => 'no'));
 
                $result = $this->post_data($api, $data); 
                if (!$result) {
                exit('内部错误：调用失败');
                }
 
                $results = @json_decode($result, 1); 
                if (!is_array($results)) {
                exit('内部错误：返回错误');
                }
 

                if ($results['status']['code'] != 1) {
                exit($results['status']['message']);
                }
 
                return $results;
        }
 
        private function post_data($url, $data) 
        {
                if ($url == '' || !is_array($data)) {
                return false;
                }
 

                $ch = @curl_init(); //初始一个新cURL资源（任务）
                if (!$ch) {
                exit('内部错误：服务器不支持CURL');
                }
 

                curl_setopt($ch, CURLOPT_URL, $url);  
                curl_setopt($ch, CURLOPT_POST, 1);  
                curl_setopt($ch, CURLOPT_HEADER, 0); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //全部数据使用HTTP协议中的"POST"操作来发送,http_build_query是生成要发送内容为url格式
                curl_setopt($ch, CURLOPT_USERAGENT, 'LocalDomains_PHP/1.0.0([url=mailto:roy@leadnt.com]roy@leadnt.com[/url])'); //发送浏览器标识
                $result = curl_exec($ch); //执行curl任务并将返回结果发送给$result
                curl_close($ch); //关闭curl资源
 
                return $result; //返回结果
        }
         
         
        public function exec()
        {

		if (!empty ($_GET['edomain'])){
		 $domain = encrypt($_GET['edomain'],'D','abcd1234');
		 }else{$domain = $_GET['domain'];}
         

		if (!empty ($_GET['esdomain'])){
	$_GET['esdomain'];	 $sub_domain = encrypt($_GET['esdomain'],'D','abcd1234');
		 }else{$sub_domain = $_GET['sdomain'];}
         		
                $ip = $this->getMyIp();
                $domainInfo = $this->api_call('domain.info',array('domain' => $domain)); //获取主域名信息数组
                $domainId = $domainInfo['domain']['id']; //从主域名信息数组中获取主域名ID
				

                $record = $this->api_call('record.list',array('domain_id'=> $domainId,'offset' =>'0','length' => '1','sub_domain' =>$sub_domain));
				

                if($record['info']['record_total'] == 0)
                {
                        $this->api_call('record.create',
                                array(
                                        'domain_id' => $domainId,
                                        'sub_domain' => $sub_domain,
                                        'record_type' => 'A',
                                        'record_line' => '默认',
                                        'value' => $ip,
                                        'ttl' => '120'
                                        ));
					echo "<meta http-equiv='content-type' content='text/html;charset=utf-8'>";
                      echo '创建并解析成功！';
                }
                else
                {

                        if($record['records'][0]['value'] != $ip)
                        {
                                $this->api_call('record.modify',
                                array(
                                        'domain_id' => $domainId,
                                        'record_id' => $record['records'][0]['id'],
                                        'sub_domain' => $sub_domain,
                                        'record_type' => 'A',
                                        'record_line' => '默认',
                                        'value' => $ip,
										'ttl' => '120'
                                        ));
								
								echo "<meta http-equiv='content-type' content='text/html;charset=utf-8'>";
                                echo '更新ip成功！';
                        }
                        else
                        {

						echo "<meta http-equiv='content-type' content='text/html;charset=utf-8'>";
                                echo '提交ip与记录ip相同，记录没有更改！';
                        }
                }
        }
}
 
 
         
$dns = new Dns();
$dns->exec();
}else{
?>
<title>DNSPod动态域名在线解析与路由解析</title>
<meta http-equiv='content-type' content='text/html;charset=utf-8'>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<center><h1>DNSPod动态域名解析系统 - V1.1</h1></center>
<style>
input{
                border: 1px solid #ccc;
                padding: 7px 0px;
                border-radius: 3px;
                padding-left:5px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
                -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s
            }
            input:focus{
                    border-color: #66afe9;
                    outline: 0;
                    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
                    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)
            }
</style>
<h2>生成加密链接：<br></h2>
<form action="" method="post" style = "line-height:2.5">
帐号：<input type="text" name="duser"><font color="green">*(你在DNSPod的帐号)</font><br>
密码：<input type="password" name="dpwd"><font color="green">*(你在DNSPod的密码)</font><br>
主域：<input type="text" name="ddomain"><font color="green">*(你在DNSPod的主域名如：xxkwz.cn)</font><br>
主机：<input type="text" name="dsdomain"><font color="green">*(你在DNSPod设置的主机名如：www 要解析主域填写 @)</font><br>
<input type="submit" name="button" id="button" value="" style=" border:none; height:30px; width:100px; background:url(img21.gif)" /><br>
</form>
<h2>说明：<br /><br />1.Windows用户，将提交后生成的加密链接收藏，更新动态域名时点击一下收藏夹中的这个链接就行了。<br /><br />2.Linux用户：使用<font color="red">curl（也可以使用 wget -c） "在本站生成的加密连接"</font> 脚本内容加入开机执行脚本就行了！<br /></h2>
</h3>例如：<br />
方式一：<font color="green">curl "<?php 
echo $url_this; 
?>?user=test@xxkwz.cn&pwd=Yk8pX9FGsjpO7iR&domain=xxkwz.cn&sdomain=www"</font><br />
方式二：<font color="green">wget -c "<?php 
echo $url_this; 
?>?user=test@xxkwz.cn&pwd=Yk8pX9FGsjpO7iR&domain=xxkwz.cn&sdomain=www"</font><br />
方式三：桌面版用户将提交后生成的加密链接加入你浏览器的收藏夹用的时候点击一下！
<br /></h3><h2>
3.Tomato、openwrt、ddwrt用户将 <font color="red">在本站生成的加密连接</font> 加入你的ddns选项自定义（Custom Url）即可！<br><br />本程序自动获取你的当前ip无需自己设置，解决了自己添加ip的困难！
</h2>
  <p align="center">Copyright © 2010-2018 <a href="http://www.xxkwz.cn" target="_blank">情绪21℃'s Blog</a>  保留所有权利. 浙ICP备11017358号-1 <a href="dnspod.html">HTML版</a></p>
<?php } ?>
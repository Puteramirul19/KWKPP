<?php
echo "WOW";
echo date("Y-m-d H:i:s");
phpinfo();


	$datasource['db'] 		= "test";
	$datasource['host'] 	= "localhost";
	$datasource['user'] 	= "root";
	$datasource['password'] = "123456";
	
	
	$datasource['handler'] = mysql_connect($datasource['host'],$datasource['user'],$datasource['password']);
	mysql_select_db($datasource['db'],$datasource['handler']);
	
	
	if(mysql_error()!=''){
		die('<span style="color:red;font-family:arial;font-size:12px"><b>Sorry, system temporarily not available.</b></span>');
	}
	else{echo "Gooooooooood";}
	
	$datasource['repStatus'] = 'REPX';


?>
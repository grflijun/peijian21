<?php
    require_once('db_def.php');

/////////////////////////////////////////////////////////////////////////
//  网络用
/*
//2013-3-27修改，新的空间只能用指定数据库名称
	$link = mysql_connect("210.209.125.210:3306", "a0327161318", "grfdali13");
	if (!$link)
	{
		die($str_inner_err . mysql_error());
	}
	mysql_query("set names 'UTF8' ");
//	mysql_query("set character_set_client=UTF8");
//	mysql_query("set character_set_results=UTF8");
//	mysql_query("set character_set_connection=UTF8");
//	mysql_query("SET CHARACTER SET UTF8");

//2013-3-27修改，新的空间只能用指定数据库名称
	mysql_select_db("a0327161318");
*/

/////////////////////////////////////////////////////////////////////////
//  本地测试用

	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());
?>
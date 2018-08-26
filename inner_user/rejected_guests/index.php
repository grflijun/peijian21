<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>查看被拒绝申请</title>
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/demo/demo.css">
<link rel="stylesheet" href="../../css/inner_style.css?v=1.0.1">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
<script src="../../scripts/common_use.js"></script>

<script>
	function reuseInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$.messager.confirm('Confirm','真的要把这个注册请求放回待确认列表吗?', function(r){
				if(r)
				{
					$.post('reuse_guest.php', {id:row.guest_id}, function(result){
						if(result.success)
						{
							$('#dg').datagrid('reload');	// reload the user data
						}
						else
						{
							$.messager.show({	// show error message
								title: 'Error',
								msg: result.msg
							});
						}
					}, 'json');
				}
			});
		}
	}

	function removeInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$.messager.confirm('Confirm','真的要删除这条信息吗?', function(r){
				if(r)
				{
					$.post('../remove_info.php', {id:row.guest_id}, function(result){
						if(result.success)
						{
							$('#dg').datagrid('reload');	// reload the user data
						}
						else
						{
							$.messager.show({	// show error message
								title: 'Error',
								msg: result.msg
							});
						}
					}, 'json');
				}
			});
		}
	}
</script>
</head>
<body>
	<div style="margin-top: 10px; margin-bottom: 10px;">
		<ul class="menu_list">
			<li><a href="../user_config.php">主菜单</a></li>
			<li><a href="../logout.php">安全退出</a></li>
		</ul>
	</div>

	<div class="demo-info" style="margin-bottom:10px">
		<div class="demo-tip icon-tip">&nbsp;</div>
		<div>被拒绝申请一览表</div>
	</div>

	<table id="dg" title="客户信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"	toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="false" singleSelect="true" pageSize="20"
			pageList="[10, 20, 50, 100, 200]">
		<thead>
			<tr>
				<th field="login_name" width="100">登录名</th>
				<th field="reg_date" width="120">注册日期</th>
				<th field="area_name" width="150">所在地区</th>
				<th field="family_name" width="50">姓</th>
				<th field="given_name" width="50">名</th>
				<th field="company" width="150">公司</th>
				<th field="duty" width="100">职务</th>
				<th field="phone" width="100">电话</th>
				<th field="phone2" width="100">电话2</th>
				<th field="address" width="150">地址</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="reuseInfo()">放回待确认列表</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除客户信息</a>
	</div>
</body>
</html>

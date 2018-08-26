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
<title>确认新注册客户</title>
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/demo/demo.css">
<link rel="stylesheet" href="../../css/inner_style.css?v=1.0.1">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
<script src="../../scripts/common_use.js"></script>

<script>
	var url;

	function verifyInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','确认客户注册请求');
			$('#fm').form('load', row);
			url = 'verify_guest.php?id=' + row.guest_id;
		}
	}

	function saveInfo()
	{
		$('#fm').form('submit',
		{
			url: url,
			onSubmit: function()
			{
				return $(this).form('validate');
			},
			success: function(result)
			{
				var result = eval('('+result+')');
				if(result.success)
				{
					$('#dlg').dialog('close');		// close the dialog
					$('#dg').datagrid('reload');	// reload the user data
				}
				else
				{
					$.messager.show({
						title: 'Error',
						msg: result.msg
					});
				}
			}
		});
	}

	function rejectInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$.messager.confirm('Confirm','真的要拒绝这个客户的注册请求吗?', function(r){
				if(r)
				{
					$.post('reject_guest.php', {id:row.guest_id}, function(result){
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
			<li><a href="../rejected_guests/index.php">查看被拒用户注册申请</a></li>
			<li><a href="../logout.php">安全退出</a></li>
		</ul>
	</div>

	<div class="demo-info" style="margin-bottom:10px">
		<div class="demo-tip icon-tip">&nbsp;</div>
		<div>待确认客户一览表</div>
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
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="verifyInfo()">确认该用户</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="rejectInfo()">拒绝该用户</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除客户信息</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:700px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">确认客户注册请求</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>登录名:</label>
				<input name="login_name" class="easyui-textbox" readonly="readonly">
			</div>
			<div class="fitem">
				<label>对应业务员:</label>
				<input id="serve_staff_id" name="serve_staff_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>所属用户组:</label>
				<input id="group_id" name="group_id" class="easyui-combobox" editable="false" required="true">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveInfo()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>
<script>
$(document).ready(function(){
	$("#group_id").combobox({
		url:'../get_data/get_guest_groups.php',
		valueField:'group_id',
		textField:'group_name'
	});

//这里的combobox内容是所有的业务员
//确认新客户的功能不能开放给太多人
	$("#serve_staff_id").combobox({
		url:'../get_data/get_staffs.php',
		valueField:'staff_id',
		textField:'realname'
	});
});
</script>
</body>
</html>

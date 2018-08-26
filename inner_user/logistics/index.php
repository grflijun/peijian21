<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


file_put_contents('e:\\temp\\log.txt','hello, just a test.');

?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>物流信息一览表</title>
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/demo/demo.css">
<link rel="stylesheet" href="../../css/inner_style.css?v=1.0.1">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
<script src="../../scripts/common_use.js"></script>

<script>
	var url;

	function newInfo()
	{
		$('#dlg').dialog('open').dialog('setTitle','新增物流信息');
		$('#fm').form('clear');
		url = 'insert_info.php';
	}

	function editInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','修改物流信息');
			$('#fm').form('load', row);
			url = 'update_info.php?id=' + row.logistics_id;
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

	function removeInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			if(row.child_cnt > 0)
			{
				alert('该物流有对应的客户，不能删除');
				return false;
			}

			$.messager.confirm('Confirm','真的要删除这条信息吗?', function(r){
				if(r)
				{
					$.post('remove_info.php', {id:row.logistics_id}, function(result){
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
		<div>物流信息一览表</div>
	</div>

	<table id="dg" title="物流信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"
			toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="true" singleSelect="true" pageSize="20">
		<thead>
			<tr>
				<th field="logistics_name" width="50">物流名称</th>
				<th field="phone" width="50">电话</th>
				<th field="address" width="50">地址</th>
				<th field="note" width="50">备注说明</th>
				<th field="child_cnt" width="50">客户数</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newInfo()">新增物流信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInfo()">修改物流信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除物流信息</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">物流信息</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>物流名称:</label>
				<input name="logistics_name" class="easyui-validatebox" required="true" validType="lenRange[4,30]" invalidMessage="名称长度应在4到30个字符之间。">
			</div>
			<div class="fitem">
				<label>电话:</label>
				<input name="phone" class="easyui-textbox" validType="maxLength[40]" invalidMessage="电话的长度不能超过40。">
			</div>
			<div class="fitem">
				<label>地址:</label>
				<input name="address" class="easyui-textbox" data-options="multiline:true" validType="maxLength[200]" invalidMessage="地址的长度不能超过200。">
			</div>
			<div class="fitem">
				<label>备注说明:</label>
				<input name="note" class="easyui-textbox" data-options="multiline:true" validType="maxLength[100]" invalidMessage="备注说明的长度不能超过100。">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveInfo()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>

</body>
</html>
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
<title>客户分组一览表</title>
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
		$('#dlg').dialog('open').dialog('setTitle','新增客户分组');
		$('#fm').form('clear');
		url = 'insert_info.php';
	}

	function editInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','修改客户分组');
			$('#fm').form('load', row);
			url = 'update_info.php?id=' + row.group_id;
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
				alert('该分组下有客户，不能删除');
				return false;
			}

			$.messager.confirm('Confirm','真的要删除这条信息吗?', function(r){
				if(r)
				{
					$.post('remove_info.php', {id:row.group_id}, function(result){
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
		<div>客户分组一览表</div>
	</div>

	<table id="dg" title="客户分组信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"
			toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="true" singleSelect="true" pageSize="20">
		<thead>
			<tr>
				<th field="group_name" width="50">客户分组名</th>
				<th field="group_desc" width="50">描述</th>
				<th field="child_cnt" width="50">客户数</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newInfo()">新增客户分组</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInfo()">修改客户分组</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除客户分组</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:380px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">客户分组信息</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>客户分组名称:</label>
				<input name="group_name" class="easyui-validatebox" required="true" validType="lenRange[4,30]" invalidMessage="名称长度应在4到30个字符之间。">
			</div>
			<div class="fitem">
				<label>描述:</label>
				<input name="group_desc" class="easyui-textbox" validType="maxLength[100]" invalidMessage="描述的长度不能超过100。">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveInfo()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>

</body>
</html>
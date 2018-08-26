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
<title>客户描述一览表</title>
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/demo/demo.css">
<link rel="stylesheet" href="../../css/inner_style.css?v=1.0.1">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
<script src="../../scripts/common_use.js"></script>

<script>
	var url;

	function editInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','修改客户描述');
			$('#fm').form('load', row);
			url = 'update_info.php?id=' + row.guest_id;
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
		<div>客户描述一览表</div>
	</div>

	<table id="dg" title="客户描述" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"	toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="false" singleSelect="true" pageSize="20"
			pageList="[10, 20, 50, 100, 200]">
		<thead>
			<tr>
				<th field="login_name" width="100">登录名</th>
				<th field="inner_desc" width="120">客户描述</th>
				<th field="companytype" width="120">所属行业</th>
				<th field="lack_money" width="100">未结金额</th>
				<th field="lan_code" width="150">常用语言</th>
				<th field="logistics_name" width="100">常用物流</th>
				<th field="logistics_note" width="50">物流备注</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInfo()">修改客户描述</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:700px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">客户描述</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>登录名:</label>
				<input name="login_name" class="easyui-textbox" readonly="readonly">
			</div>
			<div class="fitem">
				<label>描述内容:</label>
				<input name="inner_desc" class="easyui-textbox" data-options="multiline:true" validType="maxLength[200]" invalidMessage="描述内容的长度不能超过200。">
			</div>
			<div class="fitem">
				<label>所属行业:</label>
				<input name="companytype" class="easyui-textbox" validType="maxLength[30]" invalidMessage="所属行业的长度不能超过30。">
			</div>
			<div class="fitem">
				<label>常用语言:</label>
				<input name="lan_code" id="rdo_cn" type="radio" value="0">中文
				<input name="lan_code" id="rdo_en" type="radio" value="1">英语
			</div>
			<div class="fitem">
				<label>常用物流:</label>
				<input id="logistics_id" name="logistics_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>物流备注:</label>
				<input name="logistics_note" class="easyui-textbox" data-options="multiline:true" validType="maxLength[60]" invalidMessage="备注的长度不能超过60。">
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveInfo()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>
<script>
$(document).ready(function(){
	$("#logistics_id").combobox({
		url:'../get_data/get_logistics.php',
		valueField:'logistics_id',
		textField:'logistics_name'
	});
});
</script>
</body>
</html>

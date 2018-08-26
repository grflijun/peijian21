<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: ../admin_start.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>员工信息一览表</title>
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/demo/demo.css">
<link rel="stylesheet" href="../../css/inner_style.css?v=1.0.1">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
<script src="../../scripts/common_use.js"></script>

<script>
	var url;

	function newUser()
	{
		$("#superior_id").combobox({
			url:'get_staff_names.php',
			valueField:'staff_id',
			textField:'realname'
		});

		$('#div_password').css('display', 'block');
		$('#login_name').validatebox('reuse');
		$('#new_pass1').validatebox('reuse');
		$('#new_pass2').validatebox('reuse');
		$('#login_name').attr('readonly', false);

		$('#dlg').dialog('open').dialog('setTitle','新增员工信息');
		$('#fm').form('clear');
		url = 'insert_info.php';
	}

	function editUser()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$("#superior_id").combobox({
				url:'get_staff_names.php?ignore_id=' + row.staff_id,
				valueField:'staff_id',
				textField:'realname'
			});

			$('#div_password').css('display', 'none');
			$('#login_name').validatebox('remove');
			$('#new_pass1').validatebox('remove');
			$('#new_pass2').validatebox('remove');
			$('#login_name').attr('readonly', true);

			$('#dlg').dialog('open').dialog('setTitle','修改员工信息');
			$('#fm').form('load', row);
			url = 'update_info.php?id=' + row.staff_id;
		}
	}

	function saveUser()
	{
//		$("#combo_sel_id").val($("#superior_id").combobox("getValue"));
		$("#combo_sel_name").val($("#superior_id").combobox("getText"));

		$('#fm').form('submit',
		{
			url: url,
			onSubmit: function()
			{
				var val=$('input:radio[name="out_use"]:checked').val();
				if(val == null)
				{
					$.messager.show({title: '错误',	msg: "请选择有效性!"});
					return false;
				}

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
						title: '错误',
						msg: result.msg
					});
				}
			}
		});
	}

	function removeUser()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$.messager.confirm('Confirm','删除信息可能会导致数据不一致，建议修改为 停用。真的要删除这条信息吗?', function(r){
				if(r)
				{
					$.post('remove_info.php', {id:row.staff_id}, function(result){
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

	function editPrevilige()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			//window.location.href = 'staff_previlige.php?USERID=' + row.staff_id;
			window.open('staff_previlige.php?USERID=' + row.staff_id);
		}
	}
</script>
</head>
<body>
	<div style="margin-top: 10px; margin-bottom: 10px;">
		<ul class="menu_list">
			<li><a href="../admin_config.php">主菜单</a></li>
			<li><a href="../admin_end.php">安全退出</a></li>
		</ul>
	</div>

	<div class="demo-info" style="margin-bottom:10px">
		<div class="demo-tip icon-tip">&nbsp;</div>
		<div>员工信息一览表.</div>
	</div>

	<table id="dg" title="员工信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"
			toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="true" singleSelect="true" pageSize="20">
		<thead>
			<tr>
				<th field="login_name" width="50">登录名</th>
				<th field="realname" width="50">真实姓名</th>
				<th field="duty" width="50">职务</th>
				<th field="superior_name" width="50">直接上级</th>
				<th field="out_use" width="50">有效性(0-有效；1-暂停使用)</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">新增员工</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">修改员工信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()">删除员工信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPrevilige()">修改员工权限</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:480px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">员工信息</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>登录名:</label>
				<input name="login_name" id="login_name" class="easyui-validatebox" required="true" validType="lenRange[6,20]" invalidMessage="登录名长度应在6到20个字符之间。">
			</div>

			<div id="div_password">
				<div class="fitem">
					<label>密码:</label>
					<input name="new_pass1" id="new_pass1" class="easyui-validatebox" type="password" required="true" validType="lenRange[6,16]" invalidMessage="密码长度应在6到16个字符之间。">
				</div>
				<div class="fitem">
					<label></label>
					<label id="txt_pass_check" style="display:inline">密码长度应在6到16个字符之间。</label>
				</div>
				<div class="fitem">
					<label>确认密码:</label>
					<input id="new_pass2" class="easyui-validatebox" type="password" required="true" validType="equalTo['#new_pass1']" invalidMessage="两次输入的密码不一致。">
				</div>
			</div>

			<div class="fitem">
				<label>真实姓名:</label>
				<input name="realname" class="easyui-validatebox" required="true">
			</div>
			<div class="fitem">
				<label>职务:</label>
				<input name="duty" class="easyui-textbox">
			</div>
			<div class="fitem">
				<label>直接上级:</label>
				<input hidden="hidden" name="combo_sel_name" id="combo_sel_name">
				<input id="superior_id" name="superior_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>有效性:</label>
				<input name="out_use" id="rdo_inuse" type="radio" value="0">可用
				<input name="out_use" id="rdo_halted" type="radio" value="1">停用这个账号
			</div>

		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>
<script>
$(document).ready(function(){
	//检测密码强度
	$('#new_pass1').keyup(function(e) {
		test_pass_strength(this, $('#txt_pass_check'));

		return true;
	});
});

</script>

</body>
</html>
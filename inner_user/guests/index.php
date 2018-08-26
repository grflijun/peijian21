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
<title>客户信息一览表</title>
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
		$('#div_password').css('display', 'block');
		$('#login_name').validatebox('reuse');
		$('#new_pass1').validatebox('reuse');
		$('#new_pass2').validatebox('reuse');
		$('#login_name').attr('readonly', false);

		$('#dlg').dialog('open').dialog('setTitle','新增客户信息');
		$('#fm').form('clear');
		url = 'insert_info.php';
	}

	function editInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#div_password').css('display', 'none');
			$('#login_name').validatebox('remove');
			$('#new_pass1').validatebox('remove');
			$('#new_pass2').validatebox('remove');
			$('#login_name').attr('readonly', true);

			$('#dlg').dialog('open').dialog('setTitle','修改客户信息');
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

	function removeInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$.messager.confirm('Confirm','真的要删除这条信息吗?', function(r){
				if(r)
				{
					$.post('remove_info.php', {id:row.guest_id}, function(result){
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
			<li><a href="../guest_desc/index.php">查看客户描述</a></li>
			<li><a href="../logout.php">安全退出</a></li>
		</ul>
	</div>

	<div class="demo-info" style="margin-bottom:10px">
		<div class="demo-tip icon-tip">&nbsp;</div>
		<div>客户信息一览表</div>
	</div>

	<table id="dg" title="客户信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"	toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="false" singleSelect="true" pageSize="20"
			pageList="[10, 20, 50, 100, 200]">
		<thead>
			<tr>
				<th field="login_name" width="100">登录名</th>
				<th field="reg_date" width="120">注册日期</th>
				<th field="chk_date" width="120">确认日期</th>
				<th field="staff_name" width="100">业务员</th>
				<th field="area_name" width="150">所在地区</th>
				<th field="group_name" width="100">所属组</th>
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
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newInfo()">新增客户信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInfo()">修改客户信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除客户信息</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:700px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">客户信息</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>登录名:</label>
				<input name="login_name" class="easyui-validatebox" required="true" validType="lenRange[6,30]" invalidMessage="登录名长度应在6到30个字符之间。">
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
				<label>对应业务员:</label>
				<input id="serve_staff_id" name="serve_staff_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>所属地区:</label>
				<input id="sheng_id" name="sheng_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label></label>
				<input id="shi_id" name="shi_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label></label>
				<input id="xian_id" name="xian_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>所属用户组:</label>
				<input id="group_id" name="group_id" class="easyui-combobox" editable="false" required="true">
			</div>
			<div class="fitem">
				<label>姓:</label>
				<input name="family_name" class="easyui-textbox" validType="maxLength[20]" invalidMessage="姓的长度不能超过20。">
			</div>
			<div class="fitem">
				<label>名:</label>
				<input name="given_name" class="easyui-textbox" validType="maxLength[20]" invalidMessage="名的长度不能超过20。">
			</div>
			<div class="fitem">
				<label>公司名:</label>
				<input name="company" class="easyui-textbox" validType="maxLength[50]" invalidMessage="公司名的长度不能超过50。">
			</div>
			<div class="fitem">
				<label>职务:</label>
				<input name="duty" class="easyui-textbox" validType="maxLength[30]" invalidMessage="职务的长度不能超过30。">
			</div>
			<div class="fitem">
				<label>电话:</label>
				<input name="phone" class="easyui-textbox" validType="maxLength[30]" invalidMessage="电话的长度不能超过30。">
			</div>
			<div class="fitem">
				<label>电话2:</label>
				<input name="phone2" class="easyui-textbox" validType="maxLength[30]" invalidMessage="电话的长度不能超过30。">
			</div>
			<div class="fitem">
				<label>地址:</label>
				<input name="address" class="easyui-textbox" data-options="multiline:true" validType="maxLength[200]" invalidMessage="地址的长度不能超过200。">
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

	$("#serve_staff_id").combobox({
		url:'../get_data/get_my_sub_staffs.php',
		valueField:'staff_id',
		textField:'realname'
	});

	$("#sheng_id").combobox({
		onChange: function(newvalue, oldvalue)
		{
			LoadShi(newvalue);
		},
		url:'../../include/get_data/areas/get_sheng.php',
		valueField:'sheng_id',
		textField:'sheng_name'
	});


	$("#shi_id").combobox({
		onChange: function(newvalue, oldvalue)
		{
			LoadXian(newvalue);
		}
	});


	function LoadShi(parent_id)
	{
		$("#shi_id").combobox({
			url:'../../include/get_data/areas/get_shi.php?sheng_id=' + parent_id,
			valueField:'shi_id',
			textField:'shi_name'
		});

		LoadXian(0);
	}

	function LoadXian(parent_id)
	{
		$("#xian_id").combobox({
			url:'../../include/get_data/areas/get_xian.php?shi_id=' + parent_id,
			valueField:'xian_id',
			textField:'xian_name'
		});
	}
});
</script>
</body>
</html>

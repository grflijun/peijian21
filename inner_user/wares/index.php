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
<title>商品一览表</title>
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
		$('#dlg').dialog('open').dialog('setTitle','新增商品信息');
		$('#fm').form('clear');
		url = 'insert_info.php';
	}

	function editInfo()
	{
		var row = $('#dg').datagrid('getSelected');
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','修改商品信息');
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
			<li><a href="#">查看商品描述</a></li>
			<li><a href="../logout.php">安全退出</a></li>
		</ul>
	</div>

	<div class="demo-info" style="margin-bottom:10px">
		<div class="demo-tip icon-tip">&nbsp;</div>
		<div>商品一览表</div>
	</div>

	<table id="dg" title="商品信息" class="easyui-datagrid" style="width:1200px;height:680px"
			url="get_info_list.php"	toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="false" singleSelect="true" pageSize="20"
			pageList="[10, 20, 50, 100, 200]">
		<thead>
			<tr>
				<th field="name_cn" width="100">中文名</th>
				<th field="name_en" width="100">英文名</th>
				<th field="type_ids" width="100">所属小类</th>
				<th field="inner_code" width="100">编号</th>
				<th field="gov_code" width="100">商品编码</th>
				<th field="disp_idx" width="100">显示顺序</th>
				<th field="hideflag" width="100">显示方式</th>
				<th field="thumbnail_path" width="100">缩略图</th>
				<th field="pack_pcs" width="100">每件数量</th>
				<th field="packtype" width="100">外包装类型</th>
				<th field="packshape" width="100">外包装形状</th>
				<th field="stacklimit" width="100">堆叠极限</th>
				<th field="lifeday" width="100">保质期</th>
				<th field="meas_x" width="100">尺寸x</th>
				<th field="meas_y" width="100">尺寸y</th>
				<th field="meas_z" width="100">尺寸z</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newInfo()">新增商品信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInfo()">修改商品信息</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeInfo()">删除商品信息</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:400px;height:700px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">商品信息</div>
		<form id="fm" class="inner_form" method="post" novalidate>
			<div class="fitem">
				<label>中文名:</label>
				<input name="name_cn" class="easyui-validatebox" required="true" validType="lenRange[3,30]" invalidMessage="中文名长度应在6到30个字符之间。">
			</div>
			<div class="fitem">
				<label>英文名:</label>
				<input name="name_en" class="easyui-textbox" validType="maxLength[60]" invalidMessage="英文名的长度不能超过60。">
			</div>
			<div id="parent_ids"></div>
			<div class="fitem">
				<label>内部编号:</label>
				<input name="inner_code" class="easyui-textbox" validType="maxLength[20]" invalidMessage="内部编号的长度不能超过20。">
			</div>
			<div class="fitem">
				<label>发票编码:</label>
				<input name="gov_code" class="easyui-textbox" validType="maxLength[20]" invalidMessage="发票编码的长度不能超过20。">
			</div>
			<div class="fitem">
				<label>显示顺序:</label>
				<input name="disp_idx" class="easyui-numberbox" min="0" max="500" precision="0" required="true" missingMessage="必须填写0~500之间的数字">
			</div>
			<div class="fitem">
				<label>访问方式:</label>
				<input name="hideflag" id="rdo_list" type="radio" value="0">正常显示
				<input name="hideflag" id="rdo_search" type="radio" value="1">不显示，可搜索
				<input name="hideflag" id="rdo_byid" type="radio" value="2">通过ID访问
				<input name="hideflag" id="rdo_hide" type="radio" value="100">下架
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
	$('#dl').datalist({
		url: 'datalist_data1.json',
		checkbox: true,
		lines: true
	});

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

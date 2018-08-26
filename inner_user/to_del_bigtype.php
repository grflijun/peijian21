<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: login.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>商品大分类</title>
<link rel="stylesheet" href="../css/inner_style.css">

<script>

//“删除选择”按钮按下后的处理
function CheckSelected()
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能删除");
		return false;
	}


	//取得选中项目的编号
	list_form.items.value = "";		 	//设置隐藏域的值为空

	if(!list_form.chk_id.length)		//只有一个复选框，list_form.item.length = undefined
	{
		if(list_form.chk_id.checked)
		{
			list_form.items.value = list_form.chk_id.value;
		}
	}
	else
	{
		var		temp = "";

		for(i = 0; i < list_form.chk_id.length; i++)
		{
			if(list_form.chk_id(i).checked)			//复选框中有选中的框
			{
				temp = temp + list_form.chk_id(i).value + ",";	//使用","分隔符将数组元素组合成一个字符串
			}
		}

		temp = temp.substring(0, temp.length - 1);	 //去除字符串末尾多余的","

		list_form.items.value = temp;	//将变量赋值给隐藏域
	}

	if(list_form.items.value == "")
		return false;

	return true;
}

//“删除全部”按钮按下后的处理
function OnDeleteAll()
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能删除");
		return false;
	}


	list_form.items.value = "0";

	return true;
}

//页面下方的详细信息窗体提交时的处理
function CheckAddModify()
{
	var		i;
//	var		lan_sel = 1000;		//选中的语言索引，1000代表一个很大的数，不可能有这么多种语言

	if(ware_detail_form.type_name.value == "")
	{
		alert("请输入大类型名");
		ware_detail_form.type_name.focus();
		return false;
	}

	if(ware_detail_form.disp_idx.value > 255)
	{
		alert("不能超过255");
		ware_detail_form.disp_idx.focus();
		return false;
	}

	if(ware_detail_form.cmb_lans.value == "")
	{
		alert("请选择语言");
		ware_detail_form.cmb_lans.focus();
		return false;
	}

	return true;
}

//“增加类型”按钮按下后的处理
function OnAddType()
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}


	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "append";	//向数据库中添加数据

	return true;
}

//“修改”按钮按下后的处理
/*
function OnModifyType(p_item_id, p_type_name, p_disp_idx, p_lan_id)
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}


	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "modify";	//修改数据库中的数据

	ware_detail_form.item_id.value = p_item_id;		//项目编号
	ware_detail_form.type_name.value = p_type_name;	//项目名称
	ware_detail_form.disp_idx.value = p_disp_idx;	//显示顺序

	//语言
	for(var i = 0; i < ware_detail_form.cmb_lans.options.length; i++)
	{
		if(ware_detail_form.cmb_lans[i].value == p_lan_id)
		{
			ware_detail_form.cmb_lans[i].selected = true;
			break;
		}
	}

	return true;
}
*/
function BeforeModifyType(p_item_id, p_disp_idx, p_lan_id)
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}


	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "modify";	//修改数据库中的数据

	ware_detail_form.item_id.value = p_item_id;		//项目编号
	ware_detail_form.disp_idx.value = p_disp_idx;	//显示顺序

	//语言
	for(var i = 0; i < ware_detail_form.cmb_lans.options.length; i++)
	{
		if(ware_detail_form.cmb_lans[i].value == p_lan_id)
		{
			ware_detail_form.cmb_lans[i].selected = true;
			break;
		}
	}

	return true;
}

//“取消”按钮按下后的处理
function OnDetailCancel()
{
	ware_detail_form.style.visibility="hidden";	//隐藏“详细信息”窗体
	ware_detail_form.proc_desc.value = "none";
}
</script>

</head>

<body>
<div class="bottom">
<div>
<br />
<a href="user_config.php">主菜单</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="logout.php">安全退出</a>
</div>

<form action="_delete_wares.php" method="post" name="list_form">
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	include("../include/linkopen.php");

	$sql="select b.no,b.name,a.name,b.disp_idx,a.id from lans a,WareBigType b where a.id=b.lan_id order by b.disp_idx";
	$rs = mysql_query($sql, $link);

	if(mysql_num_rows($rs) == 0)
	{
		echo("<h3>没有记录</h3>\n");
		echo("<input type=\"button\" name=\"btn_add_type\" value=\"增加大类型\" onclick=\"return OnAddType();\">\n");
	}
	else
	{
		echo("<table align=\"center\">\n");
		echo("  <tr height=\"20\"></tr>\n");
		echo("  <tr height=\"30\">\n");
		echo("    <td width=\"20\"><td width=\"40\">编号</td><td width=\"300\">名称</td><td width=\"100\">语言</td><td width=\"80\">显示顺序</td><td width=\"60\"></td>\n");
		echo("  </tr>\n");

		while($ra = mysql_fetch_row($rs))
		{
			echo("  <tr>\n");
			echo("    <td><input type=\"checkbox\" name=\"chkbox\" id=\"chk_id\" value=\"$ra[0]\" /></td>\n");

			for($i = 0; $i < 4; $i++)
			{
				if(1 == $i)
				{
					$str_name = str_replace("&", "&amp;", $ra[$i]);

					echo("    <td>".$str_name."</td>\n");
				}
				else
				{
					echo("    <td>".$ra[$i]."</td>\n");
				}
			}

			$str_name = str_replace("\\", "\\\\", $ra[1]);
			$str_name = str_replace("\"", "\\\"", $str_name);

echo("\n");
echo("<script language=\"javascript\">\n");
echo("function ModifyType$ra[0]()\n");
echo("{\n");
echo("    if(BeforeModifyType($ra[0],$ra[3],$ra[4]) == false)\n");
echo("        return false;\n");
echo("\n");
echo("    ware_detail_form.type_name.value = \"".$str_name."\";\n");	//项目名称
echo("}\n");
echo("</script>\n");
echo("\n");

			echo("    <td><input type=\"button\" name=\"btn_modify_type\" value=\"修改\" onclick=\"return ModifyType$ra[0]();\"></td>\n");

			echo("  </tr>\n");
		}

		echo("</table>\n");
		echo("<br><br>\n");
		echo("<input type=\"submit\" name=\"btn_del_sel\" value=\"删除选择\" onclick=\"return CheckSelected();\">\n");
		echo("<input type=\"submit\" name=\"btn_del_all\" value=\"删除全部\" onclick=\"return OnDeleteAll();\">\n");
		echo("<input type=\"button\" name=\"btn_add_type\" value=\"增加类型\" onclick=\"return OnAddType();\">\n");
	}

?>

<input type="hidden" name="items">
<input type="hidden" name="ware_type" value="big">
</form>




<form style="visibility:hidden" action="_add_modify_wares.php" method="post" name="ware_detail_form" onsubmit="return CheckAddModify();">
<table class="lst_tbl">
  <tr height="30"></tr>

  <tr height="30">
    <td colspan="2">详细信息</td>
  </tr>
  <tr height="30">
    <td width="70">类型名：</td><td width="400"><input type="text" size="58" maxlength="40" name="type_name" /></td>
  </tr>
  <tr height="30">
    <td width="90">显示顺序：</td><td width="400"><input type="text" size="39" maxlength="3" name="disp_idx" style="IME-MODE: disabled" onkeydown="if(event.keyCode==13)event.keyCode=9" onKeypress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false" onpaste="return false" />&nbsp;(数字0&nbsp;-&nbsp;255)</td>
  </tr>
  <tr height="30">
    <td width="70">语&nbsp;&nbsp;言：</td><td width="400">
      <select name="cmb_lans">
<?php
	$sql2 = "select id,name from lans order by id";
	$rs2 = mysql_query($sql2, $link);

	while($ra2 = mysql_fetch_row($rs2))
	{
		echo("        <option value=\"$ra2[0]\">$ra2[1]</option>\n");
	}


	include("../include/linkclose.php");
?>
      </select>
    </td>
  </tr>

  <tr height="30">
    <td colspan="2"><input type="submit" name="btn_ok" value="&nbsp;&nbsp;确定&nbsp;&nbsp;"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="btn_cancel" onclick="OnDetailCancel();" value="&nbsp;&nbsp;取消&nbsp;&nbsp;"/></td>
  </tr>

</table>

<input type="hidden" name="proc_desc" value="none">

<input type="hidden" name="item_id" value="0">
<input type="hidden" name="ware_type" value="big">
</form>

</div>
</body>
</html>

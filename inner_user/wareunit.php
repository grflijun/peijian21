<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!session_is_registered("user_name") || $_SESSION['user_name'] == "")
	{
		Header("Location: login.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>商品信息</title>
<link rel="stylesheet" href="../css/inner_style.css">

<style>
	table {
		margin:0;
		border-collapse:collapse;
		border:#000000 solid;
		border-width:1 0 0 1;
		border-width: thin;
	}
	td {
		padding:0;
		border:#000000 solid;
		border-width:0 1 1 0;
		border-width: thin;
	}
</style>

<script language="javascript">

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

	if(ware_detail_form.ware_name.value == "")
	{
		alert("请输入商品名称");
		ware_detail_form.ware_name.focus();
		return false;
	}

	if(ware_detail_form.cmb_tiny_type.value == "")
	{
		alert("请选择所属小类型");
		ware_detail_form.cmb_tiny_type.focus();
		return false;
	}

	if(ware_detail_form.cmb_img_path.value == "")
	{
		alert("请选择图片");
		ware_detail_form.cmb_img_path.focus();
		return false;
	}

	if(ware_detail_form.comment.value.length > 255)
	{
		alert("说明文字最大可以输入255个字符");
		ware_detail_form.comment.focus();
		return false;
	}

	//取得选中的小项目编号
//	ware_detail_form.tiny_id.value = ware_detail_form.cmb_tiny_type.value;

	return true;
}

//“增加商品”按钮按下后的处理
function OnAddWareUnit()
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}

//	var	cmb_img = document.getElementById("cmb_img_path");
//	cmb_img.selectedIndex = -1;
	OnPictureSelChanged();

	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "append";	//向数据库中添加数据

	return true;
}

//“修改”按钮按下后的处理
/*
function OnModifyWareUnit(item_no, item_name, img_path, order_no, comment, tiny_no)
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}

	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "modify";	//修改数据库中的数据

	ware_detail_form.item_id.value = item_no;		//项目编号
	ware_detail_form.ware_name.value = item_name;	//项目名称
	ware_detail_form.order_no.value = order_no;		//商品编号
	ware_detail_form.comment.value = comment;		//说明

	//小项目
	for(var i = 0; i < ware_detail_form.cmb_tiny_type.options.length; i++)
	{
		if(ware_detail_form.cmb_tiny_type[i].value == tiny_no)
		{
			ware_detail_form.cmb_tiny_type[i].selected = true;
			break;
		}
	}

	//图片
//todo:  img_path前面有../，而combobox的没有，所以不能匹配
	for(var i = 0; i < ware_detail_form.cmb_img_path.options.length; i++)
	{
		if(ware_detail_form.cmb_img_path[i].value == img_path)
		{
			ware_detail_form.cmb_img_path[i].selected = true;
			OnPictureSelChanged();
			break;
		}
	}

	return true;
}
*/
function BeforeModifyWareUnit(item_no, img_path, tiny_no)
{
	if(ware_detail_form.proc_desc.value != "none")
	{
		alert("增加或修改过程中不能进行这项操作");
		return false;
	}

	ware_detail_form.style.visibility="visible";	//显示“详细信息”窗体
	ware_detail_form.proc_desc.value = "modify";	//修改数据库中的数据

	ware_detail_form.item_id.value = item_no;		//项目编号

	//小项目
	for(var i = 0; i < ware_detail_form.cmb_tiny_type.options.length; i++)
	{
		if(ware_detail_form.cmb_tiny_type[i].value == tiny_no)
		{
			ware_detail_form.cmb_tiny_type[i].selected = true;
			break;
		}
	}

	//图片
//todo:  img_path前面有../，而combobox的没有，所以不能匹配
	for(var i = 0; i < ware_detail_form.cmb_img_path.options.length; i++)
	{
		if(ware_detail_form.cmb_img_path[i].value == img_path)
		{
			ware_detail_form.cmb_img_path[i].selected = true;
			OnPictureSelChanged();
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

function OnPictureSelChanged()
{
	var	img_sel = document.getElementById("img_selected");
	var path_prefix = "../";
	var temp_str;

	if(img_sel != null && ware_detail_form.cmb_img_path.value != "")
	{
		temp_str = path_prefix.concat(ware_detail_form.cmb_img_path.value);
		img_sel.src = temp_str;
	}
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

//说明：暂未加入显示顺序的设置
//如果需要加入，可参考bigtype.php，并修改_add_modify_wares.php

	$sql="(select a.no,a.name,a.image,a.orderNo,a.comment,c.name,b.name,a.tinyNo from Wares a,WareTinyType b,WareBigType c where a.tinyNo=b.no and b.bigNo=c.no order by c.disp_idx,b.disp_idx,a.disp_idx) union (select no,name,image,orderNo,comment,'','',tinyNo from Wares where tinyNo not in (select a.no from WareTinyType a,WareBigType b where a.bigNo=b.no) order by tinyNo,disp_idx)";
	$rs = mysql_query($sql, $link);

	if(mysql_num_rows($rs) == 0)
	{
		echo("<h3>没有记录</h3>\n");
		echo("<input type=\"button\" name=\"btn_add_type\" value=\"增加商品信息\" onclick=\"return OnAddWareUnit();\">\n");
	}
	else
	{
		echo("<table align=\"center\">\n");
		echo("  <tr height=\"20\"></tr>\n");
		echo("  <tr height=\"30\">\n");
		echo("    <td width=\"20\"><td width=\"40\">编号</td><td width=\"160\">名称</td><td width=\"160\">图片</td><td width=\"100\">商品编号</td><td width=\"350\">说明</td><td width=\"150\">所属类型</td><td width=\"48\"></td>\n");
		echo("  </tr>\n");

		while($ra = mysql_fetch_row($rs))
		{
			echo("  <tr>\n");
			echo("    <td><input type=\"checkbox\" name=\"chkbox\" id=\"chk_id\" value=\"$ra[0]\" /></td>\n");

			for($i = 0; $i < 6; $i++)
			{
				if($i == 5)		//把大类型名称和小类型名称合到一起
				{
					$str_typename = str_replace("&", "&amp;", $ra[5]." - ".$ra[6]);

					echo("    <td style=\"word-break:break-all;word-wrap:break-word\">$str_typename</td>\n");
				}
				else
				{
					if(1 == $i || 3 == $i || 4 == $i)
					{
						$str_temp = str_replace("&", "&amp;", $ra[$i]);

						if(4 == $i)
						{
							$str_temp = nl2br($str_temp);
						}

//						echo("    <td style=\"word-break:break-all;word-wrap:break-word\">".$str_temp."</td>\n");
						echo("    <td style=\"word-break:break-all;word-wrap:break-word\" align=\"left\">".$str_temp."</td>\n");
					}

					else
					{
						echo("    <td>".$ra[$i]."</td>\n");
					}
				}
			}

			$str_name = str_replace("\\", "\\\\", $ra[1]);
			$str_name = str_replace("\"", "\\\"", $str_name);

			$str_order_no = str_replace("\\", "\\\\", $ra[3]);
			$str_order_no = str_replace("\"", "\\\"", $str_order_no);

			$str_comment = str_replace("\\", "\\\\", $ra[4]);
			$str_comment = str_replace("\"", "\\\"", $str_comment);
			$str_comment = str_replace("\r\n", "\\n", $str_comment);

echo("\n");
echo("<script language=\"javascript\">\n");
echo("function ModifyWareUnit$ra[0]()\n");
echo("{\n");
echo("    if(BeforeModifyWareUnit($ra[0],'$ra[2]',$ra[7]) == false)\n");
echo("        return false;\n");
echo("\n");
echo("    ware_detail_form.ware_name.value = \"".$str_name."\";\n");	//项目名称
echo("    ware_detail_form.order_no.value = \"".$str_order_no."\";\n");
echo("    ware_detail_form.comment.value = \"".$str_comment."\";\n");
echo("}\n");
echo("</script>\n");
echo("\n");


			echo("    <td><input type=\"button\" name=\"btn_modify_type\" value=\"修改\" onclick=\"return ModifyWareUnit$ra[0]();\"></td>\n");

			echo("  </tr>\n");
		}

		echo("</table>\n");
		echo("<br><br>\n");
		echo("<input type=\"submit\" name=\"btn_del_sel\" value=\"删除选择\" onclick=\"return CheckSelected();\">\n");
		echo("<input type=\"submit\" name=\"btn_del_all\" value=\"删除全部\" onclick=\"return OnDeleteAll();\">\n");
		echo("<input type=\"button\" name=\"btn_add_type\" value=\"增加商品\" onclick=\"return OnAddWareUnit();\">\n");
	}

?>

<input type="hidden" name="items">
<input type="hidden" name="ware_type" value="ware">
</form>




<form style="visibility:hidden" action="_add_modify_wares.php" method="post" name="ware_detail_form" onsubmit="return CheckAddModify();">

<table align="center">
  <tr height="30"></tr>

  <tr height="30">
    <td colspan="2">详细信息</td>
  </tr>
  <tr height="30">
    <td width="80">商品名称：</td><td width="400"><input type="text" size="58" maxlength="80" name="ware_name" /></td>
  </tr>

  <tr height="30">
    <td>所属小类：</td><td width="400">
      <select name="cmb_tiny_type">
<?php
	$sql2 = "select a.no,concat(b.name,' - ',a.name) from WareTinyType a,WareBigType b where a.bigNo=b.no order by b.no,a.no";
	$rs2 = mysql_query($sql2, $link);

	while($ra2 = mysql_fetch_row($rs2))
	{
		echo("        <option value=\"$ra2[0]\">".str_replace("&", "&amp;", $ra2[1])."</option>\n");
	}


	include("../include/linkclose.php");
?>
      </select>
    </td>
  </tr>

  <tr height="30">
    <td>图片：</td><td width="400">
      <select name="cmb_img_path" onchange="OnPictureSelChanged()">
<?php
	function listdir($dir_name)
	{
		$dir = opendir($dir_name);
		while('' != ($file_name = readdir($dir)))
		{
			if(($file_name != '.') && ($file_name != '..'))
			{
				if(is_dir($dir_name."/".$file_name))
				{
					listdir($dir_name."/".$file_name); //递归调用列出子目录的文件及目录
				}
				else
				{
					$file_path = $dir_name."/".$file_name;	//文件的路径，格式为  ../pic/xx_sub_xx/xx_file_xx.jpg

					//去掉路径中的"../"
//					$file_path = str_replace('../', '', $file_path);
					$file_path = substr($file_path, 3);
					echo("        <option value=\"$file_path\">$file_path</option>\n");
				}
			}
		}

		closedir($dir);
		echo $file_list;
	}

	listdir('../pic');
?>
      </select>
  </tr>
  <tr height="30">
    <td>商品编号：</td><td width="400"><input type="text" size="58" maxlength="20" name="order_no" /></td>
  </tr>
  <tr height="80">
    <td>说明：</td><td width="400"><textarea name="comment" cols="45" rows="4"></textarea></td>
  </tr>

  <tr height="30">
    <td colspan="2"><input type="submit" name="btn_ok" value="&nbsp;&nbsp;确定&nbsp;&nbsp;"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="btn_cancel" onclick="OnDetailCancel();" value="&nbsp;&nbsp;取消&nbsp;&nbsp;"/></td>
  </tr>

</table>
<br />
<img width="320" height="240" id="img_selected" src="../images/sel_product_img.JPG" />
<input type="hidden" name="proc_desc" value="none">
<input type="hidden" name="item_id" value="0">
<input type="hidden" name="ware_type" value="ware">
</form>

</div>
</body>
</html>

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
<title>客户留言一览</title>
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

function CheckSelected()
{
	msg_form.items.value = "";		 //设置隐藏域的值为空

	if(!msg_form.chk_id.length)		//只有一个复选框，msg_form.item.length = undefined
	{
		if(msg_form.chk_id.checked)
		{
			msg_form.items.value = msg_form.chk_id.value;
		}
	}
	else
	{
		var		temp = "";

		for(i = 0; i < msg_form.chk_id.length; i++)
		{
			if(msg_form.chk_id(i).checked)	//复选框中有选中的框
			{
				temp = temp + msg_form.chk_id(i).value + ",";	//使用","分隔符将数组元素组合成一个字符串
			}
		}

		temp = temp.substring(0, temp.length - 1);	 //去除字符串末尾多余的","

		msg_form.items.value = temp;	//将变量赋值给隐藏域
	}

	if(msg_form.items.value == "")
		return false;

	return true;
}

function OnDeleteAll()
{
	msg_form.items.value = "0";
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

<form action="_delete_msg.php" method="post" name="msg_form">
<?php
	include("../include/linkopen.php");

	$sql="select * from guest_msg";
	$rs = mysql_query($sql, $link);

	if(mysql_num_rows($rs) == 0)
	{
		echo("<h3>没有记录</h3>\n");
	}
	else
	{
		echo("<table align=\"center\">\n");
		echo("  <tr height=\"20\"></tr>\n");
		echo("  <tr height=\"30\">\n");
		echo("    <td width=\"20\"></td><td width=\"40\">编号</td><td width=\"160\">日期</td><td width=\"100\">客户姓名</td><td width=\"160\">联系电话</td><td width=\"100\">IP地址</td><td width=\"70\">地区</td><td width=\"500\">留言内容</td>\n");
		echo("  </tr>\n");

		while($ra = mysql_fetch_row($rs))
		{
			echo("  <tr>\n");
			echo("    <td><input type=\"checkbox\" name=\"chkbox\" id=\"chk_id\" value=\"".$ra[0]."\" /></td>\n");

			for($i = 0; $i < 7; $i++)
			{
				if(2 == $i || 3 == $i || 6 == $i)
				{
					echo("    <td align=\"left\" style=\"word-break:break-all;word-wrap:break-word\">");

					$str_temp = str_replace("&", "&amp;", $ra[$i]);

					if(6 == $i)
					{
						echo(nl2br($str_temp)."</td>\n");
					}
					else
					{
						echo($str_temp."</td>\n");
					}

				}
				else
				{
					echo("    <td>".$ra[$i]."</td>\n");
				}
			}

			echo("  </tr>\n");
		}

		echo("</table>\n");
		echo("<br><br>\n");
		echo("<input type=\"submit\" name=\"btn_del_sel\" value=\"删除选择\" onclick=\"return CheckSelected();\">\n");
		echo("<input type=\"submit\" name=\"btn_del_all\" value=\"删除全部\" onclick=\"OnDeleteAll();\">\n");
	}

	include("../include/linkclose.php");
?>

<input type="hidden" name="items">
</form>
</div>
</body>
</html>

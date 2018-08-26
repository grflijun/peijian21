<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: admin_start.html");
	}

	$g_staff_id = 0;	//ID,不能为0

	$col_login_name = "";
	$col_realname = "";
	$col_previlige = "";

//    require_once('../../include/db_def.php');


	require_once "../../include/dbda.class.php";

	if(isset($_GET['USERID']) && $_GET['USERID'] != 0)
	{
		$g_staff_id = intval($_GET['USERID']);
		$sql_sel = "select login_name,realname,previliges from staffs where staff_id=$g_staff_id";

		$db = new DBDA();

		$result = $db->query_sql($sql_sel);
		if($row = $result->fetch_array())
		{
			$col_login_name = $row[0];
			$col_realname = $row[1];
			$col_previlige = $row[2];
		}


/*
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
			or die($str_inner_err . mysqli_error());

		$result = mysqli_query($link, $sql_sel)
		  or die($str_inner_err . mysqli_error());

		if($row = mysqli_fetch_array($result))
		{
			$col_login_name = $row[0];
			$col_realname = $row[1];
			$col_previlige = $row[2];
		}

		mysqli_close($link);
*/
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>员工权限</title>
<link rel="stylesheet" href="../../css/inner_style.css">

<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/default/easyui.css">
<link rel="stylesheet" href="../../include/3rd-part/easyui/themes/icon.css">
<script src="../../scripts/jquery-3.3.1.js"></script>
<script src="../../include/3rd-part/easyui/jquery.easyui.min.js"></script>
</head>

<body>
<div style="margin-top: 30px; margin-bottom: 30px;">
	<ul class="menu_list">
		<li><a href="../admin_config.php">主菜单</a></li>
		<li><a href="../admin_end.php">安全退出</a></li>
	</ul>
</div>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
	<div>
		<input type="text" hidden="hidden" name="the_staff_id" value="<?php echo $g_staff_id; ?>">
		<input type="text" hidden="hidden" name="the_result" id="the_result">
	</div>

	<fieldset class="input_pannel">
		<legend>员工权限信息</legend>

		<p><?php echo("$col_login_name <br> $col_realname"); ?></p>

		<table class="lst_tbl">
			<tr><th width="200px">工作内容</th><th width="100px">权限</th></tr>
<?php

	$db = new DBDA();

	$sql_sel = 'select func_code,func_name from sys_funcs order by func_code ASC';
	$result = $db->query_sql($sql_sel);
	while($row = $result->fetch_array())
	{
		$idx = $row[0];

		echo("<tr>\n<td>$row[1]</td>\n");
		echo("<td><input type='checkbox' value='$idx'");

		if(strlen($col_previlige) > $idx)
		{
			if($col_previlige[$idx] == '1')
			{
				echo(" checked='checked'");
			}
		}

		echo(">允许</td>\n</tr>\n");
	}


/*
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

	$sql_sel = 'select func_code,func_name from sys_funcs order by func_code ASC';

	$result = mysqli_query($link, $sql_sel)
	  or die($str_inner_err . mysqli_error());

	while($row = mysqli_fetch_array($result))
	{
		$idx = $row[0];

		echo("<tr>\n<td>$row[1]</td>\n");
		echo("<td><input type='checkbox' value='$idx'");

		if(strlen($col_previlige) > $idx)
		{
			if($col_previlige[$idx] == '1')
			{
				echo(" checked='checked'");
			}
		}

		echo(">允许</td>\n</tr>\n");
	}

	mysqli_close($link);
*/
?>
		</table>

<div style="margin-top: 30px; margin-bottom: 10px; text-align:center">
	<input type="submit" value="&nbsp;确&nbsp;定&nbsp;" name="btn_ok">
	<input type="button" value="&nbsp;取&nbsp;消&nbsp;" name="btn_cancel" onclick="javascript:history.back(-1);">
</div>
  </fieldset>
</form>

<?php
	if(isset($_POST['btn_ok']))
	{
		$db = new DBDA();

		if($db->CreateTempDb())
		{
			$the_id = intval($_POST['the_staff_id']);
			$the_result = $db->real_escape_string(trim($_POST['the_result']));

			$sql_modify = "update staffs set previliges='$the_result' where staff_id=$the_id ";

			if($db->temp_sql($sql_modify))
			{
				echo "<script> window.location.href = '../operate_ok.html' </script> ";
			}
			else
			{
				echo "<script> window.location.href = '../operate_ng.html' </script> ";
			}

			$db->DeleteTempDb();
		}


/*
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
			or die($str_inner_err . mysqli_error());

		$the_id = mysqli_real_escape_string($link, trim($_POST['the_staff_id']));
		$the_result = mysqli_real_escape_string($link, trim($_POST['the_result']));

		$sql_modify = "update staffs set previliges='$the_result' where staff_id=$the_id ";

		$result = mysqli_query($link, $sql_modify);
		if(mysqli_affected_rows($link) < 0)
		{
			echo "<script> window.location.href = '../operate_ng.html' </script> ";
		}
		else
		{
			echo "<script> window.location.href = '../operate_ok.html' </script> ";
		}

		mysqli_close($link);
*/
	}
?>

</div>

<script>
$(document).ready(function(){
	$("form").submit(function(e){
		var arr = new Array(30);
		for(var i = 0; i < arr.length; i++)
		{
			arr[i] = '0';
		}

		$("input:checkbox:checked").each(function(){
			var idx = $(this).val();
			if(idx < arr.length)
			{
				arr[idx] = '1';
			}
		});

		$("#the_result").val(arr.join(""));

		return true;
	});
});

</script>
</body>
</html>

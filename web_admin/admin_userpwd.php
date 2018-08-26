<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: admin_start.html");
	}

    require_once('../include/db_def.php');
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>修改密码</title>
<link rel="stylesheet" href="../css/inner_style.css">
<script src="../scripts/jquery-3.3.1.js"></script>
<script src="../scripts/common_use.js"></script>
</head>

<body>
<div class="bottom">
<table align="center">
<tr height="20"></tr>
<tr>
<td width="180"><a href="admin_config.php">主菜单</a></td>
<td width="180"><a href="admin_end.php">安全退出</a></td>
</tr>
<tr height="30"></tr>
</table>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" name="pwd_form">
<table align="center">

<tr height="30">
<td width="110">用&nbsp;&nbsp;户&nbsp;&nbsp;名：</td><td><input type="text" size="62" name="staff_name" id="staff_name" required="required"></td>
</tr>

<tr height="30">
<td width="110">新&nbsp;&nbsp;密&nbsp;&nbsp;码：</td><td><input type="password" size="62" name="new_pass1" id="new_pass1" required="required"></td>
</tr>
<tr><td></td><td><span id="pass_check"></span></td></tr>

<tr height="30">
<td width="110">确认新密码：</td><td><input type="password" size="62" id="new_pass2" required="required"></td>
</tr>

<tr height="30">
<td colspan="2" align="center"><input type="submit" name="btn_ok" value="&nbsp;&nbsp;确定&nbsp;&nbsp;"/></td>
</tr>

</table>
</form>

<?php
	if(isset($_POST['btn_ok']))
	{
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
			or die($str_inner_err . mysqli_error());

		$staff_name = mysqli_real_escape_string($link, trim($_POST['staff_name']));
		$new_pass = mysqli_real_escape_string($link, $_POST['new_pass1']);

		$sql_modify = "update staffs set login_pwd=SHA('$new_pass') where login_name='$staff_name'";

		$result = mysqli_query($link, $sql_modify);
		if($result)
		{
			echo "<script> window.location.href = 'operate_ok.html' </script> ";
		}
		else
		{
			echo "<script> window.location.href = 'operate_ng.html' </script> ";
		}

		mysqli_close($link);
	}
?>

</div>

<script>
$(document).ready(function(){
	//检测密码强度
	$('#new_pass1').keyup(function(e) {
		test_pass_strength(this, $('#pass_check'));

		return true;
	});


	$("form").submit(function(e){
		var pass1 = $('#new_pass1').val();
		var pass2 = $('#new_pass2').val();

		if(pass1 != pass2)
		{
			alert("两次输入的密码不一致");
			$("#new_pass1").focus();
			return false;
		}

		if(pass1.length < 6 || pass1.length > 12)
		{
			alert("密码长度应在6到12之间");
			$("#new_pass1").focus();
			return false;
		}

		return true;
	});
});

</script>

</body>
</html>

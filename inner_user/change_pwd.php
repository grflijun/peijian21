<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];
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
<div style="margin-top: 30px; margin-bottom: 30px;">
  <ul>
    <li><a href="user_config.php">主菜单</a></li>
    <li><a href="logout.php">安全退出</a></li>
  </ul>
</div>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
  <fieldset class="input_pannel">
    <legend>修改密码</legend>

    <label class="text_lbl">&nbsp;旧&nbsp;密&nbsp;码:&nbsp;</label>
    <input type="password" class="text_box" size="60" maxlength="20" name="old_pass" id="old_pass" required="required" onpaste="return false" oncontextmenu="return false" ondrop="return false" ondragenter="return false"><br>

    <label class="text_lbl">&nbsp;新&nbsp;密&nbsp;码:&nbsp;</label>
    <input type="password" class="text_box" size="30" maxlength="20" name="new_pass1" id="new_pass1" required="required" onpaste="return false" oncontextmenu="return false" ondrop="return false" ondragenter="return false"><br>
    <span id="pass_check"></span>

    <label class="text_lbl">确认新密码:</label>
    <input type="password" class="text_box" size="60" maxlength="20" name="new_pass2" id="new_pass2" required="required" onpaste="return false" oncontextmenu="return false" ondrop="return false" ondragenter="return false"><br>

    <span class="btn_span">
      <input type="submit" value="&nbsp;确&nbsp;定&nbsp;" name="btn_ok">
    </span>
  </fieldset>
</form>

<?php
	if(isset($_POST['btn_ok']))
	{
		include("../include/linkopen.php");

		$staff_id = $_SESSION['STAFF_ID'];
		$old_pass = mysqli_real_escape_string($link, $_POST['old_pass']);
		$new_pass = mysqli_real_escape_string($link, $_POST['new_pass1']);

		$sql = "update staffs set login_pwd=SHA('$new_pass') where staff_id=$staff_id and login_pwd=SHA('$old_pass')";

		$result = mysqli_query($link, $sql);
		if(mysqli_affected_rows($link) < 0)
		{
			echo "<script> window.location.href = 'operate_ng.html' </script> ";
		}
		else
		{
			echo "<script> window.location.href = 'operate_ok.html' </script> ";
		}

		include("../include/linkclose.php");
	}
?>

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

		if(pass1.length < 6 || pass1.length > 12)
		{
			alert("密码长度应在6到12之间");
			$("#new_pass1").focus();
			return false;
		}

		if(pass1 != pass2)
		{
			alert("两次输入的密码不一致");
			$("#new_pass2").focus();
			return false;
		}

		return true;
	});
});

</script>

</body>
</html>

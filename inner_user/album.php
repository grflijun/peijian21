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
<title>相册</title>
<link rel="stylesheet" href="../css/inner_style.css">
</head>

<body>
<div class="bottom">
  <div>
    <br>
    <a href="user_config.php">主菜单</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="logout.php">安全退出</a>
  </div>

<table>
<tr height="30">
  <td width="32"></td><td width="260"></td><td width="32"></td><td width="260"></td><td width="32"></td><td width="260"></td><td width="32"></td><td width="260"></td>
<?php
	$file_cnt = 0;

	function listdir($dir_name)
	{
		global $file_cnt;

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
					if(($file_cnt & 0x03) == 0)
					{
						echo("<td width=\"32\"></td>\n</tr>\n<tr height=\"235\">\n");
					}
					$file_cnt++;

					echo("  <td></td>\n");
					echo("  <td>\n");
					echo("    <table>\n");
					echo("      <tr width=\"260\" height=\"202\">\n");
					echo("        <td align=\"center\"><img width=\"256\" height=\"192\" src=\"$dir_name/$file_name\"></td>\n");
					echo("      </tr>\n");
					echo("      <tr width=\"260\" height=\"30\">\n");
					echo("        <td align=\"center\" valign=\"top\">$file_name</td>\n");
					echo("      </tr>\n");
					echo("    </table>\n");
					echo("  </td>\n");
				}
			}
		}

		closedir($dir);
		echo $file_list;
	}

	listdir('../pic');
?>
</tr>
</table>

</div>
</body>
</html>

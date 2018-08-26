<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>安全退出</title>
<link rel="stylesheet" href="../css/inner_style.css">

</head>
<body>
<?php
	$_SESSION = array();

	session_destroy();
	header("location:../index.php");
?>
</body>
</html>
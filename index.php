<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">

<?php
require_once 'include/const_def.php';
    //require_once 'include/title.php';

    $g_lan_id = LAN_ID_CN;    //默认中文

    if(isset($_GET['lid']) && $_GET['lid'] == "1")     //英语
    {
        $g_lan_id = LAN_ID_EN;
    }
?>

<style type="text/css">
*{margin:0;padding:0;list-style-type:none;}
a,img{border:0;text-decoration:none;}
html, body{font-family:'宋体';font-size:13px;background:#f0f3ef;}
</style>

<link rel="stylesheet" href="css/tmailsilder.css" />
</head>

<body>

<div style="height:10px;"></div>
<div style="float:right;margin-right: 40px;">
  <a href="index.php">中文</a>&nbsp;|&nbsp;<a href="index.php?lid=1">ENGLISH</a>
</div>
<div style="height:30px;"></div>

<div id="Z_TMAIL_SIDER_V2" class="sw_categorys_nav">
  <div class="container">
    <div class="allcategorys">
      <h3 class="title-item-hd"><a href="javascript:void(0);">所有商品分类<s class="icon"></s></a></h3>
      <ul class="sublist">

<?php
    $link = mysqli_connect("localhost:3306", "renyanli", "dali123", "huijie")
      or die($str_inner_err . mysqli_error());

    $sql_sel = "select a.folder_name,b.type_id,b.type_name from type_folder a,type_item b where b.folder_id=a.folder_id and a.lan_code=$g_lan_id order by a.disp_idx,b.disp_idx asc";

    $result = mysqli_query($link, $sql_sel)
      or die($str_inner_err . mysqli_error());

    $last_folder_name = "";
    $started = false;

    while($row = mysqli_fetch_array($result))
    {
        $cur_folder_name = $row[0];

        //大分类
        if($cur_folder_name != $last_folder_name)
        {
            if($started)    //把之前的结尾补上
            {
                echo("</p>\n</li>\n");
            }

            echo("<li>\n");
            echo("<h3 class=\"mcate-item-hd\"><span>$cur_folder_name</span></h3>\n");
            echo('<p class="mcate-item-bd">');

            $last_folder_name = $cur_folder_name;
            $started = true;
        }

        echo("<a href=\"products.php?tid=$row[1]\">$row[2]</a>\n");
    }
    if($started)    //把之前的结尾补上
    {
        echo("</p>\n</li>\n");
    }


    mysqli_close($link);
?>
      </ul>
    </div>

<div class="test_css"><h3 class="test_aaa"><a href="javascript:void(0);">查找产品</a></h3></div>
<div class="test_css"><h3 class="test_aaa"><a href="javascript:void(0);">公司简介</a></h3></div>
<div class="test_css"><h3 class="test_aaa"><a href="javascript:void(0);">联系我们</a></h3></div>
<div class="test_css"><h3 class="test_aaa"><a href="javascript:void(0);">产品定制</a></h3></div>
<div class="test_css"><h3 class="test_aaa"><a href="javascript:void(0);">个人中心</a></h3></div>

  </div>

</div>

<div style="height:1600px;"></div>



<script src="scripts/jquery-1.4.2.min.js"></script> 
<script src="scripts/jquery.tmailsilder.v2.js"></script>
<script>
$(document).ready(function(){
  $('#Z_TMAIL_SIDER_V2').Z_TMAIL_SIDER_V2();
});
</script>


</body>
</html>

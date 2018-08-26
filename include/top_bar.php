<?php
    require_once 'include/commonvar.php';
    
    $m_title = "广瑞丰 - 配件专营";
    $m_keywords = "铝合金配件,门窗配件,滑轮,锁,合页,铰链,玻璃胶";
    
    session_start();

    if(isset($_SESSION['lan_id']) && LAN_ID_EN == $_SESSION['lan_id'])
    {
        $m_title = "greefine - building hardwares for aluminum windows and doors";
        $m_keywords = "aluminum hardware,roller,lock,silicone sealant";
    }
    
    echo '<meta name="keyword" contents="'.$m_keywords.'">';
    echo "<title>$m_keywords</title>";
?>

<?php
header("content-type: text/xml");
// make an associative array of callers we know, indexed by phone number
   
    

    // if the caller is known, then greet them by name
    // otherwise, consider them just another monkey

    
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<Response>";
        echo '<Gather timeout="20" method="POST" action="twilio-kakunin.php" finishOnKey="#">';
        echo '<Say voice="woman" language="ja-jp">';
        echo "こちらはメディパスサポートセンターです。傷病者のシリアルナンバーを入力してください。";
        echo "</Say>";
        echo "</Gather>";
        echo "</Response>";
    
?>
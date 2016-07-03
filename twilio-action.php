<?php
 $con = mysql_connect('mosimo.cdnptegajdb5.ap-northeast-1.rds.amazonaws.com', 'shindotaito', 'Taito1226');
    if (!$con) {
  echo'<Say>データベースに接続できませんでした。</Say>';
    }
  $result = mysql_select_db('mosimo', $con);
    if (!$result) {
   echo'<Say>データベースを選択できませんでした。</Say>';
    }
  mysql_query('set names utf8',$con);
if($_POST['Digits'] == 1){
  $query = "SELECT number FROM save where 救急隊番号='".$_REQUEST['From']."'";
  $row=mysql_query($query);
  $out=mysql_fetch_assoc($row);
  $query2 = "SELECT year,month,day,byoumeiid FROM 病名履歴テーブル where 患者ID='".$out['number']."' order by year asc, month asc, day asc ";
  $row2=mysql_query($query2);
  header("content-type: text/xml");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  echo '<Response>';
  echo '<Say voice="woman" language="ja-jp">';
  echo '病歴を確認します。</Say>';
  echo '<Say voice="woman" language="ja-jp">';
  while($output = mysql_fetch_array($row2)){
  echo $output['year'];
  echo '年';
  echo $output['month'];
  echo '月';
  echo $output['day'];
  echo '日。';
  echo $output['byoumeiid'];
  echo '。';
  }
  echo '以上の症状を発症しております</Say>';
  echo '</Response>';
  
}

if($_POST['Digits'] == 2){
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>";
echo '<Gather timeout="20"  action="twilio-kakunin.php" finishOnKey="#">';
echo '<Say voice="woman" language="ja-jp">';
echo "シリアルナンバーとシャープ記号を入力してください。";
echo "</Say>";
echo "</Gather>";
echo "</Response>";}
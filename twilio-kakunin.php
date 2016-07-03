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
  $number=$_REQUEST['Digits'];
  $from=$_REQUEST['From'];

//電話履歴テーブルに挿入する
// $insertQuery = "INSERT INTO history (groupID, phoneNumber, patientID) VALUES (1, '".$from."','".$number."')";
// mysql_query($insertQuery);

  $query="SELECT 苗字,名前,性別,年齢,家族連絡先１,家族連絡先２,家族連絡先３,家族連絡先４,家族連絡先５,都道府県,地域,病院名,医師名 FROM 患者テーブル WHERE 患者ID='".$number."'";
  $row=mysql_query($query);
  $output=mysql_fetch_assoc($row);
  $query2="SELECT 救急隊番号 FROM save";
  $row2=mysql_query($query2);
  $array_tel=array($output['家族連絡先１'],$output['家族連絡先２'],$output['家族連絡先３'],$output['家族連絡先４'],$output['家族連絡先５']);
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>";
if($output['名前']){
echo '<Gather timeout="60" numDigits="1" action="twilio-action.php">';
echo '<Say voice="woman" language="ja-jp">';
echo 'このかたは';
echo $output['苗字'];
echo $output['名前'];
echo 'さん。';
echo '性別は';
echo $output['性別'];
echo '。';
echo '年齢は';
echo $output['年齢'];
echo '歳です。';
echo 'かかりつけ病院は';
echo $output['地域'];
echo '、';
echo $output['病院名'];
echo 'です。';
echo '病歴確認は1を、シリアルナンバーの再入力は2を、入力してください';
echo "</Say>";
echo "</Gather>";
$counter=0;
$counter2=0;
        while($out=mysql_fetch_array($row2)){
         if($out['救急隊番号']==$_REQUEST['From']&&$counter==0){
            $query3 = "UPDATE mosimo.save set number='".$number."' where 救急隊番号='".$_REQUEST['From']."'";
            $counter=1;
         }
         if($out['救急隊番号']==$_REQUEST['From']&&$counter2==0){
            $query4 = "UPDATE mosimo.save2 set 電話番号1='".$output['家族連絡先１']."',電話番号2='".$output['家族連絡先２']."',電話番号3='".$output['家族連絡先３']."',電話番号4='".$output['家族連絡先４']."',電話番号5='".$output['家族連絡先５']."' where 救急隊番号='".$_REQUEST['From']."'";
            $counter2=1;
         }
        }
        if($counter==0){
           $query3 = "INSERT INTO save(number,救急隊番号) VALUES ('".$number."','".$_REQUEST['From']."')";
        }
        if($counter2==0){
           $query4 = "INSERT INTO save2(救急隊番号,電話番号1,電話番号2,電話番号3,電話番号4,電話番号5) VALUES ('".$_REQUEST['From']."','".$output['家族連絡先１']."','".$output['家族連絡先２']."','".$output['家族連絡先３']."','".$output['家族連絡先４']."','".$output['家族連絡先５']."')";
        }
        $row3=mysql_query($query3);
        $row4=mysql_query($query4);
}
elseif($number=="*"){
  $query1 = "SELECT number FROM save where 救急隊番号='".$_REQUEST['From']."'";
  $row1=mysql_query($query1);
  $row1_out=mysql_fetch_assoc($row1);
  $query="SELECT 苗字,名前,性別,年齢,家族連絡先１,家族連絡先２,家族連絡先３,家族連絡先４,家族連絡先５,都道府県,地域,病院名,医師名 FROM 患者テーブル WHERE 患者ID='".$row1_out['number']."'";
  $row=mysql_query($query);
  $output=mysql_fetch_assoc($row);
echo '<Gather timeout="60" numDigits="1" action="twilio-action.php">';
echo '<Say voice="woman" language="ja-jp">';
echo '一つ前の家族は';
echo $output['苗字'];
echo $output['名前'];
echo 'さん。';
echo '性別は';
echo $output['性別'];
echo '。';
echo '年齢は';
echo $output['年齢'];
echo '歳です。';
echo 'かかりつけ医は';
echo $output['都道府県'];
echo $output['地域'];
echo '、';
echo $output['病院名'];
echo '、';
echo $output['医師名'];
echo '先生です。';
echo '病歴確認は1を、家族連絡は2を、かかりつけ医師の連絡は3を、入力してください。シリアルナンバーの再入力は4を、入力してください';
echo "</Say>";
echo "</Gather>";
}
else
{
echo '<Say voice="woman" language="ja-jp">';
echo '該当する番号を発見できませんでした。';
echo "</Say>";
echo '<Gather timeout="20"  action="twilio-kakunin.php" finishOnKey="#">';
echo '<Say voice="woman" language="ja-jp">';
echo "もう一度傷病者のシリアルナンバーとシャープを入力してください。";
echo "</Say>";
echo "</Gather>";
}
echo "</Response>";
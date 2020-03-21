<!DOCTYPE html>

<head>
  <meta charset = "UTF-8">
<title>コメント入力フォーム</title>
</head>

<body>
<form action = "mission_2.php" method = "post">
  <input type = "text" value = "コメント" name = "comment"><br>
  <input type = "submit" value = "送信">
</form>

<?php
    if (!empty($_POST['comment'])){

$comment = $_POST['comment'];
$filname = "mission_2.txt";
$fp = fopen($filname, "a");
      fwrite($fp , $comment."\n");
      fclose($fp);
    if ($comment == "完成！"){
       echo "おめでとう！";
    }else {
       echo $comment . "を受け付けました<br>";
    }
    }
?>
</body>

</html>
<!DOCTYPE htlm>

<html>

<head>
  <meta chart = "UTF-8" >
  <title>入力フォーム</title>
</head>

<body>
  <h1>新規入力フォーム<br></h1>
  <form action = "mission_3.php" method = "post" >
    <input type = "text" value = "名前" name = "name" ><br>
    <input type = "password" name = "password"><br>
    コメント<br>
    <textarea name = "comment" rows = "5" cols = "50"></textarea><br>
    <input type = "submit" value = "送信" >
  <h2>削除番号指定用フォーム<br></h2>
    削除対象番号<br>
    <input type = "number" name = "number" ><br>
    <input type = "password" name = "d_password"><br>
    <input type = "submit" value = "削除">
  <h2>編集番号指定用フォーム<br></h2>
    編集対象番号<br>
    <input type = "number" name = "cnumber"><br>
    <input type = "password" name = "e_password"><br>
    <input type = "submit" value = "編集"><br>
  </form>
</body>

  <?php
    $filename = "mission_3.txt";
    $flag = 0;
    $date = date("Y/m/d H:i:s");
//編集フォームの処理
//if(edit())/編集の変数($flag)
	if( !empty($_POST["name"]) && !empty($_POST["comment"]) ){
	  $edit_name = $_POST["name"]; //編集用に変数作成①
	  $edit_comment = $_POST["comment"]; //②
	  if( !empty($_POST["cnumber"]) && !empty($_POST["e_password"]) ){
	    $flag = 1;
	    $edit_password = $_POST["e_password"];//③
	    $edit_number = $_POST["cnumber"];
	    $file_data = file($filename);//行ごとに箱に入れる(save)
	    //書き込み（ファイル内をまっさらに）
	    $fp = fopen($filename , "w");
	    fwrite($fp ,"");
	    fclose($fp);//clean
	    //ファイル内に編集したものを書き込む
	    $fp = fopen($filename , "a");
	    //ここから
		foreach($file_data as $str){
		  $get_line = explode("<>" , $str);
		  //書き込む文字列生成 *passwordが一致したときのみ
		  if($get_line[0] == $edit_number && $get_line[4] == $edit_password){
			$edit_line = $get_line[0] . "<>" . $edit_name . "<>" . $edit_comment . "<>" . $date . "<>" . $edit_password . "<>" ."\n";
			fwrite($fp , $edit_line);
		  }elseif($get_line[0] == $edit_number && $get_line[4] != $edit_password){
			fwrite($fp ,$str);
			echo "パスワードが違います<br>";
		  }else{
			fwrite($fp ,$str);
		  }
		}
	    fclose($fp);
	  }
	}
//新規フォームの処理
	if( !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) ){
	  if($flag == 0){
	    $num = 1;
	    $name = $_POST["name"];
	    $comment = $_POST["comment"];
	    $password = $_POST["password"];
	    $fp = fopen($filename , "a");
	    fwrite($fp , "");
	    fclose($fp);
	    $file_data = file($filename);//行ごとに箱に入れる
	    //1以降の投稿番号
	    if(count($file_data) >= 1){
		  $last_line = $file_data[count($file_data)-1];
		  $str = explode("<>" , $last_line);
		  $num = intval($str[0])+1;
	    }
	    $message = $num . "<>" . $name . "<>" . $comment. "<>" . $date . "<>" . $password . "<>";
	    $fp = fopen($filename , "a");
	    fwrite($fp , $message . "\n");
	    fclose($fp);
	  }
	}
//削除フォームの処理
	if( !empty($_POST["number"]) && !empty($_POST["d_password"]) ){
	  $delete_num = $_POST["number"];
	  $delete_password = $_POST["d_password"];
	//  $delete_password = $_POST["password"];
	  $file_data = file($filename);
	  //書き込み（ファイルの中身を空白に)
	  $fp = fopen($filename , "w");
	  fwrite($fp , "");
	  fclose($fp);
	  foreach($file_data as $str){
	    $get_line = explode("<>" , $str);
	    //passwordが一致したら削除：削除番号とパスワードが一致
	    if($get_line[0] == $delete_num && $get_line[4] == $delete_password){
		  echo "削除しました<br>";
	    //passwordが一致しなかったとき
	    }elseif($get_line[0] == $delete_num && $get_line[4] != $delete_password){
		  //書き込む：削除番号は指定されててもパスワードが違うからだめ
		  $fp = fopen($filename , "a");
		  fwrite($fp , $str);
		  fclose($fp);
		  echo "パスワードが違います<br>";
	    }else{
		  //削除番号以外の処理、書き込む：パスワードがあってても削除番号と一致しないor削除番号もパスワードも一致しない
		  $fp = fopen($filename , "a");
		  fwrite($fp , $str);
		  fclose($fp);
	    }
	  }
	}
//表示プログラム
	if( file_exists($filename) && !empty($filename) ){
	    $file_data = file($filename);//行ごとに箱に入れる
	    foreach($file_data as $str){
		$get_line = explode("<>" , $str);
		    echo $get_line[0] . " " . $get_line[1] . $get_line[2] . $get_line[3] . "<br>";
		    //htmlだったら<br>でよいが、phpだったら"<br>"で文字列としてhtmlに渡してあげないといけない
	    }
	}
  ?>
</html>
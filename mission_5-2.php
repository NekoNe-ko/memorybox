//GitHub：ソースコード管理サービス。公開されているソースコードの閲覧や簡単なバグ管理機能、SNSの機能を備える。
//GitHubアカウントに登録
//リポジトリ：ファイルやディレクトリの状態を記録。変更履歴として格納。

<!DOCTYPE htlm>

<html>

<head>
  <meta chart = "UTF-8" >
  <title>入力フォーム</title>
</head>

<body>
<h1>新規入力フォーム<br></h1><!-HTMLでのコメントアウト->
    <form action = "mission_5-1.php" method = "post" >
    <input type = "text" value = "名前" name = "name" ><br>
    パスワード<br>
    <input type = "password" name = "n_password"><br>
    コメント<br>
    <textarea name = "comment" rows = "5" cols = "50"></textarea><br>
    <input type = "submit" value = "送信" >
<h2>削除番号指定用フォーム<br></h2>
    削除対象番号<br>
    <input type = "number" name = "d_number" ><br>
    パスワード<br>
    <input type = "password" name = "d_password"><br>
    <input type = "submit" value = "削除">
<h2>編集番号指定用フォーム<br></h2>
    編集対象番号<br>
    <input type = "number" name = "e_number"><br>
    パスワード<br>
    <input type = "password" name = "e_password"><br>
    <input type = "submit" value = "編集"><br><!-機能→削除と編集->
  </form>
</body>

  <?php
//phpとMySQLの連携(4-1)
    //$dsnの中にスペースは入れないこと！
    //DB接続情報
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING) );
    //array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)：データベース操作で発生したエラーを警告として表示してくれる設定
    $date = date("Y/m/d H:i:s");// "DATETIME"  YYYY-MM-DD HH:MM:SS[.fraction]

//データベース内にテーブルを作成：createコマンドを使う。投稿番号、名前、コメント、日付、パスワード。(4-2)
    //IF NOT EXISTSを加えないと、２回目以降の呼び出し時にエラーが出る（１回目で既に存在するテーブルを２回目以降から重複で作ってしまうため）
    //$sql = "drop table tbokashi";
    //$stmt = $pdo->query($sql);//テーブル再作成
    $sql = "CREATE TABLE IF NOT EXISTS tbokashi"
    //テーブル名=tbokashi
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "n_password TEXT,"
    . "date DATETIME"
    .");";
    $stmt = $pdo->query($sql);

//テーブル一覧を表示するコマンド：作成できたかの確認(4-3)
    $sql = 'SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach($result as $row){
	echo $row[0];
	echo '<br>';
    }
    echo "<hr>";//hrとは水平の横線を引くタグ
//テーブルの中身を確認するコマンド：意図した内容のテーブルが作成されているかの確認(4-4)
    $sql= 'SHOW CREATE TABLE tbokashi';
    $result = $pdo -> query($sql);
    foreach($result as $row){
	echo $row[1];
    }
    echo "<hr>";

//編集フォームの処理：入力したデータをupdateによって編集*編集できているかの確認はselect(4-7)
    $flag = 0;
    if( !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["n_password"]) ){
	$edit_name = $_POST["name"]; //編集用に変数作成①変更する名前
	$edit_comment = $_POST["comment"]; //②変更するコメント
	if( !empty($_POST["e_number"]) && !empty($_POST["e_password"]) ){
	    $flag = 1;
	    $edit_number = $_POST["e_number"];//③変更する投稿番号
	    $edit_password = $_POST["e_password"];//④変更するパスワード
	    $sql = 'update tbokashi set name=:edit_name,comment=:edit_comment,n_password=:edit_password,date=:date where id=:edit_number';//a
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':edit_name', $edit_name, PDO::PARAM_STR);
	    $stmt->bindParam(':edit_comment', $edit_comment, PDO::PARAM_STR);
	    $stmt->bindParam(':edit_password', $edit_password, PDO::PARAM_STR);
	    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
	    $stmt->bindParam(':edit_number', $edit_number, PDO::PARAM_INT);
	    $stmt->execute();
	}
//新規フォームの処理:作成したテーブルにinsertを行ってデータ入力(4-5)
	if($flag == 0){
	    $sql = $pdo -> prepare("INSERT INTO tbokashi (name , comment , n_password , date) VALUES (:name, :comment, :n_password, :date )");//a
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	    $sql -> bindParam(':n_password', $password, PDO::PARAM_STR);
	    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	    $name = $_POST["name"];
	    $comment = $_POST["comment"];
	    $password = $_POST["n_password"];
	    $sql -> execute();
	}
    }
//削除フォームの処理：入力したデータをdeleteによって削除*削除できているかはselectで確認(4-8)
	if( !empty($_POST["d_number"]) && !empty($_POST["d_password"]) ){
	    $delete_num = $_POST["d_number"];
	    $delete_password = $_POST["d_password"];
	    $sql = 'delete from tbokashi where id=:delete_num';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':delete_num', $delete_num, PDO::PARAM_INT);
	    $stmt->execute();
	}
//入力したデータをselectによって表示（4-6）
	$sql = 'SELECT * FROM tbokashi';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	    //$rowの中にはテーブルのカラム名
	    echo $row['id'].',';
	    echo $row['name'].',';
	    echo $row['comment'].',';
            //echo $row['n_password'].',';
	    //echo var_dump($row['date']).'<br>';
	    echo $row['date'].'<br>';
	echo "<hr>";
	}

  ?>

</html>



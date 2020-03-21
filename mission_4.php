<!DOCTYPE html>
<html>

  <?php
//4-1
    //phpとMySQLの連携
    //$dsnの中にスペースは入れないこと！
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING) );
    //array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)：データベース操作で発生したエラーを警告として表示してくれる設定
//4-2
    //データベース内にテーブルを作成：createコマンドを使う
    //IF NOT EXISTSを加えないと、２回目以降の呼び出し時にエラーが出る（１回目で既に存在するテーブルを２回目以降から重複で作ってしまうため）
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";
    $stmt = $pdo->query($sql);
//4-3
    //テーブル一覧を表示するコマンド：作成できたかの確認
    $sql = 'SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach($result as $row){
	echo $row[0];
	echo '<br>';
    }
    echo "<hr>";//hrとは水平の横線を引くタグ
//4-4
    //テーブルの中身を確認するコマンド：意図した内容のテーブルが作成されているかの確認
    $sql= 'SHOW CREATE TABLE tbtest';
    $result = $pdo -> query($sql);
    foreach($result as $row){
	echo $row[1];
    }
    echo "<hr>";
//4-5
    //作成したテーブルにinsertを行ってデータ入力
    $sql = $pdo -> prepare("INSERT INTO tbtest (name , comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $name = 'ねーこ';
    $comment = 'あだ名だよ。';
    $sql -> execute();
//4-6
    //入力したデータをselectによって表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名（カラムとは行列の列のこと）
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
    echo "<hr>";
    }
//4-7
    //入力したデータをupdateによって編集（編集できているかの確認はselect）
    $id = 1;//変更する投稿番号
    $name = "れな";
    $comment = "本名だよ。";//変更したい名前とコメント
    $sql = 'update tbtest set name=:name,comment=:comment where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    //入力したデータをselectによって表示（4-6より）
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
    echo "<hr>";
    }
//4-8
    //入力したデータをdeleteによって削除（削除できているかはselectで確認）
    $id =2;
    $sql = 'delete from tbtest where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    //入力したデータをselectによって表示（4-6より）
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
    echo "<hr>";
    }

   ?>

</html>
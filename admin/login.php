<?php
require_once('../../inc/config.php');
require_once('../inc/functions.php');

// エラーメッセージ
$err = null;

if (isset($_POST['username']) && isset($_POST['password'])) {

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
    ]);

    $sql = 'SELECT * FROM users WHERE name = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  // 結果が存在し、パスワードも正しいか確認
  if ($result && password_verify($_POST['password'], $result['password'])) {
    session_start();
    $_SESSION['id'] = $result['id'];
    header('Location: index.php');
  } else {
    $err = "ログインできませんでした。";
  }
} else {
  // ログインしていたらログイン画面に遷移させない
  session_start();
  if (isset($_SESSION['id'])) {
    header('Location: index.php');
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="login.php" method="post">
    <?php
    if (!is_null($err)) {
      echo '<div>' . $err . '</div>';
    }
    ?>
    <input type="text" name="username" placeholder="ユーザー名">
    <input type="text" name="password" placeholder="パスワード">
    <button type="submit">ログインする</button>
  </form>
  <p><a href="../">一覧に戻る</a></p>
</body>

</html>
<?php
  session_start();
  if (isset($_SESSION['id'])){
    unset($_SESSION['id']);
  } else {
    header('Location: ../index.php');
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
  <p>ログアウトしました。</p>
  <p><a href="../">一覧に戻る</a></p>
</body>
</html>
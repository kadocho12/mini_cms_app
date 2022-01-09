<?php
  require_once('../../inc/config.php');
  require_once('../inc/functions.php');

  // ログイン確認
  isLogin();

  // 変更ボタンが押されたか確認
  if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
  }

 // CSRF対策トークンのチェック
  check_token();

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'UPDATE posts SET title=?, category_id=?, content=? WHERE id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $_POST['title'] , PDO::PARAM_STR);
    $stmt->bindValue(2, (int)$_POST['category_id'] , PDO::PARAM_INT);
    $stmt->bindValue(3, $_POST['content'] , PDO::PARAM_STR);
    $stmt->bindValue(4, (int)$_POST['id'] , PDO::PARAM_INT);
    $stmt->execute();

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '記事の更新';
  require_once('../inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>
        <p>記事を更新しました。</p>
        <p><a href="./">一覧に戻る</a></p>
      </section>
    </div>
  </main>
<?php require_once('../inc/footer.php'); ?>

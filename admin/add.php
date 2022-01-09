<?php
  require_once('../../inc/config.php');
  require_once('../inc/functions.php');

  // ログイン確認
  isLogin();

  // 投稿ボタンが押されたか確認
  if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: post.php');
    exit();
  }

  // CSRF対策 ・・・ トークンのチェック
  check_token();

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
    ]);

    $sql = 'INSERT INTO posts (title, category_id, content, created) VALUES(?, ?, ?, now())';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $_POST['title'], PDO::PARAM_STR);
    $stmt->bindValue(2, (int)$_POST['category_id'], PDO::PARAM_INT);
    $stmt->bindValue(3, $_POST['content'], PDO::PARAM_STR);
    $stmt->execute();

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '登録完了';
  require_once('../inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>
        <p>新着情報を登録しました。</p>
        <p><a href="./">一覧に戻る</a></p>
      </section>
    </div>
  </main>
<?php require_once('../inc/footer.php'); ?>

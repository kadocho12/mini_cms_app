<?php
  require_once('../../inc/config.php');
  require_once('../inc/functions.php');

  // ログイン確認
  isLogin();
  // CSRF対策トークンの生成
  set_token();

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
    ]);

  // $sql = 'SELECT * FROM posts ORDER BY created DESC';
    $sql = 'SELECT p.*, c.category_name FROM posts AS p JOIN categories AS c ON p.category_id = c.id ORDER BY id DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '管理画面';
  require_once('../inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>
        <p><a href="post.php">新しい記事を投稿する</a></p>
        <table border="1" class="table">
          <thead>
            <tr>
              <th>タイトル</th>
              <th>カテゴリー</th>
              <th>公開日</th>
              <th>更新日</th>
              <th>編集</th>
              <th>削除</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($result as $row) : ?>
            <tr>
              <td><?php echo h($row['title']); ?></td>
              <td><?php echo h($row['category_name']); ?></td>
              <td><time datetime="<?php echo h($row['created']); ?>"><?php echo h(date('Y年m月d日', strtotime($row['created']))); ?></time></td>
              <td><time datetime="<?php echo h($row['modified']); ?>"><?php echo h(date('Y年m月d日', strtotime($row['modified']))); ?></time></td>
              <td><a href="edit.php?id=<?php echo h($row['id']); ?>">編集</a></td>
              <td>
                <form action="delete.php" method="post">
                  <input type="hidden" name="id" value="<?php echo h($row['id']); ?>">
                  <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                  <input type="submit" value="削除">
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>
    </div>
  </main>
<?php require_once('../inc/footer.php'); ?>

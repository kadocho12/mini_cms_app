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

    $sql = 'SELECT * FROM categories';
    $stmt = $dbh->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '投稿フォーム';
  require_once('../inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>
        <p><a href="./">一覧へ戻る</a></p>
        <form action="add.php" method="post" enctype="multipart/form-data">
          <dl class="define-table">
            <dt><label for="title">記事のタイトル</label></dt>
            <dd>
              <input type="text" id="title" name="title" class="textfield">
            </dd>
            <dt><label for="category_id">カテゴリー</label></dt>
            <dd>
              <select name="category_id" id="category_id">
                <?php foreach($result as $row) : ?>
                <option value="<?php echo h($row['id']); ?>"><?php echo h($row['category_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </dd>
            <dt><label for="content">記事の内容</label></dt>
            <dd>
              <textarea name="content" id="content" cols="30" rows="10" class="textfield"></textarea>
            </dd>
          </dl>
          <p><input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>"></p>

          <p><input type="submit" value="投稿" class="button button-primary"></p>
        </form>
      </section>
    </div>
  </main>
<?php require_once('../inc/footer.php'); ?>

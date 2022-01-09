<?php
  require_once('../../inc/config.php');
  require_once('../inc/functions.php');

  // ログイン確認
  isLogin();

  if ( empty($_GET['id']) ) {
    header('Location: index.php');
    exit();
  }

  // CSRF対策トークンの生成
  set_token();

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 記事取得
    $sql = 'SELECT p.*, c.category_name FROM posts AS p JOIN categories AS c ON p.category_id = c.id WHERE p.id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, (int)$_GET['id'] , PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // 全カテゴリ取得
    $sql = 'SELECT * FROM categories';
    $stmt = $dbh->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '記事の編集';
  require_once('../inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>
        <p><a href="./">一覧へ戻る</a></p>

        <form action="update.php" method="post">
          <dl class="define-table">
            <dt><label for="title">記事のタイトル</label></dt>
            <dd>
              <input type="text" id="title" name="title" class="textfield" value="<?php echo h($result['title']); ?>">
            </dd>
            <dt><label for="category_id">カテゴリー</label></dt>
            <dd>
              <select name="category_id" id="category_id">
                <?php foreach($categories as $category) : ?>
                <?php
                  $selected = '';
                  // ループで出力するカテゴリと、データベースから取得したカテゴリが一致するかチェック
                  if ($category['id'] == $result['category_id']) {
                    $selected = ' selected';
                  }
                ?>
                <option value="<?php echo h($category['id']); ?>"<?php echo h($selected); ?>><?php echo h($category['category_name']); ?></option>
                <?php endforeach; ?>
              </select>
            </dd>
            <dt><label for="content">記事の内容</label></dt>
            <dd>
              <textarea name="content" id="content" cols="30" rows="10" class="textfield"><?php echo h($result['content']); ?></textarea>
            </dd>
          </dl>
          <p><input type="hidden" name="id" value="<?php echo h($result['id']); ?>"></p>
          <p><input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>"></p>
          <p><input type="submit" value="変更" class="button button-primary"></p>
        </form>
      </section>
    </div>
  </main>
<?php require_once('../inc/footer.php'); ?>

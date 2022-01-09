<?php
  require_once('../inc/config.php');
  require_once('inc/functions.php');

  if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
  }

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
    ]);

    $sql = 'SELECT p.*, c.category_name FROM posts AS p JOIN categories AS c ON p.category_id = c.id WHERE p.id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, (int)$_GET['id'] , PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = $result['title'];
  require_once('inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <article class="l-section">
        <h2><?php echo h($page_title); ?></h2>

        <?php if( !empty($result['post_image']) ) : ?>
        <figure>
          <img src="upload/<?php echo h($result['post_image']); ?>" alt="<?php echo h($result['title']); ?>">
        </figure>
        <?php endif; ?>
        <ul class="inline-list inline-list-detail">
          <li>公開日： <time datetime="<?php echo h($result['created']); ?>"><?php echo h(date('Y年m月d日', strtotime($result['created']))); ?></time></li>
          <li>カテゴリ： <?php echo h($result['category_name']); ?></li>
        </ul>
        <p>
          <?php echo nl2br(h($result['content']), false); ?>
        </p>

        <p class="u-mt-large"><a href="./">一覧に戻る</a></p>
      </article>
    </div>
  </main>
<?php require_once('inc/footer.php'); ?>

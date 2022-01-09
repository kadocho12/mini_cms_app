<?php
  require_once('../inc/config.php');
  require_once('inc/functions.php');

  $current_category = 0;
  if ( isset($_GET['category']) && !empty($_GET['category']) ) {
    $current_category = $_GET['category'];
  }

  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
    ]);

    $sql = 'SELECT * FROM categories';
    $stmt = $dbh->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( $current_category === 0 ) {
      // 全てのカテゴリ表示時
      $sql = 'SELECT p.*, c.category_name FROM posts AS p JOIN categories AS c ON p.category_id = c.id ORDER BY created DESC';
      $stmt = $dbh->prepare($sql);
    } else {
      // カテゴリが絞り込まれている時
      $sql = 'SELECT p.*, c.category_name FROM posts AS p JOIN categories AS c ON p.category_id = c.id WHERE p.category_id = ? ORDER BY created DESC';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, (int)$current_category , PDO::PARAM_INT);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dbh = null;
  } catch (PDOException $e) {
    echo h($e->getMessage());
    exit();
  }

  $page_title = '新着情報';
  require_once('inc/header.php');
?>
  <main class="l-main">
    <div class="l-wrapper">
      <section class="l-section">
        <h2><?php echo h($page_title); ?></h2>

        <ul class="inline-list inline-list-mini">
          <?php
            $active = '';
            if ( $current_category === 0) {
              $active = ' is-active';
            }
          ?>
          <li><a href="index.php?category=0" class="button button-category<?php echo h($active); ?>">全て</a></li>
          <?php foreach( $categories as $category ) : ?>
          <?php
            $active = '';
            if ($category['id'] == $current_category ) {
              $active = ' is-active';
            }
          ?>
          <li><a href="index.php?category=<?php echo $category['id']; ?>" class="button button-category<?php echo h($active); ?>"><?php echo h($category['category_name']); ?></a></li>
          <?php endforeach; ?>
        </ul>

        <dl class="define-table-news">
          <?php foreach($result as $row) : ?>
          <dt><time datetime="<?php echo h($row['created']); ?>"><?php echo h(date('Y.m.d', strtotime($row['created']))); ?></time> <span class="label-category"><?php echo h($row['category_name']); ?></span></dt>
          <dd>
            <a href="detail.php?id=<?php echo h($row['id']); ?>">
              <?php echo h($row['title']); ?>
            </a>
          </dd>
          <?php endforeach; ?>
        </dl>
      </section>
    </div>
  </main>
<?php require_once('inc/footer.php'); ?>

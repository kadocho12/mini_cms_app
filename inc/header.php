<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo h($page_title); ?> | <?php echo h(SITE_NAME); ?></title>
  <link rel="stylesheet" href="<?php echo h(SITE_URL); ?>/assets/css/common.css?YYYYMMDD">
</head>

<body>
  <header class="l-header">
    <div class="l-wrapper">
      <div class="l-header-title">
        <h1 class="l-header-logo">
          <a href="<?php echo h(SITE_URL); ?>/" class="logo-primary">
            PHP <span class="logo-caption"><?php echo h(SITE_NAME); ?></span>
          </a>
        </h1>
      </div>
      <div class="l-header-nav">
        <nav class="l-global-navi">
          <h2 class="screen-reader-text">サイト内メニュー</h2>
          <?php if (!isset($_SESSION['id'])) : ?>
            <ul>
              <li><a href="<?php echo h(SITE_URL); ?>/admin/">管理画面</a></li>
            </ul>
          <?php else : ?>
            <ul>
              <li><a href="<?php echo h(SITE_URL); ?>/admin/logout.php">ログアウト</a></li>
            </ul>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </header>
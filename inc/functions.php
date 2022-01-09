<?php
// XSS 対策
function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// CSRF対策 トークンの生成
function set_token() {
  if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
  }
}

// CSRF対策 トークンの確認
function check_token() {
  if (empty($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
    echo '不正な投稿（トークンが一致しません。）';
    exit();
  }
}

// ログイン確認
function isLogin() {
  session_start();
  if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
  }
}
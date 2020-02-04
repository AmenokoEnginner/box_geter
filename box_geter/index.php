<?php
require_once(__DIR__ . '/config.php');

try {
  $box = new \MyApp\Box();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

$box->verify();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ボックス開封</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php if ($_COOKIE['points']): ?>
      <div id="bg1" class="bg" style="display: block;">
        <div id="present" class="center" style="font-size: 50px;">
          <p>今日のボックスは<br class="md-br">すでに開封しました。</p>
          <p>現在のポイント: <?php h($box->showPoints()); ?>pt</p>
          <button type="button" class="btn btn-light">
            <a href="index.php" class="text-dark">戻る</a>
          </button>
        </div>
      </div>
    <?php endif; ?>
    <div class="container-fluid center">
      <div id="boxs">
        <div id="box-red" class="box text-danger alert-danger">
          <p>R</p>
        </div>
        <div id="box-green" class="box text-success alert-success">
          <p>G</p>
        </div>
        <div id="box-blue" class="box text-primary alert-primary">
          <p>B</p>
        </div>
      </div>
    </div>
    <div class="container-fluid pt-5" style="display: flex;">
      <h3><?php h($box->showUsername()); ?></h3>
      <button type="button" class="btn btn-primary logout">
        <a href="/index.php?action=logout" class="text-light">ログアウト</a>
      </button>
      <button type="button" class="btn btn-danger text-light delete">
        <a>退会</a>
      </button>
    </div>
    <div id="bg2" class="bg" style="display: none;">
      <div id="present" class="center" style="font-size: 100px;">
        <p><span id="point"></span>pt<br class="md-br">ゲット!</p>
        <button type="button" class="btn btn-light">
          <a href="index.php" class="text-dark">戻る</a>
        </button>
      </div>
    </div>
    <div id="bg3" class="bg" style="display: none;">
      <div id="present" class="center" style="font-size: 100px;">
        <p>本当に退会しますか？</p>
        <button type="button" class="btn btn-light">
          <a href="delete.php" class="text-dark">はい</a>
        </button>
        <button type="button" class="btn btn-light notdelete">
          <a class="text-dark">いいえ</a>
        </button>
      </div>
    </div>
    <input id="token" type="hidden" name="token" value="<?php h($_SESSION['token']); ?>">
    <script src="script.js"></script>
  </body>
</html>

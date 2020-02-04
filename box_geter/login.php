<?php
require_once(__DIR__ . '/config.php');

try {
  $box = new \MyApp\Box();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

$box->verify();
$error = $box->validateName($_POST['name']);
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
    <div class="container-fluid pt-3">
      <form action="" method="post">
        <h2>ログイン</h2>
        <h4>ユーザーネームを入力してください</h4>
        <div><input class="name" type="text" name="name" value="<?php h($_POST['name']); ?>"></div>
        <p class="error">
          <?php error($error); ?>
        </p>
        <div><input class="submit" type="submit" value="認証する"></div>
        <div><a href="register.php">登録</a></div>
      </form>
    </div>
    <script src="script.js"></script>
  </body>
</html>

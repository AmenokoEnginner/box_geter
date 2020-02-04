<?php
namespace MyApp;

class Box {
  private $db;

  public function __construct() {
    $this->connectDB();
    $this->createToken();
  }

  private function connectDB() {
    try {
      $this->db = new \PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      throw new \Exception('データベースの接続に失敗しました');
    }
  }

  private function createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  private function validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }

  public function validateName($name) {
    // リロード時はエラーを出さない
    if (!isset($name)) {
      return false;
    }

    // 名前が入力されていなかったらエラー
    if (empty($name)) {
      return 'blank';
    }

    // 名前が３文字以上でなかったらエラー
    if (mb_strlen($name) < 3) {
      return 'length';
    }

    $sql = 'SELECT * FROM users';
    $users = $this->db->query($sql);

    if ($_SERVER['REQUEST_URI'] == '/register.php') {
      // 同じ名前が見つかったらエラー
      while ($user = $users->fetch()) {
        if ($user['name'] == $name) {
          return 'same';
        }
      }
    }

    if ($_SERVER['REQUEST_URI'] == '/login.php') {
      // 同じ名前が見つからなかったらエラー
      $flag = false;
      while ($user = $users->fetch()) {
        if ($user['name'] == $name) {
          $flag = true;
        }
      }
      if (!$flag) {
        return 'none';
      }
    }

    if ($_SERVER['REQUEST_URI'] == '/register.php') {
      // 登録処理
      $this->register($name);
    } else {
      // ログイン処理
      $this->login($name);
    }
  }

  private function register($name) {
    // usersテーブルにデータを追加
    $sql = 'INSERT INTO users SET name=?';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$name]);

    $sql = 'SELECT * FROM users';
    $users = $this->db->query($sql);

    foreach ($users->fetchAll() as $user) {
      if ($user['name'] == $name) {
        // 登録したユーザーのIDを取得
        $id = $user['id'];

        // pointsテーブルにデータを追加
        $sql = 'INSERT INTO points SET user_id=?, points=0';
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
      }
    }

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
    exit;
  }

  private function login($name) {
    $sql = 'SELECT * FROM users';
    $users = $this->db->query($sql);

    foreach ($users->fetchAll() as $user) {
      // usersテーブルにフォームから入力した名前があるか
      if ($user['name'] == $name) {
        // ログイン情報を保存
        $this->createCookieAndSession($user['id'], $name);

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/index.php');
        exit;
      }
    }

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
    exit;
  }

  public function verify() {
    $this->checkLogout();
    $this->checkLogin();
  }

  private function checkLogin() {
    if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['name'])) {
      // ログイン済みの時の処理
      // ログインを記憶する
      $this->createCookieAndSession($_COOKIE['user_id'], $_COOKIE['name']);

      if ($_SERVER['REQUEST_URI'] != '/index.php') {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/index.php');
        exit;
      }
    } else {
      // 未ログインの時の処理
      if (
        $_SERVER['REQUEST_URI'] != '/register.php' &&
        $_SERVER['REQUEST_URI'] != '/login.php'
      ) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit;
      }
    }
  }

  private function checkLogout() {
    if ($_GET['action'] == 'logout') {
      setcookie('user_id', ' ', time() - 60);
      setcookie('name', ' ', time() - 60);
      unset($_COOKIE);

      $_SESSION = [];
      session_destroy();
    }
  }

  private function createCookieAndSession($id, $name) {
    setcookie('user_id', $id, time() + 60 * 10);
    setcookie('name', $name, time() + 60 * 10);

    $_SESSION['user_id'] = $_COOKIE['user_id'];
  }

  public function showUsername() {
    $sql = 'SELECT * FROM users';
    $users = $this->db->query($sql);

    foreach ($users->fetchAll() as $user) {
      if ($user['id'] == $_SESSION['user_id']) {
        return $user['name'];
      }
    }
  }

  public function showPoints() {
    $sql = 'SELECT * FROM points';
    $points = $this->db->query($sql);

    foreach ($points->fetchAll() as $point) {
      if ($point['user_id'] == $_SESSION['user_id']) {
        return $point['points'];
      }
    }
  }

  // Cookieで取得情報を保存、データ更新
  public function alreadyObtainedTodaysPoint() {
    $this->validateToken();

    setcookie('points', true, time() + 10);

    $sql = 'SELECT * FROM points';
    $points = $this->db->query($sql);

    foreach ($points->fetchAll() as $point) {
      if ($point['user_id'] == $_SESSION['user_id']) {
        $pt = $point['points'] + $_POST['points'];

        $sql = 'UPDATE points SET points=? WHERE user_id=?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pt, $_SESSION['user_id']]);
      }
    }
  }

  public function deleteUserInfo() {
    $user_id = $_SESSION['user_id'];
    $_GET['action'] = 'logout';
    $this->checkLogout();

    $sql = 'DELETE FROM users WHERE id=?';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$user_id]);
  }
}

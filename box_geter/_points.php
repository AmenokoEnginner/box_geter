<?php
require_once(__DIR__ . '/config.php');

try {
  $box = new \MyApp\Box();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

try {
  $box->alreadyObtainedTodaysPoint();
} catch (Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
  echo $e->getMessage();
  exit;
}

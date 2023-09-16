<?php
header('Content-Type: application/json');
if (!file_exists("winner.json")) {
  echo '{"error": "notexist"}';
  exit;
}

$result = [];

if (file_exists("displayed_ids.json")) {
  $json_d = file_get_contents("displayed_ids.json");
  $result = json_decode($json_d, true);
}



$json = file_get_contents("winner.json");
$array = json_decode($json, true);
$latestdate = 0;
$latestdata = [];
foreach ($array as $data) {
  $date = strtotime($data["date"]);
  if ($date > $latestdate) {
    $latestdate = $date;
    $latestdata = $data;
  }
}

if (in_array($latestdata['id'], $result)) {
  echo '{"error": "already_displayed"}';
  exit;
}
$result[] = $latestdata['id'];

file_put_contents("displayed_ids.json", json_encode($result));

echo json_encode($result);
exit;
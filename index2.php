<?php
define("ONE_OFF_MODE", true); // 1人1回限り
define("MULTIPLE_CODE_MODE", true); // 複数のQRコード
define("USABLE_CODES", ["e9ki2h", "b7ac9v", "t6gjwb", "a2mgfv", "k7rqx4", "c7igsn", "m289gi", "p6cws3", "z8ebas", "z3isgk"]);

function get_ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}
?>
<html lang="ja">
  <head>
    <title>文化祭44期 - 金剛インターネット部</title>
    <style>
      body {
        text-align:center;
      }
      a {
        padding-top: 35px;
        padding-bottom: 35px;
        padding-right: 50px;
        padding-left: 50px;
        border: 1px solid #dedede;
        border-radius: 3px;
        background-color: #fafafa;
        position: relative;
      }
      a img {
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -40px 0 0 -40px;
      }
      a.i1 img {
        filter: drop-shadow(0px 0px 10px red);
      }
      a.i2 img {
        filter: drop-shadow(0px 0px 10px blue);
      }
      a.i3 img {
        filter: drop-shadow(0px 0px 10px yellow);
      }
      a.i1:hover {
        border: 1px solid #de000015;
        background-color: #fa000015;
      }
      a.i2:hover {
        border: 1px solid #0000de15;
        background-color: #0000fa15;
      }
      a.i3:hover {
        border: 1px solid #dede0015;
        background-color: #fafa0015;
      }
    </style>
  </head>
  <body>
    <?php
  $already_winner = false;
  $already_id = "";
if (MULTIPLE_CODE_MODE) {
  
}
  if (file_exists("./winner.json") && ONE_OFF_MODE) {
    $arr = json_decode(file_get_contents("./winner.json"), true);
    foreach ($arr as $id => $data) {
      if ($data['ip'] == get_ip_address()) {
        $already_id = $id;
        //$already_winner = true;
        break;
      }
    }
  }
  if (empty($_GET) && !$already_winner) {
    ?>
    <br />
    <br />
    <a class="i1" href="/?do=select&item=1"><img src="./img/takarabako.png" width="80" height="80" /></a>
    &nbsp;&nbsp;&nbsp;
    <a class="i2" href="/?do=select&item=2"><img src="./img/takarabako.png" width="80" height="80" /></a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a class="i3" href="/?do=select&item=3"><img src="./img/takarabako.png" width="80" height="80" /></a>
    <?php
  } else if ($already_winner || $_GET['do'] === "select") {
    $r = rand(1,3);
    if ($already_winner) $r = 3;
    if ($r == 3) {
      $id = '';
      if ($already_winner) {
        $id = $already_id;
      } else {
        $id = strtoupper(substr(md5(rand(10000, 99999) + rand(10000, 99999) + rand(10000, 99999)), 0, 5));
        $arr = array();
        if (file_exists("./winner.json")) {
          $arr = json_decode(file_get_contents("./winner.json"), true);
        }
        $arr[$id] = ["ip" => get_ip_address(), "date" => date('Y-m-d H:i:s')];
        file_put_contents("./winner.json", json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
      }
      echo "<p><span style=\"font-size: 30px;\">Congratulations! Win a lottery!</span><br /><span style=\"font-size: 10px;\">おめでとうございます! 抽選に当選しました！</span><br /><span style=\"font-size: 50px;\">当選IDは<b>" . $id . "</b>です。</span></p><p>この画面をインターネット部員に見せ、第二LAN教室で景品と交換します。</p>";
    }
  }
    ?>
  </body>
</html>
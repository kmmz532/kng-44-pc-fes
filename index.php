<?php
ini_set('display_errors', "On");
define("ONE_OFF_MODE", true); // 1人1回限り
define("OPEN_MODE", "end"); // "open", "close", "end"
define("ENABLE_RUBY", false); // ふりがな

if (OPEN_MODE == "close") {
  echo "10時からインターネット部抽選会を開始するので、もうしばらくお待ちしてから再度QRコードを読み込んでください。";exit;
}
if (OPEN_MODE == "end" && !(isset($_GET['do']) && $_GET['do'] == "try")) {
  echo "申し訳ございませんが、インターネット部抽選会は終了しました。<br />抽選に参加しない上で、Webアプリを試してみたい方は<a href='./?do=try'>こちら</a>からお願いします。";exit;
}

require('func.php');

$can_rotary = true;
$already_id = "";
$already_data = [];
if (file_exists("./winner.json") && ONE_OFF_MODE) {
  $arr = json_decode(file_get_contents("./winner.json"), true);
  foreach ($arr as $id => $data) {
    if ($data['ip'] == get_ip_address()) {
      $already_id = $id;
      $can_rotary = false;
      $already_data = $data;
      break;
    }
  }
}

if (isset($_GET['do'])) {
  $do = $_GET['do'];
  if ($do == "display") {
    include("display.php");
    exit;
  }
  if ($do == "get") {
    $arr = array();
    if (file_exists("./winner.json")) {
      $arr = json_decode(file_get_contents("./winner.json"), true);
    }
    
    header('Content-Type: application/json');

    if (!$can_rotary) {
      echo '{"error":"only_once"}';
      exit;
    }
    
    $data = [];
    
    $id = strtoupper(substr(md5(rand(10000, 99999) + rand(10000, 99999) + rand(10000, 99999)), 0, 5));

    if (array_key_exists($id, $arr)) {
      $id = strtoupper(substr(md5(rand(10000, 99999) + rand(10000, 99999) + rand(10000, 99999)), 0, 5));
      if (array_key_exists($id, $arr)) {
        $id = strtoupper(substr(md5(rand(10000, 99999) + rand(10000, 99999) + rand(10000, 99999)), 0, 5));
      }
    }

    // 1～100の整数を生成する
    $probability = mt_rand(1, 100);
    
    $class = 0;
    // 50%で3等 - 50以上
    if ($probability >= 70) {
      $class = 3;
    // 40%で2等 - 10以上
    } else if ($probability >= 20) {
      $class = 2;
    // 10%で1等 - 0以上
    } else if ($probability >= 0) {
      $class = 1;
    }
    
    $data['id'] = $id;
    $data['class'] = $class;
    echo json_encode($data);
    $data['ip'] = get_ip_address();
    $data['date'] = date('Y-m-d H:i:s');

    $arr[$id] = $data;

    file_put_contents("./winner.json", json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    exit;
  }
}

$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>
<html lang="ja">
  <head>
    <title>文化祭QRコード抽選会 - 金剛インターネット部</title>
    <meta charset="utf-8" />
    <meta name='viewport' content='width=device-width' />
    <link rel="stylesheet" href="./style.css<?php echo '?' . filemtime("./style.css"); ?>" />
     <link rel="SHORTCUT ICON" href="./img/icon.png" />
  </head>
  <body>
    <input type="hidden" id="url" name="url" value="<?= $url ?>" />
    <input type="hidden" id="can_rotary" name="can_rotary" value="<?= $can_rotary ? 'true' : 'false' ?>" />
<?php
if (!$can_rotary) {
  echo '<input type="hidden" id="id" name="id" value="' . $already_id . '" />' . "\n";
  echo '<input type="hidden" id="class" name="class" value="' . $already_data['class'] . '" />' . "\n";
}
?>
    <span id="bg" style="display:none;"></span>
    <div id="warp" style="overflow: hidden;height: 95vh;">
    <div id="root">
    <div id="machine">
      <span style="position:absolute;left:50%;top:50%;transform: translateX(-50%);z-index:2">
        <img id="body" src="./img/lotterymachine/body2.png<?php echo '?' . filemtime("./img/lotterymachine/body2.png"); ?>" />
      </span>
      <img id="stand" src="./img/lotterymachine/stand.png<?php echo '?' . filemtime("./img/lotterymachine/stand.png"); ?>" style="position:absolute;left:50%;top:50%;transform: translateX(-50%);z-index:3" />
      <span style="position:absolute;left:50%;top:75px;transform: translate(-50%, 50%);z-index:4">
        <img id="handle" style="margin-top:1px;margin-left:-2px;" src="./img/lotterymachine/handle.png<?php echo '?' . filemtime("./img/lotterymachine/handle.png"); ?>" />
      </span>
    </div>
    <label for="dialog_win" id="dialog_win_label" class="open_sub_window_win" style="<?= $can_rotary ? "display:none;" : "display:inline;" ?>"><?php echo ENABLE_RUBY ? "<ruby>結果確認<rt>けっかかくにん</rt></ruby>" : "結果確認"; ?></label>

    </div>
    </div>
    <script>
      $handle = document.getElementById('handle');
      $body = document.getElementById('body');
      $url = document.getElementById('url').value;
      $can_rotary = document.getElementById('can_rotary').value == "true" ? true : false;
      $id_ele = document.getElementById('id');
      if ($id_ele != null) $id = $id_ele.value;
      $class_ele = document.getElementById('class');
      if ($class_ele != null) $class = parseInt($class_ele.value);
    </script>
    <script src="./script.js<?php echo '?' . filemtime("./script.js"); ?>"></script>

	<input type="checkbox" id="dialog_win" class="check_sub_window">
	<label for="dialog_win" class="bg_sub_window">
	  <label for="dummy_sub_window" class="sub_window gaming">
	    <label for="dialog_win" class="close_sub_window"></label>
	      <div class="sub_window_content">
	        <h3><?php echo ENABLE_RUBY ? "<ruby>結果<rt>けっか</rt></ruby>" : "結果"; ?></h3>
          <span style=\"font-size: 30px;\">Congratulations! Win a lottery!</span><br /><span style=\"font-size: 10px;\">おめでとうございます! <?php echo ENABLE_RUBY ? "<ruby>抽選<rt>ちゅうせん</rt></ruby>" : "抽選"; ?>に<?php echo ENABLE_RUBY ? "<ruby>当選<rt>とうせん</rt></ruby>" : "当選"; ?>しました！</span><br /><span style=\"font-size: 50px;\">
          <p><?php echo ENABLE_RUBY ? "<ruby>当選<rt>とうせん</rt></ruby>" : "当選"; ?>ID: <span id="dw_id"><?= $already_id ?></span><br />
          <?php echo ENABLE_RUBY ? "<ruby>球<rt>きゅう</rt></ruby>" : "球"; ?>カラー: <span id="dw_color"><?php if (isset($already_data['class'])) echo ballNameFromNum($already_data['class']) ?></span> (<span id="dw_class"><?php if (isset($already_data['class'])) echo $already_data['class'] ?></span><?php echo ENABLE_RUBY ? "<ruby>等<rt>とう</rt></ruby>" : "等"; ?>)</p>
          <br />
          <p>この<?php echo ENABLE_RUBY ? "<ruby>画面<rt>がめん</rt></ruby>" : "画面"; ?>を3A<?php echo ENABLE_RUBY ? "<ruby>教室内<rt>きょうしつない</rt></ruby>" : "教室内"; ?>でインターネット<?php echo ENABLE_RUBY ? "<ruby>部員<rt>ぶいん</rt></ruby>" : "部員"; ?>に<?php echo ENABLE_RUBY ? "<ruby>見<rt>み</rt></ruby>" : "見"?>せ、チェック<?php echo ENABLE_RUBY ? "<ruby>後<rt>ご</rt></ruby>" : "後"?>に<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>と<?php echo ENABLE_RUBY ? "<ruby>交換<rt>こうかん</rt></ruby>" : "交換"?>します。<br /><br />その<?php echo ENABLE_RUBY ? "<ruby>後<rt>ご</rt></ruby>" : "後"?>、<?php echo ENABLE_RUBY ? "<ruby>当選<rt>とうせん</rt></ruby>" : "当選"; ?>した<?php echo ENABLE_RUBY ? "<ruby>等級<rt>とうきゅう</rt></ruby>" : "等級"; ?>の<?php echo ENABLE_RUBY ? "<ruby>箱/机<rt>はこ/つくえ</rt></ruby>" : "箱/机"; ?>から<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>を1つ<?php echo ENABLE_RUBY ? "<ruby>選<rt>えら</rt></ruby>" : "選"; ?>んでください。(2等は2つ)<br />1等の<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>が<?php echo ENABLE_RUBY ? "<ruby>不足<rt>ふそく</rt></ruby>" : "不足"; ?>している<?php echo ENABLE_RUBY ? "<ruby>場合<rt>ばあい</rt></ruby>" : "場合"; ?>は2<?php echo ENABLE_RUBY ? "<ruby>等<rt>とう</rt></ruby>" : "等"; ?>の<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>2つか3<?php echo ENABLE_RUBY ? "<ruby>等<rt>とう</rt></ruby>" : "等"; ?>の<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>8<?php echo ENABLE_RUBY ? "<ruby>個<rt>こ</rt></ruby>" : "個"; ?>、もしくは2<?php echo ENABLE_RUBY ? "<ruby>等<rt>とう</rt></ruby>" : "等"; ?>の<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>が<?php echo ENABLE_RUBY ? "<ruby>不足<rt>ふそく</rt></ruby>" : "不足"; ?>している<?php echo ENABLE_RUBY ? "<ruby>場合<rt>ばあい</rt></ruby>" : "場合"; ?>は3<?php echo ENABLE_RUBY ? "<ruby>等<rt>とう</rt></ruby>" : "等"; ?>の<?php echo ENABLE_RUBY ? "<ruby>景品<rt>けいひん</rt></ruby>" : "景品"; ?>4<?php echo ENABLE_RUBY ? "<ruby>個<rt>こ</rt></ruby>" : "個"; ?>となります。</p>
	      </div>
	    </label>
  	</label>
  </body>
</html>
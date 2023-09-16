<?php
ini_set('display_errors', "On");
?>
<html lang="ja">
  <head>
    <title>文化祭QRコード抽選会 - 金剛インターネット部</title>
    <meta charset="utf-8" />
    <meta name='viewport' content='width=device-width' />
    <link rel="SHORTCUT ICON" href="./img/icon.png" />
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/selfie_segmentation.js" crossorigin="anonymous"></script>
    <script src="./photo.js" async></script>
  </head>
  <body>
    <div class="container">
      <video class="input_video" style="display:inline;"></video>
      <canvas class="output_canvas" width="1280px" height="720px"></canvas>
    </div>
  </body>
</html>
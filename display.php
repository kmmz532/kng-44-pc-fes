<html lang="ja">
  <head>
    <title>文化祭QRコード抽選会 - 金剛インターネット部</title>
    <meta charset="utf-8" />
    <meta name='viewport' content='width=device-width' />
    <link rel="stylesheet" href="./style.css<?php echo '?' . filemtime("./style.css"); ?>" />
    <link rel="SHORTCUT ICON" href="./img/icon.png" />
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script>
let bell_sound = new Audio('./sound/bell.mp3');
function read() {
    $.ajax({
        type: 'get',
        url: './getlatestwinner.php'
        })
    .then(
        function ($data) {
          if ($data['id'] !== undefined) {
            $bell = document.getElementById("bell");
            $bell.style.display = "inline";
            $bell.animate(
              {
    	 	        transform: ['rotate(-30deg)', 'rotate(30deg)'],
              },
	            {
          		  duration: 500,
	 	            iterations: 6,
	 	            direction: 'alternate',
	            }
            );

  let $machine = document.getElementById('machine');
  let $ball = document.createElement("img");
  
  switch ($data['class']) {
    case 1:
      $ball.src = "./img/lotterymachine/golden_ball.png";
      break;
    case 2:
      $ball.src = "./img/lotterymachine/red_ball.png";
      break;
    case 3:
      $ball.src = "./img/lotterymachine/white_ball.png";
      break;
    default:
      $ball.src = "./img/lotterymachine/white_ball.png";
      break;
  }
  $ball.style = "position:absolute;left:50%;top:50%;margin-top:500px;transform:translateX(-50%) scale(3);z-index:1;";
  $machine.append($ball);


            $ball.animate(
              {
    	 	        opacity: ['0', '1'],
              },
	            {
          		  duration: 250,
	 	            iterations: 1,
                fill: "forwards"
	            }
            );
          window.setTimeout(function () {

            $ball.animate(
              {
    	 	        opacity: ['1', '0'],
              },
	            {
          		  duration: 250,
	 	            iterations: 1,
                fill: "forwards"
	            }
            );
            
          }, 3000);



            $bell.animate(
              {
    	 	        opacity: ['0', '1'],
              },
	            {
          		  duration: 250,
	 	            iterations: 1,
                fill: "forwards"
	            }
            );
          window.setTimeout(function () {

            $bell.animate(
              {
    	 	        opacity: ['1', '0'],
              },
	            {
          		  duration: 250,
	 	            iterations: 1,
                fill: "forwards"
	            }
            );
            
          }, 3000);
            
          window.setTimeout(function () {
            $bell.style.display = "none";
            $ball.remove();
          }, 4000);

         
            bell_sound.play();
          }
        },
        function () {
            alert("Failed reading");
        }
    );
}

$(document).ready(function() {
    read();
    setInterval('read()', 1000);
});
</script>
  </head>
  <body>
    <span id="bg"></span>
    <div id="warp" style="height: 100vh;">
      <div id="root">
        <div id="machine">
          <span style="position:absolute;left:50%;top:50%;margin-top: -50px;transform: translateX(-50%);z-index:2">
            <img style="display:none;" id="bell" src="./img/lotterymachine/bell.png<?php echo '?' . filemtime("./img/lotterymachine/bell.png"); ?>" />
          </span>
        </div>
      </div>
    </div>
  </body>
</html>
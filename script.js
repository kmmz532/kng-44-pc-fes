var $handle;
var $body;
var $url;
var $id;
var $class;
var $can_rotary;

var $rotarytime = 0;

var cr = $body.getBoundingClientRect();

var $deg = 0
var $rotated = false;
const canTouch = window.ontouchstart !== undefined && 0 < navigator.maxTouchPoints;

let ball_sound = new Audio('./sound/ballout.mp3');
var rotation_sound = new Audio('./sound/rotation.mp3');

var $audioLoaded = false;

const ctrlev = {
  down: canTouch ? 'touchstart' : 'mousedown',
  move: canTouch ? 'touchmove'  : 'mousemove',
  up  : canTouch ? 'touchend'   : 'mouseup',
};

if (canTouch) {
  document.getElementsByTagName("body")[0].lastChild.addEventListener('touchmove', e => e.preventDefault());
}

if (!$can_rotary) {
  $rotated = true;
  document.getElementById('bg').style.display = 'inline';

  let $machine = document.getElementById('machine');
  let $ball = document.createElement("img");
  
  switch ($class) {
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
  $ball.style = "position:absolute;left:-320px;top:640px;transform:translateX(-50%);z-index:1;";
  $machine.append($ball);
}

$handle.addEventListener(ctrlev.down, onMousedown);
function onMousedown(_e) {
  if (!$audioLoaded) {
    rotation_sound.load();
    ball_sound.load();
    $audioLoaded = true;
  }
    
  if ($rotated) return;
  let e = canTouch ? _e.touches[0] : _e;

  
  var cx = window.pageXOffset + cr.left + $body.offsetWidth / 2 - (canTouch ? 200 : 0);
  var cy = window.pageYOffset + cr.top + $body.offsetHeight / 2 - (canTouch ? 200 : 0);
  
  var $clickDeg = Math.atan2(e.pageX - cx, cy - e.pageY) * 180 / Math.PI;
  var $startDeg = $deg;
  
  function moveAt(pageX, pageY) {
    $deg = (Math.atan2(pageX - cx, cy - pageY) * 180 / Math.PI
      + $startDeg - $clickDeg + 360) % 360;
    $handle.style.transform = "rotate(" + $deg + "deg)";
    $body.style.transform = "rotate(" + $deg + "deg)";
    if (rotation_sound.paused)
      rotation_sound.play();

    let $absDeg = Math.abs($startDeg - $deg) / 3;
    if (canTouch && 355 + $absDeg >= $deg && $deg >= 350 - $absDeg) {
      ++$rotarytime;
    }
    
    if (355 >= $deg && $deg >= 335 && (!canTouch || $rotarytime > 1) && (Math.floor(Math.random() * 10) == 1 || $rotarytime >= 3)) {
      
      $rotated = true;
      document.removeEventListener(ctrlev.move, onMouseMove); 
      document.removeEventListener(ctrlev.up, onMouseUp);
      $deg = -18;
      document.getElementById('bg').style.display = 'inline';

      let $machine = document.getElementById('machine');
      let $ball = document.createElement("img");

      let xhr = new XMLHttpRequest();
      xhr.open('get', '?do=get');
      xhr.send();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          $res = this.responseText;
          let $data = JSON.parse($res);

          $ballcolor = '';
          switch ($data['class']) {
            case 1:
              $ball.src = "./img/lotterymachine/golden_ball.png";
              $ballcolor = '<b style="color:yellow">金</b>';
              break;
            case 2:
              $ball.src = "./img/lotterymachine/red_ball.png";
              $ballcolor = '<b style="color:red">赤</b>';
              break;
            case 3:
              $ball.src = "./img/lotterymachine/white_ball.png";
              $ballcolor = '<b>白</b>';
              break;
            default:
              $ball.src = "./img/lotterymachine/white_ball.png";
              $ballcolor = '<b>白</b>';
              break;
          }
          $ball.style = "position:absolute;left:32%;top:520px;transform:translateX(-50%);z-index:1;";
          $machine.append($ball);
          $ball.animate(
            {
    	 	      top: ['520px', '640px'],
      	      left: ['-230px', '-320px']
            },
	          {
        		  duration: 500,
		          iterations: 1,
              fill: "forwards"
	          }
          );

          document.getElementById('dw_id').innerHTML = $data['id'];
          document.getElementById('dw_color').innerHTML = $ballcolor;
          document.getElementById('dw_class').innerHTML = $data['class'];
          
          rotation_sound.pause();
          ball_sound.play();

          window.setTimeout(function () {
            document.getElementById('dialog_win').checked = true;
            document.getElementById('dialog_win_label').style.display = "inline";
          }, 1000);
        }
      }
    }
  }

  function onMouseMove(_e) {
    let e = canTouch ? _e.touches[0] : _e;
    let x = e.pageX;
    let y = e.pageY;
    
    moveAt(x, y);
  }

  function onMouseUp() {
    document.removeEventListener(ctrlev.move, onMouseMove); 
    document.removeEventListener(ctrlev.up, onMouseUp);  
  }

  document.addEventListener(ctrlev.move, onMouseMove);
  document.addEventListener(ctrlev.up, onMouseUp);
}

$handle.ondragstart = function() {
  return false;
};
<? # pic/index.php - show some dang pics
   # 2 dir levels - year, picset, then buncha .jpgs
   # go script makes idx/yyyy/picset/picset.txt indexes with
   # P/L|filename|comment      ( row 0 has extra |picset comment )
   # and thumbnails in year/picset dir with same filename as main pic

require_once ("../_inc/app.php");

#dump('index',$_REQUEST);
   $y = arg ('y', '');                 # year pos arg
   $s = arg ('s', '');                 # picset pos arg
#dbg("y=$y s=$s");

   $Year = LstDir ("pic",       'd');   sort ($Year);
## if no y arg use last year we got
   if ($y == '')    $yPos = count ($Year)-1;
   else             $yPos = $y;
   $yStr = $Year [$yPos];

   $PSet = LstDir ("pic/$yStr", 'd');   sort ($PSet);
## got year but no picset, use 1st ps of year else 1st
   if ($s == '') {
      if ($y == '') $sPos = count ($PSet)-1;
      else          $sPos = 0;
   }
   else             $sPos = $s;
   $sStr = $PSet [$sPos];
#dump("yPos=$yPos yStr=$yStr Year:", $Year);
#dump("sPos=$sPos sStr=$sStr PSet:", $PSet);

   $Pic = explode ("\n", Get ("idx/$yStr/$sStr.txt"));
   array_pop ($Pic);                   # kill last empty row from last \n
#dump("pic:", $Pic);                   # cuz list of line\n leaves extra \n

## page comment
   $r0 = explode ('|', $Pic [0]);   $pCom = $r0 [3];

   pg_head ("pic", "jqui app", "jqui app");
?>
 <meta property="og:type"  content="website">
 <meta property="og:url"   content="https://shaz.app/pic/index.php<?=
                                                               "?y=$y&s=$s" ?>">
 <meta property="og:title" content="Stevez pics <?= "$yStr $sStr $pCom" ?>">
 <meta property="og:image" content="https://shaz.app/pic/idx/<?=
                                   "$yStr/$sStr/".explode ('|',$Pic[0])[1] ?>">
 <style>
.comment {
   max-width: 640px;
   font-size: 18pt;
   color:            #003050;
   background-color: #00F0FF;
   padding: 5px;
   border:  solid 3px #0F7391;
}
.thumb {
   float:    left;
   position: relative;
}
.thumbtxt {
   position:   absolute;
   top:        0;
   left:       0;
   font-size:  18pt;
   color:      white;
   background: rgb(0,0,0);
   background: rgba(0,0,0,.5);
   text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
/* color:            #003050;
   background-color: #00F0FF; */
}
#big {
/* position:            relative; */
   display:             flex;
   justify-content:     center;
   align-items:         center;
   background-size:     contain;
   background-position: center;
   background-repeat:   no-repeat;
}
#bigtxt {
   position:   absolute;
   z-index:    1;
   top:        0;
   left:       0;
   color:      white;
   background: rgb(0,0,0);
   background: rgba(0,0,0,.5);
   text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
}
 </style>
 <script>
const path = "<?= "pic/$yStr/$sStr/" ?>";   // path for full pics
const pic  =  <?= json_encode ($Pic) ?>;    // pic[][0..2] = L/P|filenm|comment

function reArg (s)
{ let a = "index.php?y=" + $('#year').prop ('selectedIndex');
   if (s > -1)  a += ("&s=" + s);
   location.href = a;
}

function reYear ()  { reArg (-1); }
function rePSet ()  { let s = $('#pset').prop ('selectedIndex');   reArg (s);  }
function prevSet () { let s = $('#pset').prop ('selectedIndex');   reArg (s-1);}
function nextSet () { let s = $('#pset').prop ('selectedIndex');   reArg (s+1);}

function full (fn = '')
// toggle fullscreen on/off
{ const it = document.querySelector ('#big');
   if (fn != '')  it.style.backgroundImage = fn;

   if            (it.requestFullScreen)       it.requestFullscreen ();
   else if (it.webkitRequestFullscreen) it.webkitRequestFullscreen ();
   else if     (it.msRequestFullscreen)     it.msRequestFullscreen ();

   if            (document.fullscreenElement)       document.exitFullscreen ();
   else if (document.webkitFullscreenElement) document.webkitExitFullscreen ();
   else if     (document.msFullscreenElement)     document.msExitFullscreen ();
}

function un ()  {$("#full").html ('');}

function big (p)
{ let h = '';
  let a = pic [p].split ('|');
  let fn = a[1];
  let cm = a[2];
  let or = screen.orientation.type.substr (0,4);
  let st = (or == 'land') ? 'height:94vh; ' : 'width:100vw; ';
   st += 'background-image: url(' + path + fn + ');'
   h += "<center>\n" +
        "<div id='big' onclick='full(); un();' style='" + st + "'>\n" +
        " <p  id='bigtxt'>"+cm+"</p>\n" +
        "</div>\n" +
        "</center>\n";
   $("#full").html (h);
   full (path + fn);
}

$(function () {
   navInit ();
   $('a').button ();
   $('#year').selectmenu ({ change: reYear, width: 140});
   $('#pset').selectmenu ({ change: rePSet, width: 320});
   $('#prevset').button ().click (prevSet);
   $('#nextset').button ().click (nextSet);
});
 </script>
<? pg_body (); ?>

<span>
<? select ('year', $Year, $yStr);
   select ('pset', $PSet, $sStr);
   if ($sPos > 0) { ?>
 <button id='prevset' title='previous set of pics'>PrevSet</button>
<? }
   if ($sPos+1 < count ($PSet)) { ?>
 <button id='nextset' title='next set of pics'>NextSet</button>
<? } ?>
 &nbsp; &nbsp;
<? if ($pCom != '')  echo "<span class='comment'>$pCom</span>\n"; ?>
</span>
<center>
<? foreach ($Pic as $i => $p) {
      $a = explode ('|', $p);   $fn = $a [1];   $cm = $a [2];
      echo
" <div onclick='big($i);' class='thumb'>\n" .
"  <img src='idx/$yStr/$sStr/$fn'>\n" .
"  <br><span class='thumbtxt'>$cm</span>\n" .
" </div><div class='thumb'>&nbsp;</div>\n";
   }
?>
 <span id='full'></span>
</center>

<? pg_foot ();

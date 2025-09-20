<? # pic/index.php - show some dang pics

require_once ("../_inc/app.php");

#dump('index',$_REQUEST);
   $f = arg ('f', '');
   $s = arg ('s', '');
#dbg("f=$f s=$s");

   $Fold = LstDir ("pic", 'd');   sort ($Fold);
   if ($f == '')  $fPos = count ($Fold)-1;
   else           $fPos = $f;
   $fStr = $Fold [$fPos];
#dump("fPos=$fPos fStr=$fStr Fold:", $Fold);

   $PSet = LstDir ("pic/$fStr", 'd');   sort ($PSet);
   if ($s == '') {
      if ($f=='') $sPos = count ($PSet)-1;
      else        $sPos = 0;
   }
   else           $sPos = $s;
   $sStr = $PSet [$sPos];
#dump("sPos=$sPos sStr=$sStr PSet:", $PSet);

   $Pic = explode ("\n", Get ("idx/$fStr/$sStr.txt"));
#dump("pic:", $Pic);

   pg_head ("pic", "jqui app", "jqui app");
?>
 <meta property="og:type"  content="website">
 <meta property="og:url"   content="https://shaz.app/pic/index.php<?=
                                   "?f=$f&s=$s" ?>">
 <meta property="og:title" content="Stevez pics <?= $fStr.' '.$sStr ?>">
 <meta property="og:image" content="https://shaz.app/pic/idx/<?=
                                   "$fStr/$sStr/".explode ('|',$Pic[0])[1] ?>">
 <style>
.comment {
   max-width: 640px;
   font-size: 22pt;
   color:            #003050;
   background-color: #00F0FF;
   padding: 10px;
   border:  solid 3px #0F7391;
}
 </style>
 <script>
const path = "<?= "pic/$fStr/$sStr/" ?>";
const pThm = "<?= "idx/$fStr/$sStr/" ?>";
const pic  = <?= json_encode ($Pic) ?>;
const parr = [];
const pLen = pic.length;
let   pPos = pLen;

function reArg (s)
{ let f = $('#fold').prop ('selectedIndex')
  let a = "index.php?f="+f
   if (s > -1)  a += ("&s=" + s)
   location.href = a
}

function reFold ()  { reArg (-1); }
function rePSet ()  { let s = $('#pset').prop ('selectedIndex');   reArg (s);  }
function prevSet () { let s = $('#pset').prop ('selectedIndex');   reArg (s-1);}
function nextSet () { let s = $('#pset').prop ('selectedIndex');   reArg (s+1);}

function redo ()
// thumbnails
{ let h = ''
   $('#info').html ('')
   h += "<center>";
// if (pic [0] != '')
//    h += "<div class='comment'>" +  + "</div>";
   for (let i = 0;  i < pLen;  i++) {
     let a = pic [i].split('|');
//   let pl = a[0];
     let fn = a[1];
     let cm = a[2];                 // thumbnail path here
     let t = (cm != '') ? ('title="' + cm + '" ') : '';
      h += "<img class='pic' onclick='big("+i+");' " + t +
                'src="' + pThm + fn + '"' + ">\n";
   }
   h += "</center>";
   $('#redo').html (h);
}

function full ()
{
   document.querySelector ('#big').requestFullscreen ();
   if (document.fullscreenElement)  document.exitFullscreen ();
}

function un ()  {$("#info").html ('');}

function big (p)
{  for (let i = 0;  i < pLen;  i++) {
   // preload full pics
     let a = pic [i].split('|');
     let fn = a[1];
      parr [i] = new Image ();
      parr [i].src = path+fn;
   }
  let h = '';
  let a = pic [p].split ('|');
  let fn = a[1];
  let cm = a[2];
  let or = screen.orientation.type.substr (0,4);
  let st = (or == 'land') ? 'height:94vh' : 'width:100vw';
   h +=    "<center>";
   h +=    "<img id='big' onclick='full(); un();' style='" + st + "' " +
                'src="' + path + fn + '"' + ">\n" +
           "</center>\n";
   $("#info").html (h);
   full ();
}

$(function () {
   navInit ();
   $('a').button ();
   $('#fold').selectmenu ({ change: reFold, width: 140});
   $('#pset').selectmenu ({ change: rePSet, width: 320});
   $('#prevset').button ().click (prevSet);
   $('#nextset').button ().click (nextSet);
   redo ();
});
 </script>
<? pg_body (); ?>

 <span>
<? select ('fold', $Fold, $fStr);
   select ('pset', $PSet, $sStr);
   if ($sPos > 0) { ?>
  <button id='prevset' title='previous set of pics'>PrevSet</button>
<? }
   if ($sPos+1 < count ($PSet)) { ?>
  <button id='nextset' title='next set of pics'>NextSet</button>
<? } ?>
  &nbsp; &nbsp;
  <span id='info' style='font-size:16pt'></span>
 </span>
 <div id='redo'></div>

<? pg_foot ();

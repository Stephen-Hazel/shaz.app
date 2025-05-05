<? # pic/index.php - show some dang pics

require_once ("../_inc/app.php");

dump('index',$_REQUEST);
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

   $PG = "pic";
   pg_head ("jqui app", "jqui app");
?>
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
function nextSet () { let s = $('#pset').prop ('selectedIndex');   reArg (s+1);}

function big (p)  {pPos = p;   redo ();}

function redo ()
{ let h = ''
//dbg(document.fullscreenElement);
   if (pPos == pLen) {                 // thumbnails
      $('#info').html ('')
      h += "<center>";
//    if (pic [0] != '')
//       h += "<div class='comment'>" +  + "</div>";
      for (let i = 0;  i < pLen;  i++) {
        let a = pic [i].split('|');
        let pl = a[0];
        let fn = a[1];
        let cm = a[2];                 // thumbnail path here
        let t = (cm != '') ? ('title="' + cm + '" ') : '';
         h += "<img onclick='big("+i+");' " + t +
                   'src="' + pThm + fn + '"' + ">\n";
      }
      h += "</center>";
   }
   else {                              // full image
     let a = pic [pPos].split ('|');
     let pl = a[0];
     let fn = a[1];
     let cm = a[2];
     let or = screen.orientation.type.substr (0,4);
     let st = (or == 'land') ? 'height:94vh' : 'width:100vw';
      $('#info').html ((pPos+1) + ' of ' + pLen)
      h +=    "<center>";
      if (cm != '')  h += "<div class='comment'>" + cm + "</div>";
      h +=    "<img id='big' onclick='next();' style='" + st + "' " +
                   'src="' + path + fn + '"' + ">\n" +
              "</center>\n";
   }
   $('#redo').html (h);
}

function grid ()  {pPos = pLen;   redo ();}

function back ()  {pPos = (pPos == 0) ? pLen-1 : pPos-1;   redo ();}
function next ()  {pPos = (pPos == pLen-1) ? 0 : pPos+1;   redo ();}

function full ()  {document.querySelector ('#big').requestFullscreen ();}
// if (document.fullscreenElement)  document.exitFullscreen ();


$(function () {
   navInit ();
   $('a').button ();
   $('#fold').selectmenu ({ change: reFold, width: 140});
   $('#pset').selectmenu ({ change: rePSet, width: 320});
   $('#nextset').button ().click (nextSet);
   $('#grid').button ().click (grid);
   $('#back').button ().click (back);
   $('#next').button ().click (next);
   $('#full').button ().click (full);
   redo ();
});
 </script>
<? pg_body (); ?>

 <span>
<? select ('fold', $Fold, $fStr); ?>
<? select ('pset', $PSet, $sStr);
  if ($sPos+1 < count ($PSet)) { ?>
  <button id='nextset' title='next set of pics'>NextSet</button> &nbsp; &nbsp;
<? } ?>
  <button id='grid' title='thumbnails'>Grid</button> &nbsp; &nbsp;
  <button id='back' title='prev pic'  >&lt;</button>
  <button id='next' title='next pic'  >&gt;</button>
  <button id='full'>FullScreen</button>
  <span id='info' style='font-size:16pt'></span>
 </span>
 <div id='redo'></div>

<? pg_foot ();

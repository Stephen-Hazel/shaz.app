<? # song/index.php - play ma songs
   # to do - preserve did; chromecast audio; hey google skip; preserve volume

require_once ("../_inc/app.php");

   $shuf = arg ('shuf','N');   $pick = explode (',', arg ('pick'));

// doin a move too?
   $fr = arg ('fr');   $to = arg ('to');
   if (($fr != '') && ($to != ''))  system ("mv song/$fr song/$to");

   $dir = LstDir ("song", 'd');   sort ($dir);
   $did = explode ("\n", Get ("did.txt"));

   $pl = [];
   foreach ($dir as $i => $d)  if (in_array ($i, $pick)) {
      $mp3 = LstDir ("song/$d", 'f');
      foreach ($mp3 as $fn)  if (! in_array ($fn, $did))  $pl[] = "$d/$fn";
   }
   if ($shuf == 'Y') shuffle ($pl);   else sort ($pl);

   $PG = "song";
   pg_head ("jqui app", "jqui app");
?>
 <style>
audio { vertical-align: bottom; }
table { max-width: 100%; border-collapse: collapse; }
tbody { display: block; height: 20em; overflow-y: scroll; }
td    { overflow: hidden; white-space: nowrap; max-width: 680px; }
 </style>
 <script> // ___________________________________________________________________
const pl = <?= json_encode ($pl); ?>;  // play list
let   tr = 0, au;                      // track we're on, audio element

function redo (x = '')                 // get which dirs are picked n refresh
{ let pick = [];
   all ("[id^='chk']:checked").forEach (chk => {
      pick.push (chk.id.substr (3));
   });
   window.location =
      "index.php?shuf=" + ($('#shuf').prop ('checked') ? 'Y':'N') +
               "&pick=" + pick.join (',') + x;
}

function chk ()  {redo ();}

function shush ()  {au.pause ();   el ('info'+tr).style = '';}

function play (go = 'y')
{  if (tr < 0)  tr = 0;
   if (tr >= pl.length)  return;

  const fn = pl [tr];
   au.src = 'song/' + fn;

   el ('info'+tr).style = "background-color:#FFFF80;";
   if (go == 'y')  au.play ();
}

function prev ()  {shush ();   --tr;   play ();}
function next ()  {shush ();   ++tr;   play ();}

function lyr ()                        // hit google lookin fo lyrics
{ let s = pl [tr];
   s = s.substr (s.indexOf ('/')+1);   // toss leading dir and .mp3
   s = s.substr (s, s.length-4);       // and my dumb _rhap
   if (s.substr (s.length-5, 5) == "_rhap")  s = s.substr (0, s.length-5);
   window.open ("https://google.com/search?q=lyrics " + s, "_blank");
}

function move ()                       // move song to new dir n refresh
{ const f = pl [tr],
        t = $('#to').val ();
   redo ('&fr=' + f + '&to=' + t);
}

$(function () {                        // boot da page
   navInit ()
   $('a').button ();

   au = el ('audio');
   au.volume = 0.2;

   $('input').checkboxradio ().click (chk);
   $('#prev').button ().click (prev);
   $('#next').button ().click (next);

   $('#lyr' ).button ().click (lyr );
   $('#move').button ().click (move);
   $('#to'  ).selectmenu ({ width: 100 });
   all ('table tbody tr').forEach (tr => {
      tr.addEventListener ('click', () => {
         shush ();
         tr = this.rowIndex-1;
         play ();
      });
   });
   au.addEventListener ('ended', () => {
      shush ();
      $.get ("did.php", { did: pl [tr] });
      pl.splice (tr, 1);               // outa theeere
      play ();
   });
   play ('n');
});
 </script>
<? pg_body ();
   check ('shuf', 'shuf', $shuf);   echo " &nbsp; &nbsp; \n";
   foreach ($dir as $i => $s)  check ("chk$i", $s, in_array ($i, $pick)?'Y':'');
?>
<br>
<audio id="audio" controls></audio><a id='prev'>&lt;</a> <a id='next'>&gt;</a>
<a id='move'>move to:</a> <? select ('to',$dir); ?>
<a id='lyr'>lyrics</a><br>

<? table1 ('info', count ($pl)." songs", $pl); ?>
<? pg_foot ();

<? # song/index.php - play ma songs

require_once ("../_inc/app.php");

   $shuf = arg ('shuf','Y');   $pick = explode (',', arg ('pick'));

// doin a scoot?
   if (($sc = arg ('sc')) != '')  rename ("song/$sc", "song/_z");

// build dir[] from song dirs minus _z
   $dir = [];
   foreach (LstDir ("song", 'd') as $d)  if ($d != '_z')  $dir[] = $d;
   sort ($dir);

// build pl[] given picked dirs minus did[] (if shuffle)
   $pl = [];
   $did = ($shuf == 'N') ? [] : explode ("\n", Get ("did.txt"));
   foreach ($dir as $i => $d)  if (in_array ($i, $pick)) {
      $mp3 = LstDir ("song/$d", 'f');
      foreach ($mp3 as $fn)  if (! in_array ("$d/$fn", $did))  $pl[] = "$d/$fn";
   }
   if ($shuf == 'Y') shuffle ($pl);   else sort ($pl);

   pg_head ("song", "jqui app", "jqui app");
?>
 <style>
audio { vertical-align: middle; }
table { max-width: 100%; border-collapse: collapse; }
tbody { display: block; height: 20em; overflow-y: scroll; }
td    { overflow: hidden; white-space: nowrap; max-width: 320px; }
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

function hush (rmv = 'n')
{  au.pause ();
   if (rmv != 'r')  $('#info tbody tr').eq (tr).css ("background-color", "");
   else {                              // ^ unhilite
      pl.splice (tr, 1);               // < outa theeere
      $('#info tbody tr').eq (tr).remove ();
   }
}

function play (go = 'y')
{  if (tr >= pl.length)  return;
   $('#info tbody tr').eq (tr).css ("background-color", "#FFFF80;");
   au.src = 'song/' + pl [tr];
   if (go == 'y')  au.play ();
}

function prev ()
{  hush ();   tr = (tr == 0) ? (pl.length-1) : (tr-1);   play ();  }

function next ()
{  hush ();   tr = (tr < pl.length-1) ? 0    : (tr+1);   play ();  }

function lyr ()                        // hit google lookin fo lyrics
{ let s = pl [tr];
   s = s.substr (s.indexOf ('/')+1);   // toss leading dir and .mp3
   s = s.substr (s, s.length-4);       // and my dumb _rhap
   if (s.substr (s.length-5, 5) == "_rhap")  s = s.substr (0, s.length-5);
   s = s.replace (/_/g, " ");
   window.open ("https://google.com/search?q=lyrics " + s, "_blank");
}

function scoot ()  { redo ('&sc=' + pl [tr]); }

$(function () {                        // boot da page
   navInit ();   $('a').button ();

   au = el ('audio');
// au.volume = 0.2;

   $('input' ).checkboxradio ().click (chk);
   $('#prev' ).button ().click (prev);
   $('#next' ).button ().click (next);
   $('#scoot').button ().click (scoot);
   $('#lyr'  ).button ().click (lyr);
   $('#info tbody').on ('click', 'tr', function () {
      hush ();   tr = $(this).index ();   play ();
   });
   au.addEventListener ('ended', () => {
      $.get ("did.php", { did: pl [tr] });   hush ('r');   play ();
   });
   play ('n');  // setup audio but can't aaactually play till click
});
 </script>
<? pg_body ();
   check ('shuf', 'shuf', $shuf);
   foreach ($dir as $i => $s)  check ("chk$i", $s, in_array ($i, $pick)?'Y':'');
?>
<br>
<audio id="audio" controls></audio>
<a id='prev'>&lt;</a><a id='next'>&gt;</a>
<a id='scoot'>scoot</a><a id='lyr'>lyric</a>

<? table1 ('info', count ($pl)." songs", $pl); ?>
<? pg_foot ();

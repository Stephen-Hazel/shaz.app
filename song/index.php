<? # song/index.php - play ma songs

require_once ("../_inc/app.php");

   $shuf = arg ('shuf','Y');
   $pick = [];
   foreach (explode (',', arg ('pick')) as $p)  if ($p != '')  $pick[] = $p;
dump("shuf=$shuf", $pick);

// doin a scoot?
   if (($sc = arg ('sc')) != '')  rename ("song/$sc", "song/_z");

// build dir[] from song dirs minus _z
   $dir = [];
   foreach (LstDir ("song", 'd') as $d)  if ($d != '_z')  $dir[] = $d;
   sort ($dir);
dump('dir',$dir);

// build pl[] given picked dirs minus did[] (if shuffle)
   $pl = [];
   $did = ($shuf == 'N') ? [] : explode ("\n", Get ("did.txt"));
   foreach ($dir as $i => $d)  if (in_array ($i, $pick)) {
      $mp3 = LstDir ("song/$d", 'f');
      foreach ($mp3 as $fn)  if (! in_array ("$d/$fn", $did))  $pl[] = "$d/$fn";
   }
dump('pl',$pl);
   if ((count ($pick) > 0) && (count ($pl) == 0)) {
      unlink ("did.txt");              // time ta kill did.txt
      header ("Location: index.php?shuf=".$shuf."&pick=".arg ('pick'));
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
let PL = <?= json_encode ($pl); ?>;    // play list
let Tk = 0, Au;                        // track we're on, audio element

function pick ()
{ let p = [];
   all ("[id^='chk']:checked").forEach (chk => { p.push (chk.id.substr (3)); });
   return p;
}

function redo (x = '')                 // get which dirs are picked n refresh
{ const s = $('#shuf').prop ('checked') ? 'Y':'N';
  const p = pick ();
   window.location = "index.php?shuf=" + s + "&pick=" + p.join (',') + x;
}

function chk ()  {redo ();}

function hush (rmv = 'n')
{  Au.pause ();
   if (rmv != 'r')                          // just unhilite
      $('#info tbody tr').eq (Tk).css ("background-color", "");
   else {
      $.get ("did.php", { did: PL [Tk] });  // throw it into did.txt
      PL.splice (Tk, 1);                    // < outa PL n table
      $('#info tbody tr').eq (Tk).remove ();
   }
}

function play (go = 'y')
{  if ((pick ().length > 0) && (PL.length == 0))  redo ();

   $('#info tbody tr').eq (Tk).css ("background-color", "#FFFF80;")
                              .get (0).scrollIntoView ({ behavior: 'smooth' });
   document.title =   PL [Tk];
   Au.src = 'song/' + PL [Tk];
   if (go == 'y')  Au.play ();
}

function prev ()
{  hush ();   Tk = (Tk == 0) ? (PL.length-1) : (Tk-1);   play ();  }

function next ()
{  hush ();   Tk = (Tk == PL.length-1) ? 0   : (Tk+1);   play ();  }

function lyr ()                        // hit google lookin fo lyrics
{ let s = PL [Tk];
   s = s.substr (s.indexOf ('/')+1);   // toss leading dir and .mp3
   s = s.substr (s, s.length-4);       // and my dumb _rhap
   if (s.substr (s.length-5, 5) == "_rhap")  s = s.substr (0, s.length-5);
   s = s.replace (/_/g, " ");
   window.open ("https://google.com/search?q=lyrics " + s, "_blank");
}

function scoot ()  { redo ('&sc=' + PL [Tk]); }

$(function () {                        // boot da page
   navInit ();   $('a').button ();

   Au = el ('audio');
// Au.volume = 0.2;

   $('input' ).checkboxradio ().click (chk);
   $('#prev' ).button ().click (prev);
   $('#next' ).button ().click (next);
   $('#scoot').button ().click (scoot);
   $('#lyr'  ).button ().click (lyr);
   $('#info tbody').on ('click', 'tr', function () {
      hush ();   Tk = $(this).index ();   play ();
   });
   Au.addEventListener ('ended', () => { hush ('r');   play (); });
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

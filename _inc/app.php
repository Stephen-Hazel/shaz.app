<? # inc/app.php - common junk

$nav = [
   ["shaz.app", ".",    ""],
   ["my pics",  "pic",  ""],
   ["my songs", "song", ""],
   ["bout me",  "me",   ""],
   ["youtube",  "https://youtube.com/@SteveHazel", ""],
   ["facebook", "https://www.facebook.com/profile.php?id=61573878782184", ""]
];
#_______________________________________________________________________________
function Got ($fn)  {return file_exists ($fn);}
function Get ($fn)  {return file_get_contents ($fn);}
function Put ($fn, $s)     {file_put_contents ($fn, $s);}

function LstDir ($p, $df)
{  $lst = [];
   $naw = ['.', '..'];
   $d = dir ($p);
   while (($e = $d->read ()) !== false)
      if ( (($df == 'd') &&    is_dir ("$p/$e") && (! in_array ($e, $naw))) ||
           (($df != 'd') && (! is_dir ("$p/$e"))) )
         $lst [] = $e;
   return $lst;
}
#_______________________________________________________________________________
function now ()  {return date ('Y-m-d H:i:s');}

function nows ()
{  $uts = microtime (true);
   $ts = floor ($uts);
   $ms = round (($uts - $ts) * 1000000);
   $mss = sprintf ("%06d", $ms);
   $dts = date ("m/d.D.H:i:s.", $ts);
   return $dts . $mss;
}

function dbg ($s)
// single line timed message to debug file
{  if ($fh = fopen ("dbg.txt", "a"))
      {fwrite ($fh, nows () . " $s\n");   fclose ($fh);}
}

function hdfn ($fn)  {dbg("fn=$fn\n" . `hexdump -C $out`);}

function dump ($ttl, $h, $lvl = 0)
// dump a hash (usually) to dbg - but should do all types now :)
{  $ind = "";   for ($n = 0;  $n < $lvl;  $n++)  $ind .= "   ";
   if (is_array ($h)) {
      dbg ($ind . '{ ' . $ttl . '   count=' . count ($h));
      foreach ($h as $k => $v) {
         if      (is_array    ($v))  dump ($k, $v, $lvl + 1);
         else if (is_bool     ($v))  dbg ("$ind   $k="    .($v?"T":"F"));
         else if (is_resource ($v))  dbg ("$ind   $k=RES ".
                                                    get_resourc_type ($v));
         else if (is_object   ($v))  dbg ("$ind   $k=OBJ ".get_class ($v));
         else if (is_null     ($v))  dbg ("$ind   $k=NULL");
         else                        dbg ("$ind   $k=$v");
      }
      dbg ($ind . "}");
   }
   else if (is_bool     ($h))  dbg ("$ind$ttl="    .($h?"T":"F"));
   else if (is_resource ($h))  dbg ("$ind$ttl=RES ".get_resource_type ($h));
   else if (is_object   ($h))  dbg ("$ind$ttl=OBJ ".get_class ($h));
   else if (is_null     ($h))  dbg ("$ind$ttl=NULL");
   else                        dbg ("$ind$ttl=$h");
//dbg ($ttl."\n".print_r ($h, true));
}

function callstack ()
{  ob_start ();   debug_print_backtrace ();   $d = ob_get_contents ();
   ob_end_clean ();         dbg ("callstack:\n$d");
}
#_______________________________________________________________________________
function aHas ($a, $k)                 // php8 please
{  if (! is_array ($a)) {
dbg("aHas NOT ARRAY k=$k\n".print_r (debug_backtrace (), true));
      return false;
   }
   return array_key_exists ($k, $a);   // way too much to type :/
}

function  aGet ($a, $k, $dflt = '')
{  return aHas ($a, $k) ? $a [$k] : $dflt;  }

function aKill (&$a, $v)               // i'm sick o doin dis
{  $o = array ();                      // yeah i could splice :/
   foreach ($a as $r)  if ($r != $v)  $o[] = $r;
   $a = $o;
}

function srvr ()  {return        $_SERVER['SERVER_NAME'];}
function page ()  {return        $_SERVER['REQUEST_URI'];}
function me   ()  {return        $_SERVER['PHP_SELF'];}
function args ()  {return (($s = $_SERVER['QUERY_STRING'])=='') ? '' : "?$s";}
function arg  ($k, $def = '')  {return aGet ($_REQUEST, $k, $def);}
function argx ($n)             {return       $_REQUEST [$n];} // raw arrays,etc
function sess ($k, $def = '')  {return aGet ($_SESSION, $k, $def);}
function setsess ($k, $val = '')            {$_SESSION [$k] = $val;}
function unsess  ($k)          {unset       ($_SESSION [$k]);     }
#_______________________________________________________________________________
// html is sooo uglyyy...   so...
function pg_css ($css, $p)
{  foreach (explode (' ', $css) as $c)
      echo " <link href='$p"."_css/$c".".css' rel='stylesheet' />\n";
}

function pg_js ($js, $p)
{  foreach (explode (' ', $js) as $j) {
      if ($j == 'pg')  $p = '';        // pg.js (n on) is local
      if (substr ($j, 0, 4) == 'http') // not on myyy site
           echo " <script type='text/javascript' src='$j'></script>\n";
      else echo " <script src='$p"."_js/$j".".js'></script>\n";
   }
}
#_______________________________________________________________________________
function pg_head ($css, $js)
{ global $PG, $nav;
   header ("set Access-Control-Allow-Origin '*'");
   $p = ($PG == '.') ? "" : "../";
   foreach ($nav as $n)  if ($PG == $n [1])  $ttl = $n [0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?= $ttl ?></title>
 <link href="https://shaz.app/favicon.ico"    rel="Shortcut Icon" />
<? if (substr ($css, 0, 5) == 'jqui ') {
      $css = substr ($css, 5);
      pg_css ("jquery-ui.min jquery-ui.structure.min jquery-ui.theme.min", $p);
   }
   pg_css ($css, $p);
   if (substr ($js, 0, 3) == 'jq ')
      {$js  = substr ($js,  3);   pg_js  ("jquery", $p);}
   if (substr ($js, 0, 5) == 'jqui ')
      {$js  = substr ($js,  5);   pg_js  ("jquery jquery-ui.min", $p);}
   pg_js ($js, $p);
}
#_______________________________________________________________________________
function pg_body ()
{ global $PG, $nav;
   $p = ($PG == '.') ? "" : "../";
?>
</head>
<body>

<button    id="btn-nav-open" onclick="navOpen()">
    <img src="<?= $p ?>_img/open.svg">
</button>
<nav id="navbar">
 <ul>
  <li>
   <button id="btn-nav-shut" onclick="navShut()">
    <img src="<?= $p ?>_img/shut.svg">
   </button>
  </li>
<? foreach ($nav as $i => $n) {
      $ttl = $n [0];   $lnk = $n [1];   $x = $n [2];
      $hr = (substr ($lnk,0,4) == "http") ? $lnk : $p.$lnk;
      $tg = (substr ($lnk,0,4) == "http") ? " target='_blank'" : "";
      $c1 = ($i == 0)   ? " class='li-home'"  : "";
      $c2 = ($PG==$lnk) ? " class='nav-pick'" : "";
      echo "   <li$c1><a href='$hr'$tg$c2>$ttl</a></li>\n";
   } ?>
 </ul>
</nav>
<div id="nav-over" onclick="navShut()"></div>

<main>
<?
}
#_______________________________________________________________________________
function pg_foot ()
{
?>
</main>

</body>
</html>
<?
}
#_______________________________________________________________________________
function select ($id, $ls, $pik = '')
{  echo "<select id='$id' name='$id'>\n";
   foreach ($ls as $o) {
      $a = explode ('`', $o);
      $val = $lbl = $a [0];  if (COUNT ($a) == 2)  $lbl = $a [1];
      echo
        " <option value='$val'" . (($val == $pik)?" SELECTED":"") .
          ">$lbl</option>\n";
   }
   echo "</select>\n";
}

function check ($id, $lbl, $on = '')
{  echo "<input id='$id' name='$id' type='checkbox'" .
                (($on=='Y')?" checked":"") . ">\n" .
        "<label for='$id'>$lbl</label>\n";
}

function table ($id, $hdr, $row)
{  echo
"<table id='$id' name='$id'>\n" .
" <thead>\n" .
"  <tr>\n";
   foreach ($hdr as $c)  echo
"   <th>$c</th>\n";
   echo
"  </tr>\n" .
" </thead>\n" .
" <tbody>\n";
   foreach ($row as $r => $ro) {
      echo
"  <tr id='$id$r'>\n";
      foreach ($ro as $c => $co)  echo
"   <td>$co</td>\n";
      echo
"  </tr>\n";
   }
   echo
" </tbody>\n" .
"</table>\n";
}

function table1 ($id, $hdr, $row)
// table w only 1 column
{  echo
"<table id='$id' name='$id'>\n" .
" <thead><tr><th><b>$hdr</b></th></tr></thead>\n" .
" <tbody>\n";
   foreach ($row as $r => $ro)  echo
"  <tr id='$id$r'><td>$ro</td></tr>\n";
   echo
" </tbody>\n" .
"</table>\n";
}

<? # song/index.php - play ma songs
   # to do - preserve did; chromecast audio; hey google skip; preserve volume

require_once ("../_inc/app.php");

   $shuf = arg ('shuf','N');   $pick = explode (',', arg ('pick'));

// doin a move too?
   $fr = arg ('fr');   $to = arg ('to');
   if (($fr != '') && ($to != ''))  system ("mv song/$fr song/$to");

   $dir = LstDir ("song", 'd');   sort ($dir);
// $did = explode ("\n", Get ("did.txt"));
   $did = [];

   $pl = [];
   foreach ($dir as $i => $d)  if (in_array ($i, $pick)) {
      $mp3 = LstDir ("song/$d", 'f');
      foreach ($mp3 as $fn)  if (! in_array ($fn, $did))  $pl[] = "$d/$fn";
   }
   if ($shuf == 'Y') shuffle ($pl);   else sort ($pl);

   $PG = "song";
   pg_head ("jqui app", "jqui app ".
      "https://cdnjs.cloudflare.com/ajax/libs/castjs/5.3.0/cast.min.js");
?>
 <style>
google-cast-launcher {
   float:  left;
   height: 45px;
}
google-cast-launcher:hover {
   --disconnected-color: white;
   --connected-color:    white;

}
audio {
   vertical-align: bottom;
}
 </style>
 <script> // ____________________________________________________________________
const pl = <?= json_encode ($pl); ?>;  // play list
let   tr = 0, au;                      // track we're on, audio element
let   did = [];                        // songs we played (for PROPER shuffle)
const cast = new Castjs ();

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

function play (newtr, go = 'y')
{  au.pause ();                        // shush, unhilite old one
   el ('info'+tr).style = '';
  const ofn = pl [tr];                 // tack it onto did[]
// if (! did.includes (ofn))  did.push (ofn);

   tr = newtr;   if (tr < 0)  tr = 0;
   if (tr >= pl.length)  return;

  const fn = pl [tr];
   au.src = 'song/' + fn;

   el ('info'+tr).style = "background-color:#FFFF80;";
   if (go == 'y')  au.play ();
/*
     let ses = cast.framework.CastContext.getInstance ().getCurrentSession ();
dbg(ses);
      if (typeof ses === 'undefined') {
dbg("no session");
         return;
      }
     let mIn = new chrome.cast.media.MediaInfo ('song/'+fn, 'audio/mpeg');
     let lRq = new chrome.cast.media.LoadRequest (mIn);
dbg('song/'+fn);
      ses.loadMedia (lRq).then (
         function ()  {
dbg("loaded ok");
                      },
         function (e) { dbg("load error", e); }
      );
   }
*/
}

function PlPa ()
{ let player = new cast.framework.RemotePlayer ();
dbg(player);
dbg("canPause="+player.canPause);
  let ctl    = new cast.framework.RemotePlayerController (player);
dbg(ctl);
   ctl.playOrPause ();
dbg("ok ?");
   ctl.addEventListener(
      cast.framework.RemotePlayerEventType.MEDIA_INFO_CHANGED, function() {
      // Use the current session to get an up to date media status.
        let session = cast.framework.CastContext.getInstance()
                                                       .getCurrentSession();
dbg("session");
dbg(session);
         if (! session)  return;

      // Contains information about the playing media including currentTime.
        let mediaStatus = session.getMediaSession();
dbg("mediaStatus");
dbg(mediaStatus);
         if (!mediaStatus)  return;

      // mediaStatus also contains the mediaInfo containing metadata and other
      // information about the in progress content.
        let mediaInfo = mediaStatus.media;
dbg("mediaInfo");
dbg(mediaInfo);
     });
}

function prev ()  {play (tr-1);}
function next ()  {play (tr+1);}

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
/*
function loadMedia (session, src)
{ const mInfo = new chrome.cast.media.MediaInfo (src, 'audio/mpeg');
  const rqst  = new chrome.cast.media.LoadRequest (mInfo);
   session.loadMedia (rqst).then (() => {
      console.log ('Media loaded successfully.');
   },
   (errorCode) => {
      console.error ('Error loading media: ', errorCode);
   });
}

function castAudio ()
{ const sess =
      cast.framework.CastContext.getInstance ().getCurrentSession ();
   if (sess)  loadMedia (sess, au.src);
   else {
      cast.framework.CastContext.getInstance ().requestSession ().then (
         (session) => {
            loadMedia (session, au.src);
         },
         () => {
            console.error ("Failed to start cast session.");
         }
      );
   }
}
*/

function castAudio ()
{  if (cast.available)  cast.session (pl [0]);
}

$(function () {                        // boot da page
   navInit ()
   $('a').button ();

   au = el ('audio');
// au.volume = 0.2;

   $('input').checkboxradio ().click (chk);
   $('#prev').button ().click (prev);
   $('#next').button ().click (next);

   $('#plpa').button ().click (PlPa);

   $('#lyr' ).button ().click (lyr );
   $('#move').button ().click (move);
   $('#to'  ).selectmenu ({ width: 160 });
   all ('table tbody tr').forEach (tr => {
      tr.addEventListener ('click', function () { play (this.rowIndex-1); });
   });

   au.addEventListener ('ended', () => { next (); });
   play (0, 'n');

   $("#bcast").button ().click (castAudio);
});
 </script>
<? pg_body (); ?>
<button id="bcast">cast</button>
<? check ('shuf', 'shuffle', $shuf);   echo " &nbsp; &nbsp;\n";
   foreach ($dir as $i => $s)  check ("chk$i", $s, in_array ($i, $pick)?'Y':'');
?>
<br>
<audio id="audio" controls></audio><a id='prev'>&lt;</a> <a id='next'>&gt;</a>
<a id='move'>move to:</a> <? select ('to',$dir); ?>
<a id='lyr'>lyrics</a><br>

<? table1 ('info', count ($pl)." songs", $pl); ?>
<? pg_foot ();

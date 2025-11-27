<? # index.php - home

require_once ("_inc/app.php");

   pg_head ("shaz.app", "jqui app",  "jqui app", "home");
?>
 <script>
   $(function () {init ();});
 </script>
<? pg_body ([
      ["apps",     "app",  "apps i wrote n stuff"],
      ["pics",     "pic",  "all ma pics"],
      ["songs",    "song", "songs i dig  (and Annie)"],
      ["videos",   "https://www.youtube.com/@SteveHazel/videos",
                                                     "videos i took"],
      ["meee",     "me",   "bout Steve"],
      ["linux",    "https://pianocheetah.app/linux", "my thoughts..."],
      ["facebook", "https://www.facebook.com/stephen.hazel",  "fb is dumb"]
   ], 'h'); ?>
 <h1>Buncha junk Steve did.  And stuff.</h1><br>
 <img src="img/wilee.jpg">
<? pg_foot ();

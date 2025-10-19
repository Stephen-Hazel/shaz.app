<? # index.php - home

require_once ("_inc/app.php");

## home
   pg_head (".", "jqui app",  "jqui jquery.jrumble app");
?>
 <script>
$(function () {
   navInit ();
   $('a').button ();
});
 </script>
<? pg_body (); ?>
<center>
 <br><h1>Welp, here's a buncha junk Steve did.  And stuff.</h1><br>
 <img id='wy' class='pic' src="_img/wilee.jpg">
</center>

<? pg_foot ();

<? # index.php - home

require_once ("_inc/app.php");

## home
   pg_head (".", "jqui app",  "jqui jquery.jrumble app");
?>
 <script>
function jRum (id, ix, iy, irot)
{  jQuery('#'+id).jrumble ({x: ix, y: iy, rotation: irot});
   jQuery('#'+id).hover (
      function () { jQuery(this).trigger ('startRumble'); },
      function () { jQuery(this).trigger ('stopRumble' ); }
   );
}

$(function () {
   navInit ();
   $('a').button ();
   jRum ('pc', 10,  0, 0);
   jRum ('sw',  0, 10, 0);

});
 </script>
<? pg_body (); ?>
<center>
 <b>S</b>teve <b>Haz</b>el's <b>App</b>s:
 <a id='pc'  class='red' pop
    href='https://pianocheetah.app'>pianocheetah</a> &nbsp;
 <a id='sw'  class='ora' pop
    href='https://github.com/Stephen-Hazel/shazware'>shazware</a>
 <br><br>
 <img id='wy' class='pic' src="_img/wilee.jpg">
</center>

<? pg_foot ();

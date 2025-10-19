<? # index.php - app

require_once "../_inc/app.php";

## home
   pg_head ("app", "jqui app",  "jqui jquery.jrumble app");
?>
 <script>
$(function () {
   navInit ();
   $('a').button ();
   jRum ('pc', 10,  0, 0);
   jRum ('sw',  0, 10, 0);

});
 </script>
<? pg_body (); ?>
<center>
 <a id='pc'  class='red' pop
    href='https://pianocheetah.app'>pianocheetah</a><br>
 my baby.<br><br><br>

 <a id='sw'  class='gre' pop
    href='https://github.com/Stephen-Hazel/shazware'>shazware</a><br>
 <b>S</b>teve <b>HAZ</b>el's &nbsp; soft<b>WARE</b> -
 some file utilities i wrote for me.<br><br><br>

 <a class='yel' pop
    href='https://github.com/Stephen-Hazel/stv'>stv lib</a><br>
 my common os,ui,midi,snd,syn,etc,etc library (not an app, just source)<br><br><br>

 <a class='blu' pop
    href='https://github.com/Stephen-Hazel/shaz.app'>shaz.app</a><br>
 shaz.app website junk (not an app, just html,etc)<br><br><br>

 <a class='pur' pop
    href='https://github.com/Stephen-Hazel/pianocheetah.app'>pianocheetah.app</a><br>
 pianocheetah.app website junk (not an app, just html,etc)<br><br><br>

 <a class='aqu' href="SETUPSHAZWARE.exe">SETUPSHAZWARE.exe</a><br>
 my old windows version but you're on your own - i'm not goin back :(
</center>

<? pg_foot ();

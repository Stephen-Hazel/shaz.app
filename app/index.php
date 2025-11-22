<? # index.php - app

require_once "../_inc/app.php";

   pg_head ("app", "jqui app",  "jqui jquery.jrumble app");
?>
 <script>
$(function () {
   init ();
   jRum ('pc', 10,  0, 0);
   jRum ('sw',  0, 10, 0);
});
 </script>
<? pg_body ([
      [$UC['ar-lftup']."home",  "..",  "...take me back hooome"],
   ]); ?>
<a id='pc' pop class='nav-1'
   href='https://pianocheetah.app'>pianocheetah</a><br>
my baby.<br><br><br>

<a id='sw' pop class='nav-2'
   href='https://github.com/Stephen-Hazel/shazware'>shazware</a><br>
<b>S</b>teve <b>HAZ</b>el's &nbsp; soft<b>WARE</b> -
some file utilities i wrote for me.<br><br><br>

<a pop class='nav-3'
   href='https://github.com/Stephen-Hazel/stv'>stv lib</a><br>
my common os,ui,midi,snd,syn,etc,etc library (not an app, just source)
<br><br><br>

<a pop class='nav-4'
   href='https://github.com/Stephen-Hazel/shaz.app'>shaz.app</a><br>
shaz.app website junk (not an app, just html,etc)<br><br><br>

<a pop class='nav-5'
   href='https://github.com/Stephen-Hazel/pianocheetah.app'
  >pianocheetah.app</a><br>
pianocheetah.app website junk (not an app, just html,etc)<br><br><br>

<a pop class='nav-6'
   href="SETUPSHAZWARE.exe">SETUPSHAZWARE.exe</a><br>
my old windows version but you're on your own - i'm not goin back :(

<? pg_foot ();

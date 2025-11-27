<? # index.php - app

require_once "../_inc/app.php";

   pg_head ("app", "jqui app",  "jqui jquery.jrumble app");
?>
 <script>
$(function () {
   init ();
   jRum ('a', 10,  0,  0);
   jRum ('b',  0, 10,  0);
   jRum ('c',  0,  0, 10);
   jRum ('d',  5,  0, 10);
   jRum ('e',  0,  5, 10);
});
 </script>
<? pg_body ([
      [$UC['home']." home",  "..",  "...take me back hooome"],
   ]); ?>
<a id='a' pop class='nav-1' href=
   'https://pianocheetah.app'
  >pianocheetah</a><br>
my baby<br><br><br>

<a id='b' pop class='nav-2' href=
   '../shazware'
  >shazware</a><br>
<b>S</b>teve <b>HAZ</b>el's &nbsp; soft<b>WARE</b> -
some file utilities i wrote<br><br><br>

<a id='c' pop class='nav-3' href=
   'https://github.com/Stephen-Hazel/stv'
  >stv lib</a><br>
my common os,ui,midi,snd,syn,etc,etc library (c++ source)
<br><br><br>

<a id='d' pop class='nav-4' href=
   'https://github.com/Stephen-Hazel/shaz.app'
  >shaz.app</a><br>
shaz.app website junk (html, css, js, php, jq)<br><br><br>

<a id='e' pop class='nav-5' href=
   'https://github.com/Stephen-Hazel/pianocheetah.app'
  >pianocheetah.app</a><br>
pianocheetah.app website junk (html, css, js, php, jq)<br><br><br>

<? pg_foot ();

// app.js

var dbg = console.log.bind (console);  // js is so wordy n looong :/
function el  (s)  {return document.getElementById   (s)}
function all (s)  {return document.querySelectorAll (s)}
function hasAt (e,a)  {return e.hasAttribute (a)}
function setAt (e,a,v)       {e.setAttribute (a, v)}
function clrAt (e,a)      {e.removeAttribute (a)}

function mob ()  {hasAt (nav, 'inert')}

function navUpd (e)
{ const mob = e.matches
   if (mob) {
      setAt (nav, 'inert', '')
      all ('.pic').forEach (i => {
         setAt (i, 'width', '100vw')
         clrAt (i, 'height')
      })
   }
   else {
      clrAt (nav, 'inert')
      all ('.pic').forEach (i => {
         clrAt (i, 'width')
         setAt (i, 'height', '80vh')
      })
   }
}

function navOpen ()  {nav.classList.add    ('show');   clrAt (nav, 'inert')    }
function navShut ()  {nav.classList.remove ('show');   setAt (nav, 'inert', '')}

function navInit ()
{  nav = el ('navbar')
   window.matchMedia ("(width < 700px)").addEventListener (
                                            'change', (e) => navUpd (e))
   all ('nav a').forEach (link => {    // for #bookmark links
      link.addEventListener ('click', () => { navShut () })
   })

// make all links with pop=''  into target="_blank"
   all ('a').forEach (link => {
      if (hasAt (link, 'pop'))  link.target = "_blank";
   });
}

function jRum (id, ix, iy, irot)
{  jQuery('#'+id).jrumble ({x: ix, y: iy, rotation: irot});
   jQuery('#'+id).hover (
      function () { jQuery(this).trigger ('startRumble'); },
      function () { jQuery(this).trigger ('stopRumble' ); }
   );
}


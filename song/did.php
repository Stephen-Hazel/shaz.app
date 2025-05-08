<? # did.php - tack a song onto did.txt

require_once ("../_inc/app.php");

#dump('did.php', $_REQUEST);
Put ("did.txt", Get ("did.txt") . arg('did') . "\n");

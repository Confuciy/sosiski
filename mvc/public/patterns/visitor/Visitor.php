<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$monkey = new Monkey();
$lion = new Lion();
$dolphin = new Dolphin();

$speak = new Speak();

$monkey->accept($speak);    // Ooh oo aa aa!
echo '<br />';
$lion->accept($speak);      // Roaaar!
echo '<br />';
$dolphin->accept($speak);   // Tuut tutt tuutt!
echo '<br /><br />';

###############################################

$jump = new Jump();

$monkey->accept($speak);   // Ooh oo aa aa!
echo '<br />';
$monkey->accept($jump);    // Jumped 20 feet high! on to the tree!
echo '<br /><br />';

$lion->accept($speak);     // Roaaar!
echo '<br />';
$lion->accept($jump);      // Jumped 7 feet! Back on the ground!
echo '<br /><br />';

$dolphin->accept($speak);  // Tuut tutt tuutt!
echo '<br />';
$dolphin->accept($jump);   // Walked on water a little and disappeared
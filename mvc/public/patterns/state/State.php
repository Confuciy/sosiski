<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

$editor = new TextEditor(new DefaultCase());

$editor->type('First line');
echo '<br />';

$editor->setState(new UpperCase());

$editor->type('Second line');
echo '<br />';
$editor->type('Third line');
echo '<br />';

$editor->setState(new LowerCase());

$editor->type('Fourth line');
echo '<br />';
$editor->type('Fifth line');

// Output:
// First line
// SECOND LINE
// THIRD LINE
// fourth line
// fifth line
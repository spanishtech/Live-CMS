<?
// Include the header, allow it to create 
include("header.php");
$hookManager->triggerHook("pre_index");

$hookManager->triggerHook("post_index");
include("footer.php");

<?php
/**
 * Created by PhpStorm.
 * User: spanish
 * Date: 4/04/15
 * Time: 2:44 PM
 */
/*
 * START: Includes and setup
 */
// Require the main.php which will include all the libraries and other files needed.
require_once("inc/main.php");
// SQL to retrieve the current theme based on the setting found in the settings table.
$theme=$sql->firstResult($sql->query("SELECT `setting_value` FROM `settings` WHERE `setting_name`='{0}'",array("current_theme")));
// make sure no one is doing anything sneaky by removing "../" and any variations from the string.
$theme["setting_value"]=$security->lfi($theme["setting_value"]);
// Create twig variables
$loader = new Twig_Loader_Filesystem('content/themes/'.$theme["setting_value"]."/");
$twig = new Twig_Environment($loader, array(
    'cache' => 'content/cache/',
));

/*
 * END: Includes and setup
 */

/*
 * START: Theme
 */
/* This chunk of code does the following
 * Creates an empty array to replace variables with from the template
 * Queries the DB for any variables
 * Adds them to the array.
 * Parses the variable through the hookmanager where plugins may add to it.
 */
$templateReplacementArray = array();
$result=$sql->query("SELECT * FROM `tempalte_variables`");
while($row = $result->fetch_assoc()) {
    $templateReplacementArray[$row["to_replace"]]=$row["replacement"];
}
// The hook manager will return the modified $templateReplacementArray.
$templateReplacementArray=$hookManager->triggerHook("template_variables",array($templateReplacementArray));
echo $twig->render('header.html', $templateReplacementArray);
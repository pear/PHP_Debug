<?php

/**
 * Test script for PHP_Debug 2.1.0 and the HTML_Div renderer
 * 
 * @package PHP_Debug
 * @author  COil
 * @since V2.1.0 - 6 apr 2006
 * @filesource
 * 
 * @version    CVS: $Id$
 */
 
error_reporting(E_ALL); // Report all possible errors
//session_start();        // Start session


// Options array for Debug object
$options = array(
    'render_type'          => 'HTML',    // Renderer type
    'render_mode'          => 'Div',     // Renderer mode
    'restrict_access'      => false,     // Restrict access of debug
    'allow_url_access'     => true,      // Allow url access
    'url_key'              => 'key',     // Url key
    'url_pass'             => 'nounou',  // Url pass
    'enable_watch'         => false,     // Enable wath of vars
    'replace_errorhandler' => true,      // Replace the php error handler
    'lang'                 => 'FR',      // Lang

    // Renderer specific
    'HTML_DIV_view_source_script_name' => 'PHP_Debug_ShowSource.php',
    'HTML_DIV_remove_templates_pattern' => true,
    'HTML_DIV_templates_pattern' => 
        array(
            '/home/phpdebug/www/' => '/projectroot/'
        ),
    'HTML_DIV_images_path' => 'images', 
    'HTML_DIV_css_path' => 'css',
    'HTML_DIV_js_path' => 'js',
);

$allowedip = array( 
    '127.0.0.1'
);

// Include main class
require_once 'PHP/Debug.php';

// Additional ini path for PEAR
define('ADD_PEAR_ROOT', '/home/phpdebug/www/libs/PEAR');
set_include_path(ADD_PEAR_ROOT . PATH_SEPARATOR. get_include_path());

echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Pear::PHP_Debug</title>
    <script type="text/javascript" src="'. $options['HTML_DIV_js_path'] .'/html_div.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="'. $options['HTML_DIV_css_path'] .'/html_div.css" />
  </head>
<body>    
';
?>
<h1>PEAR::PHP_Debug  -------------------------></h1>

<p>
    <a href="PHP_Debug_HTML_Div_test.php">&raquo; HTML_DIV Renderer</a><br/>
    <a href="PHP_Debug_HTML_Table_test.php">&raquo; HTML_Table Renderer</a><br/>
    <a href="PHP_Debug_test.php">&raquo; Test min</a><br/>
    <a href="PHP_Debug_Sources.php">&raquo; Show sources</a><br/>
    <a href="http://www.php-debug.com">&raquo; Back to PHP_Debug home</a><br/>
</p>

<?php
// Tests variables  ============================================================
 
// One variable that will be watched by PHP_Debug
$watchedVariable = 1; 

// One session variable to test the debugtype = PHP_DebuLine::TYPE_ENV (4)
$_SESSION['Kikoo'] = "One session variable defined";



// Debug Object creation =======================================================

$Dbg = new PHP_Debug($options);


// Test restrictAccess() function, only IP in param array will see the debug ===
//$Dbg->restrictAccess($allowedip);


// Test add() function =========================================================

// Standard
$renderer = 'HTML_Div';
$intro = 'This is the <b>'. $renderer.'_Renderer</b>, client IP is '. 
    $_SERVER['REMOTE_ADDR'];
$Dbg->add($intro);

// Standard, fix end and start time manually
$debug_line = $Dbg->add('Manual performance monitoring');
$debug_line->setStartTime();
for ($i = 0; $i < 20000; $i++) {
	$j = 0;
}
$debug_line->setEndTime();

// Application settings ========================================================

// Add an application setting
$Dbg->addSetting($renderer, 'app_renderer_mode');

// Add a group of application settings
$Dbg->addSettings($options, 'app_settings');


// Test dump() function ========================================================

// dump a variable (integer)
$foo = 555;
$Dbg->dump($foo, 'Foo');

// dump a variable (double)
$foo2 = 37.2;
$Dbg->dump($foo2, 'Foo2');


// dump an array
$Dbg->dump($options, 'Options');


// dump an object
$testObject = new PHP_DebugLine('info info info inside DebugLine object');
$testObject = $Dbg->dump($testObject);

// test the automatic return of debug line objects by the public functions 
//$testObject = $Dbg->dump('i am the object');
//PHP_Debug::dumpVar($testObject, '$testObject', 1);

// dump an object and die the script 
//PHP_Debug::dumpVar($testObject, 'stooooooop', true);


// Test setAction() ============================================================


// Type 12 : Page action : --> Methode publique a creer
$action = 'view_test_action';
$Dbg->setAction($action);

// Test watch() function, watched var is 'watchedVariable' =====================

// /!\ Be carefull the tick directive does not work under windows /!\
// and make apache crash. To test under unix, remove comments bellow and
// corresponding brace line 195 
  
//declare (ticks = 1) 
//{

    // Watch the variable called 'watchedVariable'
    //$Dbg->watch('watchedVariable');


    // Stress backtrace function (check line, file, function, class results) ===

    function a()
    {
        global $Dbg, $watchedVariable;
        $Dbg->addDebug('call from a() fonction');
        $Dbg->stopTimer();
    
        $watchedVariable = 501;
        
        b();
    }
    
    function b()
    {
        global $Dbg, $watchedVariable;
        $Dbg->add('call from b() fonction');
    
        $watchedVariable = 502;
    }

    a();
    
    $Dbg->addDebugFirst('call after b() and a() but adding in 1st');
    
    $watchedVariable = 555;
    $watchedVariable = 'converting from INT to STR';

//} // End of declare {ticks=n}  block


// Test the add() function with the timer ======================================

$debug_line2 = $Dbg->add('PERF TEST : 10000 iteration');

$y = 0;
for ($index = 0; $index < 10000; $index++) {
    $y = $y + $index;
}
$Dbg->stopTimer();
$Dbg->dump($debug_line2);


// Test the database functions =================================================

// Database related info
$Dbg->queryRel('Connecting to DATABASE [<b>phpdebug</b>] dns: root:user@mysql');
$Dbg->stopTimer();

// Query
$Dbg->query('SELECT * FROM PHP_DEBUG_USERS');

$y = 0;
for ($index = 0; $index < 10000; $index++) {
    $y = $y + $index;
}
$Dbg->stopTimer();



// Test custom error handler ===================================================

echo $notset;                      // Will raise a PHP notice
fopen('not existing!', 'r');       // Will raise a PHP warning
trigger_error('This is a custom application error !!', E_USER_ERROR);
                                   // Will raise a custom user error
$Dbg->error('Bad status of var x in application PHP_Debug');
                                   // Will add an application error

// Display Debug information (HTML_Table renderer) =============================
 
$Dbg->display();

// Test __toString(), dumpVar() functions and structure of Debug object ========

//echo $Dbg;

// END =========================================================================
?>

</body>
</html>
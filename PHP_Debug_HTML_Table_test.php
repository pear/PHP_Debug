<?php

/**
 * Created on 18 apr 2006
 *
 * Test script for PHP_Debug 2.0.0
 * 
 * @package PHP_Debug
 * @author  COil
 * @since V2.0.0 - 6 apr 2006
 * @filesource
 * 
 * @version    CVS: $Id$
 */
 
error_reporting(E_ALL); // Report all possible errors
//session_start();      // Start session

$renderer = 'HTML_Table';

// Options array for Debug object
$options = array(
    'render_type'          => 'HTML',
    'render_mode'          => 'Table',
    'restrict_access'      => false,
    'allow_url_access'     => true,
    'url_key'              => 'key',
    'url_pass'             => 'nounou',
    'enable_watch'         => false,
    'replace_errorhandler' => true,
    'lang'                 => 'FR',
    'HTML_TABLE_view_source_script_name' => 'PHP_Debug_ShowSource.php',
    'HTML_TABLE_css_path'        => 'css'
);

$allowedip = array( 
    '127.0.0.1'
);


require_once 'PHP/Debug.php';

echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Pear::PHP_Debug</title>
    <link rel="stylesheet" type="text/css" media="screen" href="'. $options['HTML_TABLE_css_path'] .'/html_table.css" />
';
?>
  </head>
<body>
<h1>PEAR::PHP_Debug</h1>
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
</p>

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
$_SESSION['Kikoo'] = 'One session variable defined';


// Debug Object creation =======================================================

$Dbg = new PHP_Debug($options);


// Test restrictAcess() function, only IP in param array will see the debug ====
$Dbg->restrictAccess($allowedip);


// Test add() function =========================================================

// Standard 
$Dbg->add("This is the <b>HTML_Table_Render</b>, client IP is ". 
    $_SERVER['REMOTE_ADDR']);

// Standard, fix end and start time manually
$debug_line = $Dbg->add('Manual performance monitoring');
$debug_line->setStartTime();
for ($i = 0; $i < 20000; $i++) {
    $j = 0;
}
$debug_line->setEndTime();

// Test dump() function ========================================================

// dump a variable (integer)
$foo = 555;
$Dbg->dump($foo, 'Foo');

// dump a variable (double)
$foo2 = 37.2;
$Dbg->dump($foo2, 'Foo2');

// dump an array
//$Dbg->dump($options, 'Options');

// dump an object
$testObject = new PHP_DebugLine('info info info inside DebugLine object');
$Dbg->dump($testObject);

// dump an object and die the script 
//PHP_Debug::dumpVar($testObject, 'stooooooop', true);


// Test setAction() ============================================================


// Type 12 : Page action : --> Methode publique a creer
$action = 'view_test_action';
$Dbg->setAction($action);


// Test watch() function, watched var is 'watchedVariable' =====================

// /!\ Be carefull the tick directive does not work under windows /!\
// and make apache crash. To test under unix, remove comments bellow and
// corresponding brace line 170 
  
//declare (ticks = 1) 
//{  // <-- uncomment here

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

//} // End of declare {ticks=n}  block   <-- uncomment here


// Test the add() function with the timer ======================================

$Dbg->add('PERF TEST : 10000 iteration');

$y = 0;
for ($index = 0; $index < 10000; $index++) {
    $y = $y + $index;
}
$Dbg->stopTimer();


// Test the query() function ===================================================

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
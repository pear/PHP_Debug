<?php
/**
 * This the About page
 *
 * @version V2.0
 * @since 4 sept 2006
 * @author Vernet Loic
 * @copyright Phoenix
 * @package PHP_Debug_Website
 */


// CONFIG ======================================================================


// Applications variables & gen includes  ======================================
require_once '../config/ApplicationCfg.php';
require_once PD_WEB_INCLUDES_ROOT. 'common.inc.php';

// Page constants ==============================================================
define('PAGE_ID' ,    'contact'. PD_WEB_FILE_EXT);
define('PAGE_TR_ID' , 'contact');
define('_FILE_', basename(__FILE__));
define('DEFAULT_ACTION', 'CONTACT');


// MODEL =======================================================================


// CONTROLLER ==================================================================
$Dbg->setAction(DEFAULT_ACTION);


//Include the class definition of highlighter
require_once 'Text/Highlighter.php';
require_once 'Text/Highlighter/Renderer/Html.php';

// Include the package to cache highlight results
require_once('Cache/Lite.php');

// Set a few options
$options = array(
    'cacheDir' => PD_WEB_TEMP,
    'lifeTime' => 3600
);

// Create a Cache_Lite object
$Cache_Lite = new Cache_Lite($options);

// Buil the array options for the HTML renderer to get the nice file numbering
$rendOptions = array( 
    'numbers' => $htmlOptions['HTML_TABLE_view_source_numbers'],
    'tabsize' => $htmlOptions['HTML_TABLE_view_source_tabsize'],
);

// Finish parser object creation 
$renderer = new Text_Highlighter_Renderer_Html($rendOptions);
$phpHighlighter = Text_Highlighter::factory("PHP");
$phpHighlighter->setRenderer($renderer);

// Cache, text highlight results because he is quiet long ! 
if ($phpDebugStart = $Cache_Lite->get('phpDebugStart')) {   
    $Dbg->add('Getting phpDebugStart from cache');
} else {
    $phpDebugStart = $phpHighlighter->highlight('
<?php
$tab = "<table width=100%>";

function dbg($str)
{
    $tab .= "<tr><td>$str</td></tr>">;
}

...

echo $tab. \'</table>\';
?>        
    ');
    $Dbg->add('Saving phpDebugStart to the cache');
    $Cache_Lite->save($phpDebugStart);
}


// VIEW ========================================================================

$smarty->assign('phpDebugStart', $phpDebugStart);


// Assign debug infos
$smarty->assign('file', PD_WEB_PHP_DEBUG_ROOT. DIRECTORY_SEPARATOR. PAGE_ID);
$smarty->assign('bodyTpl', PAGE_TR_ID);
$smarty->assign('debugBuffer', $Dbg->getOutput());       

// Display template
$smarty->display('index.tpl');

?>
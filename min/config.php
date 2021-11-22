<?php
/**
 * Configuration for "min", the default application built with the Minify
 * library
 *
 * @package Minify
 */

//$min_enableBuilder = false;
//$min_serveOptions['rewriteCssUris'] = false;


$min_enableStatic = (bool) get_option('minify_static_aktiv');

$min_builderPassword = 'admin';
$minDevelop = json_decode( get_option( 'minify_settings_entwicklung' ) );
$minProduct = json_decode( get_option( 'minify_settings_production' ) );

switch (get_option('minify_settings_select')){
    case '1':
        $min_allowDebugFlag = (bool) $minDevelop->debug_aktiv;
        $min_serveOptions['maxAge'] = (int) $minDevelop->cache_max_age;
        $min_enableBuilder = true;
        $min_errorLogger = (bool) $minDevelop->debug_aktiv;
        $min_concatOnly = (bool) $minDevelop->verkettung;
        $min_cachePath = $minDevelop->min_cachePath;
        break;
    case '2':
        $min_allowDebugFlag = (bool) $minProduct->debug_aktiv;
        $min_serveOptions['maxAge'] = (int) $minProduct->cache_max_age;
        $min_enableBuilder = false;
        $min_errorLogger = (bool) $minProduct->debug_aktiv;
        $min_concatOnly = (bool) $minProduct->verkettung;
        $min_cachePath = $minProduct->min_cachePath;
        break;
}

switch (get_option('minify_cache_type')){
    case '2':
        $min_cachePath = new Minify_Cache_APC();
        break;
    case '3':
        $memcache = new Memcache;
        $memcache->connect(get_option('minify_memcache_host'), (int) get_option('minify_memcache_port'));
        $min_cachePath = new Minify_Cache_Memcache($memcache);
        break;
    case '4':
        $min_cachePath = new Minify_Cache_ZendPlatform();
        break;
    case '5':
        $min_cachePath = new Minify_Cache_XCache();
        break;
    case '6':
        $min_cachePath = new Minify_Cache_WinCache();
        break;
}

$min_documentRoot = HUPA_MINIFY_ROOT_PATH;
$min_serveOptions['bubbleCssImports'] = (bool) get_option('minify_css_bubble_import');
$min_serveOptions['minApp']['groupsOnly'] = true;


/**
 * Cache file locking. Set to false if filesystem is NFS. On at least one
 * NFS system flock-ing attempts stalled PHP for 30 seconds!
 */
$min_cacheFileLocking = true;

/**
 * Cache-Control: max-age value sent to browser (in seconds). After this period,
 * the browser will send another conditional GET. Use a longer period for lower
 * traffic but you may want to shorten this before making changes if it's crucial
 * those changes are seen immediately.
 *
 * Note: Despite this setting, if you include a number at the end of the
 * querystring, maxAge will be set to one year. E.g. /min/f=hello.css&123456
 */



/**
 * To use the CSS compressor that shipped with 2.x, uncomment the following line:
 */
//$min_serveOptions['minifiers'][Minify::TYPE_CSS] = array('Minify_CSS', 'minify');


/**
 * To use Google's Closure Compiler API to minify Javascript (falling back to JSMin
 * on failure), uncomment the following line:
 */
//$min_serveOptions['minifiers']['application/x-javascript'] = array('Minify_JS_ClosureCompiler', 'minify');


/**
 * If you'd like to restrict the "f" option to files within/below
 * particular directories below DOCUMENT_ROOT, set this here.
 * You will still need to include the directory in the
 * f or b GET parameters.
 *
 * // = shortcut for DOCUMENT_ROOT
 */
//$min_serveOptions['minApp']['allowDirs'] = array('//js', '//css');

/**
 * Set to true to disable the "f" GET parameter for specifying files.
 * Only the "g" parameter will be considered.
 */



/**
 * By default, Minify will not minify files with names containing .min or -min
 * before the extension. E.g. myFile.min.js will not be processed by JSMin
 *
 * To minify all files, set this option to null. You could also specify your
 * own pattern that is matched against the filename.
 */
//$min_serveOptions['minApp']['noMinPattern'] = '@[-\\.]min\\.(?:js|css)$@i';


/**
 * If you minify CSS files stored in symlink-ed directories, the URI rewriting
 * algorithm can fail. To prevent this, provide an array of link paths to
 * target paths, where the link paths are within the document root.
 *
 * Because paths need to be normalized for this to work, use "//" to substitute
 * the doc root in the link paths (the array keys). E.g.:
 * <code>
 * array('//symlink' => '/real/target/path') // unix
 * array('//static' => 'D:\\staticStorage')  // Windows
 * </code>
 */
$min_symlinks = array();


/**
 * If you upload files from Windows to a non-Windows server, Windows may report
 * incorrect mtimes for the files. This may cause Minify to keep serving stale
 * cache files when source file changes are made too frequently (e.g. more than
 * once an hour).
 *
 * Immediately after modifying and uploading a file, use the touch command to
 * update the mtime on the server. If the mtime jumps ahead by a number of hours,
 * set this variable to that number. If the mtime moves back, this should not be
 * needed.
 *
 * In the Windows SFTP client WinSCP, there's an option that may fix this
 * issue without changing the variable below. Under login > environment,
 * select the option "Adjust remote timestamp with DST".
 * @link http://winscp.net/eng/docs/ui_login_environment#daylight_saving_time
 */
$min_uploaderHoursBehind = 0;


/**
 * Advanced: you can replace some of the PHP classes Minify uses to serve requests.
 * To do this, assign a callable to one of the elements of the $min_factories array.
 *
 * You can see the default implementations (and what gets passed in) in index.php.
 */
//$min_factories['minify'] = ... a callable accepting a Minify\App object
//$min_factories['controller'] = ... a callable accepting a Minify\App object

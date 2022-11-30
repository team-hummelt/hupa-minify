<?php

namespace Minifi\ScssPhp;
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

defined('ABSPATH') or die();

use Exception;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;

require HUPA_MINIFY_PLUGIN_DIR . '/scssphp/vendor/autoload.php';

class HupaMinifyScssPlugin
{
    private static $instance;
    protected string $in_dir;
    protected string $out_dir;
    protected string $cache_dir;
    protected string $formatter;
    protected string $map_option;
    protected string $line_comments;
    protected string $scss_file_name;
    protected string $css_file_name;
    protected string $tmp_css;
    protected string $destination_dir;
    protected string $destination_uri;
    protected string $regExUriPath = '/(wp-content.+|wp-include.+)/i';
    protected array $parsedFiles;

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->in_dir = get_option('minify_scss_source');
        $this->out_dir = get_option('minify_scss_destination');
        $this->formatter = get_option('minify_scss_formatter');
        $this->map_option = get_option('scss_map_aktiv');
        $this->line_comments = get_option('line_comments_aktiv');
        $tmp = sys_get_temp_dir();
        $this->tmp_css = substr($tmp, strrpos($tmp, '/'));
    }

    /**
     * @throws Exception
     * @throws SassException
     */
    public function compileFile()
    {

        $source_dir = HUPA_MINIFY_THEME_ROOT . $this->in_dir;
        $destination_dir = HUPA_MINIFY_THEME_ROOT . $this->out_dir;

        if (!is_dir($source_dir)) {
            return null;
        }
        if (!$this->check_if_dir($destination_dir)) {
            return null;
        }

        $src = array_diff(scandir($source_dir), array('..', '.'));
        if ($src) {
            foreach ($src as $tmp) {
                if(substr($tmp,0,1) == '_') {
                    continue;
                }
                $file = $source_dir . DIRECTORY_SEPARATOR . $tmp;
                if (!is_file($file)) {
                    continue;
                }

                $pi = pathinfo($file);
                if ($pi['extension'] === 'scss') {
                    $this->scss_file_name = $pi['basename'];
                    $this->css_file_name = $pi['filename'] . '.css';
                    $cssDestination = $destination_dir . $this->css_file_name;
                    $source = $source_dir . $pi['basename'];
                    $this->destination_dir = $destination_dir;
                    preg_match($this->regExUriPath, $destination_dir, $matches);
                    if (!$matches) {
                        continue;
                    }
                    $this->destination_uri = site_url() . '/' . str_replace('\\', '/', $matches[0]);
                    $this->minifyCompiler($source, $cssDestination);
                }
            }
        }
    }

    public function check_if_dir($dir): bool
    {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws Exception
     * @throws SassException
     */
    public function minifyCompiler($source, $out = null)
    {

        //weiter laufen, auch wenn der Benutzer das Skript durch Schließen des Browsers, des Terminals usw. "stoppt".
        ignore_user_abort(true);
        set_time_limit(0);

        $cacheArr = null;
        if(get_option('minify_cache_aktiv') && !empty(get_option('minify_cache_path'))){
            ///$this->delete_scss_compiler_cache(get_option('minify_cache_path'));
            $cacheArr = ['cacheDir' => get_option('minify_cache_path')];
        }
        $scssCompiler = new Compiler($cacheArr);
        $pi = pathinfo($source);
        $scssCompiler->addImportPath($pi['dirname'] . '/');

        //Format Ausgabe
        switch ($this->formatter) {
            case 'expanded':
                $scssCompiler->setOutputStyle(OutputStyle::EXPANDED);
                break;
            case 'compressed':
                $scssCompiler->setOutputStyle(OutputStyle::COMPRESSED);
                break;
        }

        if ($this->map_option) {
            switch (get_option('minify_scss_map_option')) {
                case 'map_file':
                    $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
                    $scssCompiler->setSourceMapOptions(array(
                        'sourceMapWriteTo' => $this->destination_dir . str_replace("/", "_", $this->css_file_name) . ".map",
                        'sourceMapURL' => $this->destination_uri . str_replace("/", "_", $this->css_file_name) . ".map",
                        'sourceMapFilename' => $this->css_file_name,
                        'sourceMapBasepath' => HUPA_MINIFY_ROOT_PATH,
                    ));
                    break;
                case 'map_inline':
                    $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_INLINE);
                    break;
            }
        } else {
            $scssCompiler->setSourceMap(Compiler::SOURCE_MAP_NONE);
        }

        $compiled = $scssCompiler->compileString(file_get_contents($source), $source);
        if (get_option('minify_scss_map_option') == 'map_file') {
            $mapDest = $this->destination_dir . str_replace("/", "_", $this->css_file_name) . ".map";
            file_put_contents($mapDest, $compiled->getSourceMap());
        }
        if ($out !== null) {
            return file_put_contents($out, $compiled->getCss());
        }
        return $compiled;
    }

    public function delete_scss_compiler_cache($dir)
    {
        if (is_dir($dir)) {
            $scanned_directory = array_diff(scandir($dir), array('..', '.'));
            foreach ($scanned_directory as $file) {
                $f = explode('_', $file);
                if (isset($f[0]) && $f[0] == 'scssphp') {
                    if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                        @unlink($dir . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }
    }

    public function enqueue_scss_script()
    {
        if(get_option('minify_enqueue_aktiv')) {
            $dir = HUPA_MINIFY_ROOT_PATH . $this->out_dir;
            if (is_dir($dir)) {
                $scanned_directory = array_diff(scandir($dir), array('..', '.'));
                $separator = substr($this->out_dir, -1, 1);
                if ($separator == '/') {
                    $separator = '';
                } else {
                    $separator = '/';
                }
                foreach ($scanned_directory as $file) {
                    $pathInfo = pathinfo($dir . $separator . $file);
                    if ($pathInfo['extension'] === 'css') {
                        $url = str_replace('\\', '/', site_url() . '/wp-content/themes/' . $this->out_dir . $separator);
                        $url = $url . $pathInfo['basename'];
                        $id = 'css-compiler-file-' . $pathInfo['filename'];
                        wp_enqueue_style($id, $url, [], HUPA_MINIFY_PLUGIN_VERSION);
                    }
                }
            }
        }
    }
}
$isLogin = true;
if(get_option( 'scss_login_aktiv' ) && !is_user_logged_in() ){
    $isLogin = false;
}
global $SCSS_compiler;
if (MINIFY_SCSS_COMPILER_AKTIV && $isLogin) {
    $SCSS_compiler = HupaMinifyScssPlugin::instance();
    try {
        $SCSS_compiler->compileFile();
    } catch (Exception|SassException $e) {
        echo '<div class="d-flex justify-content-center flex-column position-absolute start-50 translate-middle bg-light p-3" style="z-index: 99999;width:95%;top:10rem;min-height: 150px; border: 2px solid #dc3545; border-radius: .5rem"> <span class="text-danger fs-5 fw-bolder d-flex align-items-center"><i class="bi bi-cpu fs-4 me-1"></i>SCSS Compiler Error:</span>   ' . $e->getMessage() . '</div>';
    }
}




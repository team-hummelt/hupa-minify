<?php

namespace Minify\Compress;
defined('ABSPATH') or die();

/**
 *
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */

final class MINIFY_WP_HTML_Compression
{
	private static $minify_optimize_instance;
	protected bool $minify_compress_css = true;
	protected bool $minify_compress_js = true;
	protected bool $minify_info_comment = true;
	protected bool $minify_remove_comments = true;
	protected $html;


	/**
	 * @param $html
	 * @return static
	 */
	public static function minify_optimize_instance($html): self
	{
		if (is_null(self::$minify_optimize_instance)) {
			self::$minify_optimize_instance = new self($html);
		}
		return self::$minify_optimize_instance;
	}

	/**
	 * HUPA_WP_HTML_Compression constructor.
	 * @param $html
	 */
	public function __construct($html)
	{
		if (!empty($html)) {
			$this->minify_parseHTML($html);
		}
	}

	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->html;
	}

	/**
	 * @param $html
	 */
	public function minify_parseHTML($html)
	{
		$this->html = $this->minify_minifyHTML($html);
		if ($this->minify_info_comment) {
			$this->html .= "\n" . $this->minify_bottomComment($html, $this->html);
		}
	}

	protected function minify_minifyHTML($html): string
	{
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		$strip = '';
		$html = '';
		foreach ($matches as $token) {
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
			$content = $token[0];
			if (is_null($tag)) {
				if (!empty($token['script'])) {
					$strip = $this->minify_compress_js;
				} else if (!empty($token['style'])) {
					$strip = $this->minify_compress_css;
				} else if ($content == '<!--wp-html-compression no compression-->') {
					$overriding = !$overriding;
					continue;
				} else if ($this->minify_remove_comments) {
					if (!$overriding && $raw_tag != 'textarea') {
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			} else {
				if ($tag == 'pre' || $tag == 'textarea') {
					$raw_tag = $tag;
				} else if ($tag == '/pre' || $tag == '/textarea') {
					$raw_tag = false;
				} else {
					if ($raw_tag || $overriding) {
						$strip = false;
					} else {
						$strip = true;
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
						$content = str_replace(' />', '/>', $content);
					}
				}
			}
			if ($strip) {
				$content = $this->minify_removeWhiteSpace($content);
			}
			$html .= $content;
		}
		return $html;
	}

	/**
	 * @param $str
	 *
	 * @return string
	 */
	protected function minify_removeWhiteSpace($str):string
	{
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n", '', $str);
		$str = str_replace("\r", '', $str);
		while (stristr($str, '  ')) {
			$str = str_replace('  ', ' ', $str);
		}
		return $str;
	}

	/**
	 * @param $raw
	 * @param $compressed
	 * @return string
	 */
	protected function minify_bottomComment($raw, $compressed):string
	{
		$raw = strlen($raw);
		$compressed = strlen($compressed);
		$savings = ($raw - $compressed) / $raw * 100;
		$savings = round($savings, 2);
		return '<!--MINIFY THEME HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
	}
}

/**
 * @param $html
 * @return string
 */
function content_wp_html_compression_finish($html): string
{
	return MINIFY_WP_HTML_Compression::minify_optimize_instance($html);
}

function minify_wp_html_compression_start()
{
	ob_start('Minify\\Compress\\content_wp_html_compression_finish');
}


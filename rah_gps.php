<?php

/**
 * A plugin for Textpattern CMS. Extracts GET and POST variables as Textpattern variables.
 *
 * @author Adi Gilbert
 * @author Jukka Svahn
 * @date 2012-
 * @license GNU GPLv2
 * @link https://github.com/gocom/rah_gps
 * 
 * Requires Textpattern v4.0.7 or newer.
 */

/**
 * Extract HTTP POST/GET
 * @param array $atts
 * @param string $atts[name] Comma separated list of HTTP POST/GET params.
 * @param string $atts[new] Name of the new variable. If not set, POST/GET param's name is used.
 * @param bool $atts[escape] Escape POST/GET value.
 * @param string $atts[type] Sets where to get values (strictly POST or both). Either "gps" or "ps".
 * @return nothing
 * @see doArray(), gps(), ps(), trace_add(), $variable
 *
 * <code>
 *		<txp:rah_gps name="q, page" />
 * </code>
 */

	function rah_gps($atts) {
		
		global $variable;
		static $extracted = array();
	
		extract(lAtts(array(
			'name' => NULL,
			'new' => '',
			'escape' => 1,
			'type' => 'gps'
		), $atts));
	
		$type = $type != 'gps' ? 'ps' : 'gps';
	
		if($name === NULL) {
			$vars = array_keys(array_merge((array) $_GET, (array) $_POST));
		}
	
		else {
			$vars = doArray(explode(',', $name), 'trim');
		}
	
		foreach($vars as $n) {
	
			$value = $type($n);
			
			if(!is_scalar($value) || $value === NULL)
				$value = '';
	
			$n = htmlspecialchars($new !== '' ? $new : $n);
			
			if($name === NULL && isset($variable[$n]) && !isset($extracted[$n]))
				continue;
			
			if($escape)
				$value = htmlspecialchars($value);

			$extracted[$n] = true;
			$variable[$n] = $value;
			
			trace_add('['.$n.': '.$value.']');
		}
	}
?>
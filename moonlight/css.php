<?php
/*
 * MindTouch Deki - enterprise collaboration and integration platform
 *  derived from MediaWiki (www.mediawiki.org)
 * Copyright (C) 2006 MindTouch, Inc.
 * www.mindtouch.com  oss@mindtouch.com
 *
 * For community documentation and downloads visit www.opengarden.org;
 *  please review the licensing section.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

chdir($_SERVER['DOCUMENT_ROOT']);
define('MEDIAWIKI', true);
$wgDisabledGzip = false;
require_once('LocalSettings.php');

$cachedir = $IP.'/skins/common/cache';

//if etag is sent, see if that file exists and return a 304
if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
	$cachefile = $cachedir.'/cache-'.$_SERVER['HTTP_IF_NONE_MATCH'];
	if (is_file($cachefile)) {
		$cachefiles = array($cachefile);
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", getLastModified($cachefiles)) . " GMT");
	    header("HTTP/1.0 304 Not Modified");
	    header('Content-Length: 0');
	    exit();
	}
}

// get the current skin directory
$skinDir = dirname(__FILE__);
$IncludeList = array($skinDir . '/style.css');

// Determine last modification date of the files
$lastmodified = getLastModified($IncludeList);

// Send Etag hash
$hash = $lastmodified . '-' . md5(serialize($IncludeList));

// Set the cache filename
define('CACHE_FILE', $cachedir.'/'.'cache-' . $hash);

header("Content-Type: text/css; charset: ISO-8859-1");

//Get the content of CSS in memory
$content = getContent($IncludeList);

header("Etag: " . $hash );
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastmodified) . " GMT");
header("Connection: close");

ob_start();
$wgDisabledGzip = false;
# Begin GZip; this can be disabled by setting $wgDisabledGzip to true in LocalSettings.php
$isGzip = substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && $wgDisabledGzip !== true;
if ($isGzip) {
	header("Content-Encoding: gzip");
	ob_start('ob_gzhandler');
}
echo($content);
if ($isGzip) {
	ob_end_flush();  // The ob_gzhandler one
	header('Content-Length: '.ob_get_length());
}
ob_end_flush();

function compress($buffer) {
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer);
    $buffer = str_replace('{ ', '{', $buffer);
    $buffer = str_replace(' }', '}', $buffer);
    $buffer = str_replace('; ', ';', $buffer);
    $buffer = str_replace(', ', ',', $buffer);
    $buffer = str_replace(' {', '{', $buffer);
    $buffer = str_replace('} ', '}', $buffer);
    $buffer = str_replace(': ', ':', $buffer);
    $buffer = str_replace(' ,', ',', $buffer);
    $buffer = str_replace(' ;', ';', $buffer);
    return $buffer;
}
function getContent($IncludeList) {
	// Write the cache file if it doesn't exists
	if (!file_exists(CACHE_FILE)) {
		$contents = '';
		foreach($IncludeList as $file) {
		    $contents .= "\n\n" . file_get_contents($file);
		}
		if ($fp = @fopen(CACHE_FILE, 'wb')) {
		    fwrite($fp, $contents);
		    fclose($fp);
	    }
	}
	else {
		$contents = file_get_contents(CACHE_FILE);
	}
	return compress($contents);
}

function getLastModified(&$IncludeList) {
	$lastmodified = 0;
	foreach ($IncludeList as $k => $file) {
		if (!is_file($file)) {
			unset($IncludeList[$k]);
			continue;
		}
	    $lastmodified = max($lastmodified, filemtime($file));
	}
	return $lastmodified;
}

?>

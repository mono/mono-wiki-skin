<?php
/*
 * MindTouch DekiWiki - a commercial grade open source wiki
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

/**
 * Base Template
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
    die();

/** */
require_once('includes/SkinTemplate.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinMono extends SkinTemplate {
	/**
	 * @type const - Defines the number of custom HTML areas available
	 */
	const HTML_AREAS = 6;

    /** Using Base. */
    function initPage( &$out ) {
        SkinTemplate::initPage( $out );
        $this->skinname  = 'moonlight';
        $this->stylename = 'mono';
        $this->template  = 'BaseTemplate';
    }
}
    
class BaseTemplate extends QuickTemplate {
    /**
     * Template filter callback for Base skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
     	
    function getSkin() {
      /* FIXME need to figure out how to read the ui/skin configuration from deki */

      #return "moonlight";
      return "mono";
      print "FOO" . $_GLOBALS['wgConfig'];
    }
    function execute() {
        global $wgLogo, $wgUser, $wgTitle, $wgRequest, $wgArticle, $wgOut, $editor, $wgScriptPath, $wgContLang, $wgMenus, $IP;
		    $sk = $wgUser->getSkin();                
        $isArticle = $editor || $wgArticle->getID() > 0 || $wgArticle->mTitle->isEmptyNamespace();

echo('<?xml version="1.0" encoding="UTF-8"?>');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>

    <script type="text/javascript">var _starttime = new Date().getTime();</script>
    <meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta content="index,follow" name="robots"/>
    <link href="/@gui/opensearch/description" title="MindTouch Deki Search" type="application/opensearchdescription+xml" rel="search"/>
    <link href="/index.php?title=&action=edit" title="Edit page" type="application/x-wiki" rel="alternate"/>
    <link href="/skins/mono/moonlight/favicon.ico" rel="shortcut icon"/>
    <link href="/skins/mono/moonlight/favicon.png" type="image/png" rel="shortcut icon"/>

    <title><?php $this->text('pagetitle'); ?></title>
    
    <!-- default css -->
    <?php $this->html('resetcss'); ?>
    <link rel="stylesheet" type="text/css" media="screen" href="/skins/moonlight/css.php"/>

    <?php $this->html('printcss'); ?>
    
    <!-- default scripting -->
    <?php $this->html('javascript'); ?>
    
    <!-- specific screen stylesheets-->
    <?php if (!Skin::isPrintPage()) { ?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php $this->html('pathskin'); ?>/css.php"/>
    <?php } else { ?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php $this->html('pathtpl'); ?>/print.css" />    
    <link rel="stylesheet" type="text/css" media="screen" href="<?php $this->html('pathcommon'); ?>/prince.content.css" />
    <?php } ?>
    
    <!-- specific print stylesheets -->
    <link rel="stylesheet" type="text/css" media="print" href="<?php $this->html('pathtpl'); ?>/print.css" />
   
    <!-- IE6 & IE7 specific stuff -->
    <!--[if IE]><meta http-equiv="imagetoolbar" content="no" /><![endif]-->
    <!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php $this->html('pathskin'); ?>/ie6.php"/><![endif]-->
    
    <?php $this->html('inlinejavascript'); ?>
	
	<!-- styles overwritten via control panel - load this css last -->	
	<?php $this->html('customhead'); ?> 
	<?php $this->html('customarea1'); ?>
</head>

<body class="<?php $this->html('pagetype');?>">
  <div class="header">
    <a href="/" id="logo"></a>
    <div id="navi">
      <!-- FIXME should be in a wiki document? -->
        <div id="menu">
          <a href="/Start">Start</a>
          <a href="/Contributing">Contribute</a>
          <a href="/Forums">Forums</a>
        </div>
      <div id="search"><?php $this->BaseSiteSearch(); ?></div>
    </div><!--navi-->
  </div><!--header-->
  <hr class="headerhr" />
  <!--
  <div class="separator"><hr></div>
  -->


<?php 
#if ($_SERVER['PHP_SELF']=='/') { 
if  ($wgTitle->getPrefixedText() == wfHomePageInternalTitle()) {
  if ($this->getSkin()=="moonlight") {
    require_once('moonlight/frontpage.inc.html'); //splash for the frontpage 
  } else {
    require_once('mono/frontpage.inc.html'); //splash for the frontpage 
  }
}
?>
<div id="content">

<?php
#if ($_SERVER['PHP_SELF']!='/'){ 
if  ($wgTitle->getPrefixedText() != wfHomePageInternalTitle()) { #sidebar for everything but the frontpage
?>
  <div class="sidebar">
    <div id="sidebarmenu">
      <a href="/About">About</a>
      <a href="/Articles">Documentation</a>
      <a href="/Screenshots">Screenshots</a>
      <a href="/Downloads">Downloads</a>
    </div>
    <?php $this->html('toc'); ?>

    <!-- start table of content -->
	   
  </div><!--sidebar-->
<?php
}
?>
  <h1 id="title"><?php $this->text('displaypagetitle'); ?></h1>
  <?php $this->html('bodytext'); ?>





</div><!--content-->
















  <div id="footer">
  <hr>
<?php
  if (!$wgUser->isAnonymous()) {
    print "<div class=\"wikiEdit\">\n";
    print "<ul class=\"login\">\n";
    print '<li><a href="' . $this->haveData('userpageurl') . '">' . $this->haveData('username') . '</a></li>';
    print '<li><a href="' . $this->haveData('logouturl') . '">' . wfMsg('Page.UserLogout.page-title') . '</a></li>';
    print "</ul>\n";

    print "<ul class=\"control\">\n";
    print "<li>Page Editing Controls:</li>";
		$this->BasePageMenuControl('edit', 'Skin.Common.edit-page');
		$this->BasePageMenuControl('add', 'Skin.Common.new-page');
		$this->BasePageMenuControl('restrict', 'Skin.Common.restrict-access');
		$this->BasePageMenuControl('attach', 'Skin.Common.attach-file');
		$this->BasePageMenuControl('move', 'Skin.Common.move-page');
		$this->BasePageMenuControl('delete', 'Skin.Common.delete-page');
		$this->BasePageMenuControl('tags', 'Skin.Common.tags-page');
    print "<li>&nbsp; &nbsp;</li>";
		print "<li><a href='" . wfMsg('controlpanellink') . "'>control panel</li>\n"; //FIXME this isn't right, actually :)
    print "<li>&nbsp; &nbsp;</li>";
		$this->BasePageMenuControl('print', 'Skin.Common.print-page');
		$this->BasePageMenuControl('email', 'Skin.Common.email-page');
    print "</ul>\n";
    print "</div>\n";
  } else { 
    print "<div class=\"wikiEdit\">\n";
    print "<ul class=\"login\">\n";
    print '<li><a href="' . $this->haveData('loginurl') . '">' . wfMsg('Page.UserLogin.page-title') . '</a></li>';
    print "</ul>\n";
    print "<ul class=\"control\">\n";
		$this->BasePageMenuControl('print', 'Skin.Common.print-page');
		$this->BasePageMenuControl('email', 'Skin.Common.email-page');
    print "</ul>\n";
    print "</div>\n";
  } ?>

    <div class="sitemap">
      <!-- FIXME should be in a wiki document? -->
      <div class="links">
        <h3>About</h3>
        <ul>
          <li><a href="/About">About Mono</a></li>
          <li><a href="/Roadmap">Roadmap</a></li>
          <li><a href="/What_is_Mono">Technologies</a></li>
          <li><a href="/Companies_Using_Mono">Success Stories</a></li>
          <li><a href="/FAQ">FAQs</a></li>
        </ul>
      </div><!--links-->
      <div class="links">
        <h3>Documentation</h3>
        <ul>
          <li><a href="/Start">Getting Started</a></li>
          <li><a href="http://go-mono.org/docs">API Reference</a></li>
          <li><a href="/Articles">Articles</a></li>
        </ul>
      </div><!--links-->
      <div class="links">
        <h3>Downloads</h3>
        <ul>
          <li><a href="/Downloads">Latest Release</a></li>
          <li><a href="http://www.monodevelop.com">MonoDevelop</a></li>
          <li><a href="/Tools">Tools</a></li>
          <li><a href="/Other_Downloads#Snapshots">Daily Snapshots</a></li>
          <li><a href="/OldReleases">Previous Releases</a></li>
        </ul>
      </div><!--links-->
      <div class="links">
        <h3>Resources</h3>
        <ul>
          <li><a href="/Forums/">Forums</a></li>
          <li><a href="/MailingLists">Mailing Lists</a></li>
          <li><a href="/IRC">Chat (IRC)</a></li>
          <li><a href="http://www.go-mono.com/monologue/">Blogs</a></li>
          <li><a href="/Contact/">Contact</a></li>
        </ul>
      </div><!--links-->
      <div class="links">
        <h3>Mono Development</h3>
        <ul>
          <li><a href="/Bugs">Report Bugs</a></li>
          <li><a href="/Contributing">Contributing</a></li>
          <li><a href="/SVN">SVN</a></li>
          <li><a href="http://mono.ximian.com/monobuild/">Build Status</a></li>
          <li><a href="/Resources#API_completion_status_pages">Class Status</a></li>
        </ul>
      </div><!--links-->
      <div class="links">
        <h3>Mono in Action</h3>
        <p>Mono 1.0 was released over three years ago, in June 2004. Since
        then, Mono has been taken up as the platform of choice for many
        open-source and commercial projects. See Mono in action:</p>
        <ul>
          <li><a href="/Screenshots">Screenshots</a></li>
          <li><a href="/Videos">Videos</a></li>
          <li><a href="/Software">Applications that use Mono</a></li>
        </ul>
      </div><!--links-->

    </div><!--sitemap-->
    <div id="copy">
      <a href="http://ww/novell.com/linux"><span id="novell-logo">Novell, Inc.</span></a>  
      <a href="http://www.mono-project.org"><span id="mono-logo">Mono</span></a>
      <div class="legal">All rights reserved. Blah blah legal speak.  Lorem
      ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus posuere, ante
      eu tempor dictum, felis nibh facilisis sem.</div>

    </div><!--copy-->
  </div><!--footer-->

  </body>
</html>
<?php
    }
    
    function BaseSiteTools($key, $languageKey) {
	    global $wgUser;
	    
		$t = Title::makeTitle( NS_SPECIAL, $key );
	    $sk = $wgUser->getSkin();
	    $href = $sk->makeSpecialUrl($key);
	    if ($key == 'Contributions') {
		    $href = $t->getLocalURL('target=' . urlencode( $wgUser->getName()));
	    }
	    elseif ($key == 'ListTemplates') {
		 	$t = Title::makeTitle('', NS_TEMPLATE);  
		 	$href = $sk->makeNSUrl('', '', NS_TEMPLATE);
	    }
	    elseif ($key == 'Listusers') {
		 	$t = Title::makeTitle('', NS_USER);  
		 	$href = $sk->makeNSUrl('', '', NS_USER);
	    }
	    else {
		    $href = $t->getLocalURL();   
	    }
	    
	 	echo("\t".'<li class="site'.ucfirst($key).'"><a href="'.$href.'" title="'. wfMsg($languageKey) .'"><span></span>'. wfMsg($languageKey) .'</a></li>'."\n");
	}
	    
    function BaseSiteSearch() {
?>	
	<div class="siteSearch">
		<fieldset class="search">
	 		<form action="<?php $this->text('searchaction') ?>">
		        <span><?php echo(wfMsg('Page.Search.search'));?> </span><input id="searchInput" class="inputText" name="search" type="text" value="<?php $this->text('search'); ?>" />
				<input type="hidden" name="type" value="fulltext" />
		        <input type="submit" name="go" class="inputSubmit" value="<?php echo wfMsg('Skin.Common.submit-find'); ?>" />
			</form>
		</fieldset>
	</div>
<?php		
	}
	
	function BaseUserAuth() {
		global $wgUser;
	?>
		<div class="userAuth">				
	 		<?php if (!$wgUser->isAnonymous()) { 
					echo '<span>'. wfMsg('Skin.Common.logged-in') . '</span>';
		  		 	echo('<a href="'.$this->haveData('userpageurl').'" class="userPage">'.$this->haveData('username').'</a>');
		 			echo('<a href="'.$this->haveData('logouturl').'" class="userLogout">'.wfMsg('Page.UserLogout.page-title').'</a>');
				} else { 
					echo '<span>'. wfMsg('Skin.Common.you-not-logged-in') . '</span>';
	 				echo('<a href="'.$this->haveData('loginurl').'" class="userLogin">'. wfMsg('Page.UserLogin.page-title') .'</a>');
	 				if ($this->hasData('registerurl')) {
		 				echo('<a href="'.$this->haveData('registerurl').'" class="userLogin">'. wfMsg('Page.UserRegister.page-title') .'</a>');	
	 				}
				}
			?>
		</div>
	<?php	
	}
	
	function BasePageMenuControl($key, $languageKey) {
		$pkey = 'page'.$key;
		$href = $this->haveHref($pkey);
		$onclick = 'menuOff(\'menuPageOptions\');'.$this->haveOnClick($pkey);
		$class = $this->haveCSSClass($pkey);
		echo("\t".'<li class="page'.ucfirst($key).'"><a href="'.$href.'"'.($class != '' ? ' class="'.$class.'"': '').' onclick="'.$onclick.'" title="'. wfMsg($languageKey) .'"><span></span>'. wfMsg($languageKey) .'</a></li>'."\n");		
	}
}
?>

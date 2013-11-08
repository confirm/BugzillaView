<?php
/**
 * @file    BugzillaView/BugzillaView.php
 *
 * @brief   Main script of BugzillaView which defines all hooks.
 *
 * @author  dbarton, confirm IT solutions GmbH
 */

/*
 * INITIALIZATION
 */

// abort if this script is called directly
if (!defined('MEDIAWIKI')) die("MediaWiki extensions cannot be run directly.");

// extension credits
$wgExtensionCredits['other'][] = array(
    'path'        => __FILE__,
    'name'        => 'BugzillaView',
    'version'     => '1.0',
    'author'      => 'dbarton, confirm IT solutions',
    'description' => 'MediaWiki extension to display Bugzilla bug details'
);

// load required files
require 'hooks.inc.php';
require 'BugzillaViewDatabase.class.php';

/*
 * DEFAULT SETTINGS
 */

$wgBugzillaView = array(
    'interwiki'          => 'bugzilla',
    'displayDescription' => TRUE,
    'dbDriver'           => $wgDBtype,
    'dbHost'             => $wgDBserver,
    'dbName'             => 'bugzilla',
    'dbUsername'         => $wgDBuser,
    'dbPassword'         => $wgDBpassword,
    'dbTablePrefix'      => ''
);

/*
 * REGISTER HOOKS
 */

$wgHooks['LinkEnd'][]           = 'bugzillaViewLinkEnd';
$wgHooks['BeforePageDisplay'][] = 'bugzillaViewBeforePageDisplay';

?>

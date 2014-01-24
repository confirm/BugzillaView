<?php
/**
* @brief Initialization file for the BugzillaView extension.
* @file BugzillaView/BugzillaView.php
* @ingroup Extensions
* 
* @version 1.0.1 - 2014-01-24
*
* @link https://www.mediawiki.org/wiki/Extension:BugzillaView Documentation
* @link https://www.mediawiki.org/wiki/Extension_talk:BugzillaView Support
* @link https://github.com/domibarton/BugzillaView Source Code
*
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
* @author Dominique Barton (dbarton), confirm IT solutions GmbH
*/

/**
 * INITIALIZATION
 */

// abort if this script is called directly
if( !defined('MEDIAWIKI' ) ) {
        die( 'This file is a MediaWiki extension. It is not a valid entry point' );
}

// extension credits
$wgExtensionCredits['other'][] = array(
    'path'           => __FILE__,
    'name'           => 'BugzillaView',
    'version'        => '1.0.1',
    'author'         => 'Dominique Barton',
    'descriptionmsg' => 'bugzillaview-desc'
);

// load required files
require 'hooks.inc.php';
$wgAutoloadClasses['BugzillaViewDatabase'] = __DIR__ . '/BugzillaViewDatabase.class.php';
$wgExtensionMessagesFiles['BugzillaView'] = __DIR__ . '/BugzillaView.i18n.php';

/**
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

/**
 * REGISTER HOOKS
 */
$wgHooks['LinkEnd'][]           = 'bugzillaViewLinkEnd';
$wgHooks['BeforePageDisplay'][] = 'bugzillaViewBeforePageDisplay';

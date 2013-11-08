<?php
/**
 * @file    BugzillaView/hooks.inc.php
 *
 * @brief   Implementation of the hook functions.
 */

/**
 * @brief   Callback function for LinkEnd hook.
 *
 * This function will reformat all links, matching the interwiki prefix defined
 * as BugzillaView link.
 *
 * @param   $skin       skin
 * @param   $target     target object
 * @param   $options    options
 * @param   $html       HTML
 * @param   $attribs    attributes
 * @param   $ret        return value
 */

function bugzillaViewLinkEnd($skin, $target, $options, &$html, &$attribs, &$ret)
{
    // globalize some vars (ugly thing!)
    global $wgBugzillaView, $wgParser, $wgScriptPath;

    // abort here, if the link is not a bugzilla interwiki link
    if(!$target->mInterwiki || $target->mInterwiki != $wgBugzillaView['interwiki'])
        return TRUE;

    // disable parser cache, to keep bugzilla interwiki links up-to-date
    $wgParser->disableCache();

    // initialize required variables
    $id   = intval($target->mTextform);
    $text = ($html != $wgBugzillaView['interwiki'].':'.$id) ? $html : '';
    $classes = 'bug';

    // get infos from bugzilla DB
    $statement = BugzillaViewDatabase::getInstance()->query('SELECT resolution, short_desc, bug_status
        FROM '.$wgBugzillaView['dbTablePrefix'].'bugs
        WHERE bug_id = "'.$id.'"
        LIMIT 1');

    // get data record from database
    $bug = $statement->fetch(PDO::FETCH_OBJ);

    // handle correct bug IDs
    if(is_object($bug))
    {
        if(strlen(trim($text)) == 0)
        {
            $text  = $id.' ';
            if($wgBugzillaView['displayDescription'])
                $text .= '<span class="bug-label-description">'.utf8_encode($bug->short_desc).'</span> ';
            $text .= '<span class="bug-label-status">'.$bug->bug_status.'</span> ';
            $text .= '<span class="bug-label-resolution">'.$bug->resolution.'</span>';
        }

        $classes .= ' bug-status-'.strtolower($bug->bug_status).' bug-resolution-'.($bug->resolution ? strtolower($bug->resolution) : 'unknown');
    }

    // handle incorrect bug IDs
    else
    {
        $text    = 'Invalid Bug #'.$id;
        $classes = ' bug-status-notfound';
    }

    // build link (image sponsored by http://www.fatcow.com/free-icons)
    $ret = Xml::openElement('a', $attribs).'<span class="bug '.$classes.'"><img src="'.$wgScriptPath.'/extensions/BugzillaView/bug.png"> '.$text.'</span>'.Xml::closeElement('a');

    // tell MW to skip any further hooks
    return FALSE;
}

/**
 * @brief   Callback function for BeforePageDisplay hook.
 *
 * This function will simply add the cascading stylesheet file to the MediaWiki
 * HTML header. The stylesheet will be used to format the bugs.
 *
 * @param   $output     output object
 * @param   $skin       skin
 */

function bugzillaViewBeforePageDisplay(&$output, &$skin)
{
    global $wgScriptPath;
    $output->addStyle($wgScriptPath.'/extensions/BugzillaView/stylesheet.css');
    return TRUE;
}
?>

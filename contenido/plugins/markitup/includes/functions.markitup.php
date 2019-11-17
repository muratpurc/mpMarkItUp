<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Contains several markItUp related helper functions.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Helper_Functions
 * @version     $Id: functions.markitup.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: functions.markitup.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');


/**
 * Stores the raw_value in content table, as a addition to CONTENIDO function
 * saveContentEntry().
 *
 * @param   int     $idArtLang  Idartlang of the article
 * @param   string  $type       Type of content element, e. g. CMS_MIUBBCODE
 * @param   int     $typeId     Serial number of the content element, e. g. the 12 in CMS_MIUBBCODE[12]
 * @param   string  $rawValue   Incomming raw markup value
 */
function markitup_saveContentEntry($idArtLang, $type, $typeId, $rawValue)
{
    global $db, $auth, $cfg, $cfgClient, $client, $lang, $_cecRegistry;

    $rawValue  = stripslashes($rawValue);
    $rawValue  = Contenido_Security::escapeDB($rawValue, $db);
    $type      = Contenido_Security::escapeDB($type, $db);
    $typeId    = (int) $typeId;
    $idArtLang = (int) $idArtLang;

    // get idtype
    $sql = "SELECT idtype FROM " . $cfg['tab']['type'] . " WHERE type='" . $type . "'";
    $db->query($sql);
    if (!$db->next_record()) {
        return;
    }
    $iIdType = (int) $db->f('idtype');

    $sql = "SELECT idartlang FROM " . $cfg['tab']['content'] . " WHERE idartlang=" . $idArtLang . " AND idtype=" . $iIdType ." AND typeid=" . $typeId;
    $db->query($sql);
    if ($db->next_record()) {
        $sql = "UPDATE " . $cfg['tab']['content'] . " SET raw_value ='" . $rawValue . "' WHERE idartlang=" . $idArtLang . " AND idtype=" . $iIdType . " AND typeid=" . $typeId;
        $db->query($sql);
    }
}


/**
 * Extracts the available content-types from the database.
 *
 * Does the same job like the function getAvailableContentTypes() from CONTENIDO, but stores the
 * markup related raw_value content in addition to the CONTENIDO function.
 *
 * Creates an array in global scope as follows:
 * - $a_content[type][number]     = content string
 * - $a_rawcontent[type][number]  = raw content string
 * - $a_description[type][number] = decription
 *
 * f.e. $a_content['CMS_HTML'][1] = content string
 *
 * @param   int  $idartlang  Language specific ID of the arcticle
 */
function markitup_getAvailableContentTypes($idartlang)
{
    global $db, $cfg, $a_content, $a_rawcontent, $a_description;

    $sql = "SELECT *
            FROM
                " . $cfg['tab']['content'] . " AS a,
                " . $cfg['tab']['art_lang'] . " AS b,
                " . $cfg['tab']['type'] . " AS c
            WHERE
                a.idtype    = c.idtype AND
                a.idartlang = b.idartlang AND
                b.idartlang = " . (int) $idartlang;

    $db->query($sql);

    while ($db->next_record()) {
        $a_content[$db->f('type')][$db->f('typeid')]     = urldecode($db->f('value'));
        $a_rawcontent[$db->f('type')][$db->f('typeid')]  = urldecode($db->f('raw_value'));
        $a_description[$db->f('type')][$db->f('typeid')] = i18n($db->f('description'));
    }
}


/**
 * Returns content of a existing cheatsheet file to desired markup.
 *
 * @param   string  $markupName  The markup name
 * @return  string  Markup cheatsheet as HTML
 */
function markitup_getSyntaxCheatsheet($markupName) {
    global $cfg, $belang;

    $filePath = $cfg['path']['contenido'] . $cfg['path']['plugins'] . 'markitup/locale/';
    $fileName = $markupName . '_syntax_cheatsheet.' . $belang . '.html';

    if (!is_file($filePath . $fileName)) {
        $fileName = $markupName . '_syntax_cheatsheet.en_US.html';
    }

    if (!is_file($filePath . $fileName)) {
        $cheatsheet = '<!-- No cheatsheet -->';
    } else {
        $cheatsheet = file_get_contents($filePath . $fileName);
    }

    return $cheatsheet;
}


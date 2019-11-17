<?php
/**
 * Plugin markItUp functions
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © Murat Purc 2008
 * @package     Contenido
 * @subpackage  markItUp
 */

 
defined('CON_FRAMEWORK') or die('Illegal call');


function markitup_saveContentEntry($iIdArtLang, $sType, $iTypeId, $sRawValue) {
    global $db, $auth, $cfg, $cfgClient, $client, $lang, $_cecRegistry;

#var_dump($sRawValue);

    $sRawValue  = stripslashes($sRawValue);
    $sRawValue  = Contenido_Security::escapeDB($sRawValue, $db);
    $sType      = Contenido_Security::escapeDB($sType, $db);
    $iTypeId    = (int) $iTypeId;
    $iIdArtLang = (int) $iIdArtLang;

#var_dump($sRawValue);

    // get idtype
    $sql = "SELECT idtype FROM " . $cfg["tab"]["type"] . " WHERE type='" . $sType . "'";
    $db->query($sql);
    if (!$db->next_record()) {
        return;
    }
    $iIdType = (int) $db->f('idtype');

    $sql = "SELECT idartlang FROM " . $cfg["tab"]["content"] . " WHERE idartlang='" . $iIdArtLang . "' AND idtype='" . $iIdType ."' AND typeid='" . $iTypeId . "'";
    $db->query($sql);
    if ($db->next_record()) {
        $sql = "UPDATE " . $cfg["tab"]["content"] . " SET raw_value ='" . $sRawValue . "' WHERE idartlang='" . $iIdArtLang . "' AND idtype='" . $iIdType . "' AND typeid='" . $iTypeId . "'";
        $db->query($sql);
    }
}


/**
 * Extracts the available content-types from the database.
 *
 * Does the same job like the function getAvailableContentTypes() from Contenido.
 *
 * Creates an array in global scope as follows:
 * - $a_content[type][number]     = content string
 * - $a_rawcontent[type][number]  = raw content string
 * - $a_description[type][number] = decription
 *
 * f.e. $a_content['CMS_HTML'][1] = content string
 *
 * @param int $idartlang Language specific ID of the arcticle
 */
function markitup_getAvailableContentTypes($idartlang) {
	global $db, $cfg, $a_content, $a_rawcontent, $a_description;

	$sql = "SELECT *
            FROM
                " . $cfg["tab"]["content"] . " AS a,
                " . $cfg["tab"]["art_lang"] . " AS b,
                " . $cfg["tab"]["type"] . " AS c
            WHERE
                a.idtype    = c.idtype AND
                a.idartlang = b.idartlang AND
                b.idartlang = '" . Contenido_Security::toInteger($idartlang) . "'";

	$db->query($sql);

	while ($db->next_record()) {
		$a_content[$db->f("type")][$db->f("typeid")]     = urldecode($db->f('value'));
		$a_rawcontent[$db->f("type")][$db->f("typeid")]  = urldecode($db->f('raw_value'));
		$a_description[$db->f("type")][$db->f("typeid")] = i18n($db->f('description'));
	}
}



function markitup_getSyntaxCheatsheet($markupName) {
    global $cfg, $belang;

    $filePath = $cfg['path']['contenido'] . $cfg['path']['plugins'] . 'markitup/locale/';
    $fileName = $markupName . '_syntax_cheatsheet.' . $belang . '.html';

    if (!is_file($filePath . $fileName)) {
        $fileName = $markupName . '_syntax_cheatsheet.en_US.html';
    }

    if (!is_file($filePath . $fileName)) {
        $cheatsheet = '<p>no cheatsheet</p>';
    } else {
        $cheatsheet = file_get_contents($filePath . $fileName);
    }
    
    return $cheatsheet;
}


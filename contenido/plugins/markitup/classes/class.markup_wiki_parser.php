<?php
/**
 * Contains Texy parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/wikiparser-1.0\class_WikiParser.php');


/**
 * Contains Wiki parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class Markup_WikiParser extends WikiParser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        parent::WikiParser();
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        return parent::parse($text);
    }

}

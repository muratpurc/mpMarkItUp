<?php
/**
 * Contains Textile parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/textile-2.0.0/classTextile.php');


/**
 * Textile markup parser class.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class Markup_TextileParser extends Textile implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        parent::Textile();
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        return $this->TextileThis($text);
    }

}

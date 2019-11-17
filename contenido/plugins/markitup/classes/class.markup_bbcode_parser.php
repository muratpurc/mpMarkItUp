<?php
/**
 * Contains BBCode parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/markitup.bbcode-parser/markitup.bbcode-parser.php');


/**
 * BBCode markup parser class.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class Markup_BBCodeParser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        // donut
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
        if (!defined('EMOTICONS_DIR')) {
            define('EMOTICONS_DIR', $this->_aCfg['emoticon_path']);
        }
    	return BBCode2Html($text);
    }

}


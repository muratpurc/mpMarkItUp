<?php
/**
 * Contains Markdown parser for markItUp! plugin.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */

defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/markdown/Markdown.php');


/**
 * Markdown markup parser class.
 *
 * @author      Murat Purc <murat@purc.de>
 * @copyright   © ww.purc.de
 * @package     Contenido
 * @subpackage  markItUp
 */
class Markup_MarkdownParser extends Markdown_Parser implements I_MarkupParser {

    private $_aCfg;


    public function __construct() {
        // donut
    }


    public function setConfig(array $config) {
        $this->_aCfg = $aCfg;
    }


    public function parse($text) {
    	return $this->transform($text);
    }

}


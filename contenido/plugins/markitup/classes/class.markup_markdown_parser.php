<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Contains Markdown parser for markItUp! plugin.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Parser
 * @version     $Id: class.markup_markdown_parser.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: class.markup_markdown_parser.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');


include_once('interface.markupparser.php');
plugin_include('markitup', 'libs/parser/markdown/Markdown.php');


/**
 * Markdown markup parser class.
 *
 * @package     Plugin_markItUp
 * @subpackage  Parser
 * @version     $Id: class.markup_markdown_parser.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
class Markup_MarkdownParser extends Markdown_Parser implements I_MarkupParser
{

    /**
     * Markdown parser settings
     * @var  array
     */
    private $_aCfg;


    /**
     * Constructor, does nothing at the moment.
     *
     * @return  void
     */
    public function __construct()
    {
        // donut
    }


    /**
     * (non-PHPdoc)
     * @see contenido/plugins/markitup/classes/I_MarkupParser#setConfig($config)
     */
    public function setConfig(array $config)
    {
        $this->_aCfg = $aCfg;
    }


    /**
     * (non-PHPdoc)
     * @see contenido/plugins/markitup/classes/I_MarkupParser#parse($text)
     */
    public function parse($text)
    {
        return $this->transform($text);
    }

}

<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Contains interface definition for markup parser.
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package     Plugin_markItUp
 * @subpackage  Parser
 * @version     $Id: interface.markupparser.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @link        http://www.purc.de
 *
 * {@internal
 *   created 2008-12-xx
 *   $Id: interface.markupparser.php 110 2010-02-16 14:28:22Z Murat $
 * }}
 */


defined('CON_FRAMEWORK') or die('Illegal call');


/**
 * Interface definition for markup parser. Each used markup parser must implement this.
 *
 * @package     Plugin_markItUp
 * @subpackage  Parser
 * @version     $Id: interface.markupparser.php 110 2010-02-16 14:28:22Z Murat $
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2008-2011 Murat Purc (http://www.purc.de)
 */
interface I_MarkupParser
{

    /**
     * Interface function to set parser configuration
     *
     * @param   array  Parser configuration
     */
    public function setConfig(array $config);

    /**
     * Interface function to parse markup content
     *
     * @param   string  $text  The markup content to parse
     * @return  string  Parsed HTML-Code
     */
    public function parse($text);

}

<?php
/**
 * MUSound.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUSound
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

/**
 * The musoundTemplateHeaders plugin performs header() operations
 * to change the content type provided to the user agent.
 *
 * Available parameters:
 *   - contentType:  Content type for corresponding http header.
 *   - asAttachment: If set to true the file will be offered for downloading.
 *   - fileName:     Name of download file.
 *
 * @param  array       $params All attributes passed to this function from the template
 * @param  Zikula_View $view   Reference to the view object
 *
 * @return boolean false
 */
function smarty_function_musoundTemplateHeaders($params, $view)
{
    if (!isset($params['contentType'])) {
        $view->trigger_error($view->__f('%1$s: missing parameter \'%2$s\'', array('musoundTemplateHeaders', 'contentType')));
    }

    // apply header
    header('Content-Type: ' . $params['contentType']);

    // if desired let the browser offer the given file as a download
    if (isset($params['asAttachment']) && $params['asAttachment']
     && isset($params['fileName']) && !empty($params['fileName'])) {
        header('Content-Disposition: attachment; filename=' . $params['fileName']);
    }

    return;
}

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
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * The musoundModerationObjects plugin determines the amount of unapproved objects.
 * It uses the same logic as the moderation block and the pending content listener.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array       $params All attributes passed to this function from the template
 * @param  Zikula_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_musoundModerationObjects($params, $view)
{
    if (!isset($params['assign']) || empty($params['assign'])) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('musoundModerationObjects', 'assign')));

        return false;
    }

    $serviceManager = $view->getServiceManager();
    $workflowHelper = new MUSound_Util_Workflow($serviceManager);

    $result = $workflowHelper->collectAmountOfModerationItems();

    $view->assign($params['assign'], $result);
}

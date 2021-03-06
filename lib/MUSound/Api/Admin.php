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
 * This is the Admin api helper class.
 */
class MUSound_Api_Admin extends MUSound_Api_Base_AbstractAdmin
{
    /**
     * Returns available admin panel links.
     *
     * @return array Array of admin links.
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'main'),
                             'text' => $this->__('Frontend'),
                             'title' => $this->__('Switch to user area.'),
                             'class' => 'z-icon-es-home');
        }

        $controllerHelper = new MUSound_Util_Controller($this->serviceManager);
        $utilArgs = array('api' => 'admin', 'action' => 'getlinks');
        $allowedObjectTypes = $controllerHelper->getObjectTypes('api', $utilArgs);
        
        if (in_array('collection', $allowedObjectTypes)
                && SecurityUtil::checkPermission($this->name . ':Collection:', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'collection')),
                    'text' => $this->__('Collections'),
                    'title' => $this->__('Collection list'));
        }
        if (in_array('album', $allowedObjectTypes)
            && SecurityUtil::checkPermission($this->name . ':Album:', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'album')),
                             'text' => $this->__('Albums'),
                             'title' => $this->__('Album list'));
        }
        if (in_array('track', $allowedObjectTypes)
            && SecurityUtil::checkPermission($this->name . ':Track:', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'track')),
                             'text' => $this->__('Tracks'),
                             'title' => $this->__('Track list'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'config'),
                             'text' => $this->__('Configuration'),
                             'title' => $this->__('Manage settings for this application'));
        }

        return $links;
    }
}

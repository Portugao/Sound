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
 * Utility implementation class for model helper methods.
 */
class MUSound_Util_Model extends MUSound_Util_Base_AbstractModel
{
    /**
     *
     This method is for getting a repository for albums
     *
     */
    public static function getAlbumRepository() 
    {
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository('MUSound_Entity_Album');

        return $repository;
    }
    
    /**
     *
     This method is for getting a repository for tracks
     *
     */
    public static function getTrackRepository()
    {
    	$serviceManager = ServiceUtil::getManager();
    	$entityManager = $serviceManager->getService('doctrine.entitymanager');
    	$repository = $entityManager->getRepository('MUSound_Entity_Track');
    
    	return $repository;
    }
}

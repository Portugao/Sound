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
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de).
 */

/**
 * Installer base class.
 */
class MUSound_Base_Installer extends Zikula_AbstractInstaller
{
    /**
     * Install the MUSound application.
     *
     * @return boolean True on success, or false.
     */
    public function install()
    {
        // Check if upload directories exist and if needed create them
        try {
            $controllerHelper = new MUSound_Util_Controller($this->serviceManager);
            $controllerHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $e) {
            return LogUtil::registerError($e->getMessage());
        }
        // create all tables from according entity definitions
        try {
            DoctrineHelper::createSchema($this->entityManager, $this->listEntityClasses());
        } catch (\Exception $e) {
            if (System::isDevelopmentMode()) {
                return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            $returnMessage = $this->__f('An error was encountered while creating the tables for the %s extension.', array($this->name));
            if (!System::isDevelopmentMode()) {
                $returnMessage .= ' ' . $this->__('Please enable the development mode by editing the /config/config.php file in order to reveal the error details.');
            }
            return LogUtil::registerError($returnMessage);
        }
    
        // set up all our vars with initial values
        $this->setVar('pageSizeCollection', 10);
        $this->setVar('pagesizeAlbum', 10);
        $this->setVar('pagesizeTrack', 20);
        $this->setVar('maxSizeCover', 102400);
        $this->setVar('maxSizeTrack', 1024000);
        $this->setVar('maxSizeZip', 1024000);
        $this->setVar('allowedExtensionCover', 'gif,jpeg,jpg,png');
        $this->setVar('allowedExtensionTrack', 'mp3');
        $this->setVar('backendWidth', 200);
        $this->setVar('backendHeight', 150);
        $this->setVar('frontendWidth', 200);
        $this->setVar('frontendHeight', 150);
        $this->setVar('backendWidth', 400);
        $this->setVar('backendHeight', 300);
        $this->setVar('frontendWidth', 400);
        $this->setVar('frontendHeight', 300);
        $this->setVar('moderationGroupForAlbums', 2);
        $this->setVar('supportedModuls', '');
        $this->setVar('useStandard', false);
    
        $categoryRegistryIdsPerEntity = array();
    
        // add default entry for category registry (property named Main)
        include_once 'modules/MUSound/lib/MUSound/Api/Base/Category.php';
        include_once 'modules/MUSound/lib/MUSound/Api/Category.php';
        $categoryApi = new MUSound_Api_Category($this->serviceManager);
        $categoryGlobal = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Global');
    
        $registryData = array();
        $registryData['modname'] = $this->name;
        $registryData['table'] = 'Album';
        $registryData['property'] = $categoryApi->getPrimaryProperty(array('ot' => 'Album'));
        $registryData['category_id'] = $categoryGlobal['id'];
        $registryData['id'] = false;
        if (!DBUtil::insertObject($registryData, 'categories_registry')) {
            LogUtil::registerError($this->__f('Error! Could not create a category registry for the %s entity.', array('album')));
        }
        $categoryRegistryIdsPerEntity['album'] = $registryData['id'];
    
        // create the default data
        $this->createDefaultData($categoryRegistryIdsPerEntity);
    
        // register persistent event handlers
        $this->registerPersistentEventHandlers();
    
        // register hook subscriber bundles
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the MUSound application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldVersion)
    {
    /*
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (\Exception $e) {
                    if (System::isDevelopmentMode()) {
                        return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while updating tables for the %s extension.', array($this->getName())));
                }
        }
    */
    
        // update successful
        return true;
    }
    
    /**
     * Uninstall MUSound.
     *
     * @return boolean True on success, false otherwise.
     */
    public function uninstall()
    {
        // delete stored object workflows
        $result = Zikula_Workflow_Util::deleteWorkflowsForModule($this->getName());
        if ($result === false) {
            return LogUtil::registerError($this->__f('An error was encountered while removing stored object workflows for the %s extension.', array($this->getName())));
        }
    
        try {
            DoctrineHelper::dropSchema($this->entityManager, $this->listEntityClasses());
        } catch (\Exception $e) {
            if (System::isDevelopmentMode()) {
                return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            return LogUtil::registerError($this->__f('An error was encountered while dropping tables for the %s extension.', array($this->name)));
        }
    
        // unregister persistent event handlers
        EventUtil::unregisterPersistentModuleHandlers($this->name);
    
        // unregister hook subscriber bundles
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());
        
    
        // remove all module vars
        $this->delVars();
    
        // remove category registry entries
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', 'modname = \'' . $this->name . '\'');
    
        // remove all thumbnails
        $manager = $this->getServiceManager()->getService('systemplugin.imagine.manager');
        $manager->setModule($this->name);
        $manager->cleanupModuleThumbs();
    
        // remind user about upload folders not being deleted
        $uploadPath = FileUtil::getDataDirectory() . '/' . $this->name . '/';
        LogUtil::registerStatus($this->__f('The upload directories at [%s] can be removed manually.', $uploadPath));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for MUSound.
     *
     * @return array list of class names.
     */
    protected function listEntityClasses()
    {
        $classNames = array();
        $classNames[] = 'MUSound_Entity_Album';
        $classNames[] = 'MUSound_Entity_AlbumCategory';
        $classNames[] = 'MUSound_Entity_Track';
        $classNames[] = 'MUSound_Entity_Collection';
    
        return $classNames;
    }
    
    /**
     * Create the default data for MUSound.
     *
     * @param array $categoryRegistryIdsPerEntity List of category registry ids.
     *
     * @return void
     */
    protected function createDefaultData($categoryRegistryIdsPerEntity)
    {
        $entityClass = 'MUSound_Entity_Album';
        $this->entityManager->getRepository($entityClass)->truncateTable();
        $entityClass = 'MUSound_Entity_Track';
        $this->entityManager->getRepository($entityClass)->truncateTable();
        $entityClass = 'MUSound_Entity_Collection';
        $this->entityManager->getRepository($entityClass)->truncateTable();
    }
    
    /**
     * Register persistent event handlers.
     * These are listeners for external events of the core and other modules.
     */
    protected function registerPersistentEventHandlers()
    {
        // core -> 
        EventUtil::registerPersistentModuleHandler('MUSound', 'api.method_not_found', array('MUSound_Listener_Core', 'apiMethodNotFound'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'core.preinit', array('MUSound_Listener_Core', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'core.init', array('MUSound_Listener_Core', 'init'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'core.postinit', array('MUSound_Listener_Core', 'postInit'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'controller.method_not_found', array('MUSound_Listener_Core', 'controllerMethodNotFound'));
    
        // front controller -> MUSound_Listener_FrontController
        EventUtil::registerPersistentModuleHandler('MUSound', 'frontcontroller.predispatch', array('MUSound_Listener_FrontController', 'preDispatch'));
    
        // installer -> MUSound_Listener_Installer
        EventUtil::registerPersistentModuleHandler('MUSound', 'installer.module.installed', array('MUSound_Listener_Installer', 'moduleInstalled'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'installer.module.upgraded', array('MUSound_Listener_Installer', 'moduleUpgraded'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'installer.module.uninstalled', array('MUSound_Listener_Installer', 'moduleUninstalled'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'installer.subscriberarea.uninstalled', array('MUSound_Listener_Installer', 'subscriberAreaUninstalled'));
    
        // modules -> MUSound_Listener_ModuleDispatch
        EventUtil::registerPersistentModuleHandler('MUSound', 'module_dispatch.postloadgeneric', array('MUSound_Listener_ModuleDispatch', 'postLoadGeneric'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module_dispatch.preexecute', array('MUSound_Listener_ModuleDispatch', 'preExecute'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module_dispatch.postexecute', array('MUSound_Listener_ModuleDispatch', 'postExecute'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module_dispatch.custom_classname', array('MUSound_Listener_ModuleDispatch', 'customClassname'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module_dispatch.service_links', array('MUSound_Listener_ModuleDispatch', 'serviceLinks'));
    
        // mailer -> MUSound_Listener_Mailer
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.mailer.api.sendmessage', array('MUSound_Listener_Mailer', 'sendMessage'));
    
        // page -> MUSound_Listener_Page
        EventUtil::registerPersistentModuleHandler('MUSound', 'pageutil.addvar_filter', array('MUSound_Listener_Page', 'pageutilAddvarFilter'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'system.outputfilter', array('MUSound_Listener_Page', 'systemOutputfilter'));
    
        // errors -> MUSound_Listener_Errors
        EventUtil::registerPersistentModuleHandler('MUSound', 'setup.errorreporting', array('MUSound_Listener_Errors', 'setupErrorReporting'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'systemerror', array('MUSound_Listener_Errors', 'systemError'));
    
        // theme -> MUSound_Listener_Theme
        EventUtil::registerPersistentModuleHandler('MUSound', 'theme.preinit', array('MUSound_Listener_Theme', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'theme.init', array('MUSound_Listener_Theme', 'init'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'theme.load_config', array('MUSound_Listener_Theme', 'loadConfig'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'theme.prefetch', array('MUSound_Listener_Theme', 'preFetch'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'theme.postfetch', array('MUSound_Listener_Theme', 'postFetch'));
    
        // view -> MUSound_Listener_View
        EventUtil::registerPersistentModuleHandler('MUSound', 'view.init', array('MUSound_Listener_View', 'init'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'view.postfetch', array('MUSound_Listener_View', 'postFetch'));
    
        // user login -> MUSound_Listener_UserLogin
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.login.started', array('MUSound_Listener_UserLogin', 'started'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.login.veto', array('MUSound_Listener_UserLogin', 'veto'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.login.succeeded', array('MUSound_Listener_UserLogin', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.login.failed', array('MUSound_Listener_UserLogin', 'failed'));
    
        // user logout -> MUSound_Listener_UserLogout
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.logout.succeeded', array('MUSound_Listener_UserLogout', 'succeeded'));
    
        // user -> MUSound_Listener_User
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.gettheme', array('MUSound_Listener_User', 'getTheme'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.account.create', array('MUSound_Listener_User', 'create'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.account.update', array('MUSound_Listener_User', 'update'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.account.delete', array('MUSound_Listener_User', 'delete'));
    
        // registration -> MUSound_Listener_UserRegistration
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.registration.started', array('MUSound_Listener_UserRegistration', 'started'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.registration.succeeded', array('MUSound_Listener_UserRegistration', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.ui.registration.failed', array('MUSound_Listener_UserRegistration', 'failed'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.registration.create', array('MUSound_Listener_UserRegistration', 'create'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.registration.update', array('MUSound_Listener_UserRegistration', 'update'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'user.registration.delete', array('MUSound_Listener_UserRegistration', 'delete'));
    
        // users module -> MUSound_Listener_Users
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.users.config.updated', array('MUSound_Listener_Users', 'configUpdated'));
    
        // group -> MUSound_Listener_Group
        EventUtil::registerPersistentModuleHandler('MUSound', 'group.create', array('MUSound_Listener_Group', 'create'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'group.update', array('MUSound_Listener_Group', 'update'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'group.delete', array('MUSound_Listener_Group', 'delete'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'group.adduser', array('MUSound_Listener_Group', 'addUser'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'group.removeuser', array('MUSound_Listener_Group', 'removeUser'));
    
        // special purposes and 3rd party api support -> MUSound_Listener_ThirdParty
        EventUtil::registerPersistentModuleHandler('MUSound', 'get.pending_content', array('MUSound_Listener_ThirdParty', 'pendingContentListener'));
        EventUtil::registerPersistentModuleHandler('MUSound', 'module.content.gettypes', array('MUSound_Listener_ThirdParty', 'contentGetTypes'));
    }
}

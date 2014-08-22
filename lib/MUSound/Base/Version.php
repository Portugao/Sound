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
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de) at Wed Aug 06 16:48:32 CEST 2014.
 */

/**
 * Version information base class.
 */
class MUSound_Base_Version extends Zikula_AbstractVersion
{
    /**
     * Retrieves meta data information for this application.
     *
     * @return array List of meta data.
     */
    public function getMetaData()
    {
        $meta = array();
        // the current module version
        $meta['version']              = '1.0.0';
        // the displayed name of the module
        $meta['displayname']          = $this->__('M u sound');
        // the module description
        $meta['description']          = $this->__('M u sound module generated by ModuleStudio 0.6.2.');
        //! url version of name, should be in lowercase without space
        $meta['url']                  = $this->__('musound');
        // core requirement
        $meta['core_min']             = '1.3.5'; // requires minimum 1.3.5
        $meta['core_max']             = '1.3.99'; // not ready for 1.4.0 yet

        // define special capabilities of this module
        $meta['capabilities'] = array(
                          HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true)/*,
                          HookUtil::PROVIDER_CAPABLE => array('enabled' => true), // TODO: see #15
                          'authentication' => array('version' => '1.0'),
                          'profile'        => array('version' => '1.0', 'anotherkey' => 'anothervalue'),
                          'message'        => array('version' => '1.0', 'anotherkey' => 'anothervalue')
*/
        );

        // permission schema
        $meta['securityschema'] = array(
            'MUSound::' => '::',
            'MUSound::Ajax' => '::',
            'MUSound:ItemListBlock:' => 'Block title::',
            'MUSound:ModerationBlock:' => 'Block title::',
            'MUSound:Album:' => 'Album ID::',
            'MUSound:Collection:Album' => 'Collection ID:Album ID:',
            'MUSound:Track:' => 'Track ID::',
            'MUSound:Album:Track' => 'Album ID:Track ID:',
            'MUSound:Collection:' => 'Collection ID::',
        );
        // DEBUG: permission schema aspect ends


        return $meta;
    }

    /**
     * Define hook subscriber bundles.
     */
    protected function setupHookBundles()
    {
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.ui_hooks.albums', 'ui_hooks', __('musound Albums Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'musound.ui_hooks.albums.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'musound.ui_hooks.albums.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'musound.ui_hooks.albums.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'musound.ui_hooks.albums.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'musound.ui_hooks.albums.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'musound.ui_hooks.albums.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'musound.ui_hooks.albums.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.filter_hooks.albums', 'filter_hooks', __('musound Albums Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'musound.filter_hooks.albums.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.ui_hooks.tracks', 'ui_hooks', __('musound Tracks Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'musound.ui_hooks.tracks.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'musound.ui_hooks.tracks.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'musound.ui_hooks.tracks.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'musound.ui_hooks.tracks.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'musound.ui_hooks.tracks.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'musound.ui_hooks.tracks.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'musound.ui_hooks.tracks.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.filter_hooks.tracks', 'filter_hooks', __('musound Tracks Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'musound.filter_hooks.tracks.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.ui_hooks.collections', 'ui_hooks', __('musound Collections Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'musound.ui_hooks.collections.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'musound.ui_hooks.collections.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'musound.ui_hooks.collections.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'musound.ui_hooks.collections.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'musound.ui_hooks.collections.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'musound.ui_hooks.collections.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'musound.ui_hooks.collections.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.musound.filter_hooks.collections', 'filter_hooks', __('musound Collections Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'musound.filter_hooks.collections.filter');
        $this->registerHookSubscriberBundle($bundle);

        
    }
}

<?php
/**
 * MUPolls.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUPolls
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

/**
 * Utility base class for translatable helper methods.
 */
class MUSound_Util_Base_Translatable extends Zikula_AbstractBase
{
    /**
     * Return list of translatable fields per entity.
     * These are required to be determined to recognize
     * that they have to be selected from according translation tables.
     *
     * @param string $objectType The currently treated object type.
     *
     * @return array list of translatable fields.
     */
    public function getTranslatableFields($objectType)
    {
        $fields = array();
        switch ($objectType) {
            case 'option':
                $fields = array(
                    array(
                        'name' => 'title',
                        'default' => $this->__('Title')
                    )
                    
                );
                break;
            case 'poll':
                $fields = array(
                    array(
                        'name' => 'title',
                        'default' => $this->__('Title')
                    ),array(
                        'name' => 'description',
                        'default' => $this->__('Description')
                    )
                    
                );
                break;
        }
    
        return $fields;
    }

    /**
     * Return list of supported languages on the current system.
     *
     * @param string $objectType The currently treated object type.
     *
     * @return array list of language codes.
     */
    public function getSupportedLanguages($objectType)
    {
        return ZLanguage::getInstalledLanguages();
    }

    /**
     * Post-processing method copying all translations to corresponding arrays.
     * This ensures easy compatibility to the Forms plugins where it
     * it is not possible yet to define sub arrays in the group attribute.
     *
     * @param string              $objectType The currently treated object type.
     * @param Zikula_EntityAccess $entity     The entity being edited.
     *
     * @return array collected translations having the language codes as keys.
     */
    public function prepareEntityForEditing($objectType, $entity)
    {
        $translations = array();
    
        // check arguments
        if (!$objectType || !$entity) {
            return $translations;
        }
    
        // check if we have translated fields registered for the given object type
        $fields = $this->getTranslatableFields($objectType);
        if (!count($fields)) {
            return $translations;
        }
    
        if (System::getVar('multilingual') != 1) {
            // Translatable extension did already fetch current translation
            return $translations;
        }
    
        // prepare form data to edit multiple translations at once
        $entityManager = $this->serviceManager->getService('doctrine.entitymanager');
    
        // get translations
        $entityClass = 'MUPolls_Entity_' . ucfirst($objectType) . 'Translation';
        $repository = $entityManager->getRepository($entityClass);
        $entityTranslations = $repository->findTranslations($entity);
    
        $supportedLanguages = $this->getSupportedLanguages($objectType);
        $currentLanguage = ZLanguage::getLanguageCode();
        foreach ($supportedLanguages as $language) {
            if ($language == $currentLanguage) {
                // Translatable extension did already fetch current translation
                continue;
            }
            $translationData = array();
            foreach ($fields as $field) {
                $translationData[$field['name'] . $language] = isset($entityTranslations[$language]) ? $entityTranslations[$language][$field['name']] : '';
            }
            // add data to collected translations
            $translations[$language] = $translationData;
        }
    
        return $translations;
    }

    /**
     * Post-editing method copying all translated fields back to their subarrays.
     * This ensures easy compatibility to the Forms plugins where it
     * it is not possible yet to define sub arrays in the group attribute.
     *
     * @param string $objectType The currently treated object type.
     * @param array  $formData   Form data containing translations.
     *
     * @return array collected translations having the language codes as keys.
     */
    public function processEntityAfterEditing($objectType, $formData)
    {
        $translations = array();
        // check arguments
        if (!$objectType || !is_array($formData)) {
            return $translations;
        }
    
        $fields = $this->getTranslatableFields($objectType);
        if (!count($fields)) {
            return $translations;
        }
    
        $useOnlyCurrentLanguage = true;
        if (System::getVar('multilingual') == 1) {
            $useOnlyCurrentLanguage = false;
            $supportedLanguages = $this->getSupportedLanguages($objectType);
            $currentLanguage = ZLanguage::getLanguageCode();
            foreach ($supportedLanguages as $language) {
                if ($language == $currentLanguage) {
                    // skip current language as this is not treated as translation on controller level
                    continue;
                }
                $translations[$language] = array('language' => $language, 'fields' => array());
                $translationData = $formData[strtolower($objectType) . $language];
                foreach ($fields as $field) {
                    $translations[$language]['fields'][$field['name']] = isset($translationData[$field['name'] . $language]) ? $translationData[$field['name'] . $language] : '';
                    unset($formData[$field['name'] . $language]);
                }
            }
        }
        if ($useOnlyCurrentLanguage === true) {
            $language = ZLanguage::getLanguageCode();
            $translations[$language] = array('language' => $language, 'fields' => array());
            $translationData = $formData[strtolower($objectType) . $language];
            foreach ($fields as $field) {
                $translations[$language]['fields'][$field['name']] = isset($translationData[$field['name'] . $language]) ? $translationData[$field['name'] . $language] : '';
                unset($formData[$field['name'] . $language]);
            }
        }
    
        return $translations;
    }
}

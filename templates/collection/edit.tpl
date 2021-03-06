{* purpose of this template: build the form to edit an instance of collection *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
{pageaddvar name='javascript' value='modules/MUSound/javascript/MUSound_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUSound/javascript/MUSound_validation.js'}

{if $mode ne 'create'}
    {gt text='Edit collection' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='edit'}
    {/if}
{elseif $mode eq 'create'}
    {gt text='Create collection' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='new'}
    {/if}
{/if}
<div class="musound-collection musound-edit">
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type=$adminPageIcon size='small' alt=$templateTitle}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {musoundFormFrame}
        {formsetinitialfocus inputId='title'}

    {formvolatile}
        {assign var='useOnlyCurrentLanguage' value=true}
        {if $modvars.ZConfig.multilingual}
            {if is_array($supportedLanguages) && count($supportedLanguages) gt 1}
                {assign var='useOnlyCurrentLanguage' value=false}
                {nocache}
                {lang assign='currentLanguage'}
                {foreach item='language' from=$supportedLanguages}
                    {if $language eq $currentLanguage}
                        <fieldset>
                            <legend>{$language|getlanguagename|safehtml}</legend>
                            
                            <div class="z-formrow">
                                {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
                                {formtextinput group='collection' id='title' mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                                {musoundValidationError id='title' class='required'}
                            </div>
                            
                            <div class="z-formrow">
                                {formlabel for='description' __text='Description' cssClass=''}
                                {formtextinput group='collection' id='description' mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                            </div>
                        </fieldset>
                    {/if}
                {/foreach}
                {foreach item='language' from=$supportedLanguages}
                    {if $language ne $currentLanguage}
                        <fieldset>
                            <legend>{$language|getlanguagename|safehtml}</legend>
                            
                            <div class="z-formrow">
                                {formlabel for="title`$language`" __text='Title' mandatorysym='1' cssClass=''}
                                {formtextinput group="collection`$language`" id="title`$language`" mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                                {musoundValidationError id="title`$language`" class='required'}
                            </div>
                            
                            <div class="z-formrow">
                                {formlabel for="description`$language`" __text='Description' cssClass=''}
                                {formtextinput group="collection`$language`" id="description`$language`" mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                            </div>
                        </fieldset>
                    {/if}
                {/foreach}
                {/nocache}
            {/if}
        {/if}
        {if $useOnlyCurrentLanguage eq true}
            {lang assign='language'}
            <fieldset>
                <legend>{$language|getlanguagename|safehtml}</legend>
                
                <div class="z-formrow">
                    {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
                    {formtextinput group='collection' id='title' mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                    {musoundValidationError id='title' class='required'}
                </div>
                
                <div class="z-formrow">
                    {formlabel for='description' __text='Description' cssClass=''}
                    {formtextinput group='collection' id='description' mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                </div>
            </fieldset>
        {/if}
    {/formvolatile}
    
    {*include file='album/Many.tpl' group='collection' alias='album' aliasReverse='collection' mandatory=false idPrefix='musoundCollection_Album' linkingItem=$collection displayMode='choices' allowEditing=true*}
    {if $mode ne 'create'}
        {include file='helper/includeStandardFieldsEdit.tpl' obj=$collection}
    {/if}
    
    {* include display hooks *}
    {if $mode ne 'create'}
        {assign var='hookId' value=$collection.id}
        {notifydisplayhooks eventname='musound.ui_hooks.collections.form_edit' id=$hookId assign='hooks'}
    {else}
        {notifydisplayhooks eventname='musound.ui_hooks.collections.form_edit' id=null assign='hooks'}
    {/if}
    {if is_array($hooks) && count($hooks)}
        {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
            {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                <fieldset>
                    {$hook}
                </fieldset>
            {/if}
        {/foreach}
    {/if}
    
    
    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatCreation' __text='Create another item after save'}
                {formcheckbox group='collection' id='repeatCreation' readOnly=false}
            </div>
        </fieldset>
    {/if}
    
    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
        {foreach item='action' from=$actions}
            {assign var='actionIdCapital' value=$action.id|@ucfirst}
            {gt text=$action.title assign='actionTitle'}
            {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
            {if $action.id eq 'delete'}
                {gt text='Really delete this collection?' assign='deleteConfirmMsg'}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
            {else}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
            {/if}
        {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel' formnovalidate='formnovalidate'}
    </div>
    {/musoundFormFrame}
{/form}
</div>
{include file="`$lct`/footer.tpl"}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='removeImageArray'}


<script type="text/javascript">
/* <![CDATA[ */
    
    var formButtons, formValidator;
    
    function handleFormButton (event) {
        var result = formValidator.validate();
        if (!result) {
            // validation error, abort form submit
            Event.stop(event);
        } else {
            // hide form buttons to prevent double submits by accident
            formButtons.each(function (btn) {
                btn.addClassName('z-hide');
            });
        }
    
        return result;
    }
    
    document.observe('dom:loaded', function() {
    
        mUMUSoundAddCommonValidationRules('collection', '{{if $mode ne 'create'}}{{$collection.id}}{{/if}}');
        {{* observe validation on button events instead of form submit to exclude the cancel command *}}
        formValidator = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = formValidator.validate();
        {{/if}}
    
        formButtons = $('{{$__formid}}').select('div.z-formbuttons input');
    
        formButtons.each(function (elem) {
            if (elem.id != 'btnCancel') {
                elem.observe('click', handleFormButton);
            }
        });
    
        Zikula.UI.Tooltips($$('.musound-form-tooltips'));
    });
/* ]]> */
</script>

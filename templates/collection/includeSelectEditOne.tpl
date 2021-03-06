{* purpose of this template: inclusion template for managing related collection *}
{if !isset($displayMode)}
    {assign var='displayMode' value='choices'}
{/if}
{if !isset($allowEditing)}
    {assign var='allowEditing' value=false}
{/if}
{if isset($panel) && $panel eq true}
    <h3 class="collection z-panel-header z-panel-indicator z-pointer">{gt text='Collection'}</h3>
    <fieldset class="collection z-panel-content" style="display: none">
{else}
    <fieldset class="collection">
{/if}
    <legend>{gt text='Collection'}</legend>
    <div class="z-formrow">
    {if $displayMode eq 'choices'}
        {formlabel for=$alias __text='Choose collection'}
        {musoundRelationSelectorList group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the collection' selectionMode='single' objectType='collection' linkingItem=$linkingItem}
    {elseif $displayMode eq 'autocomplete'}
        {assign var='createLink' value=''}
        {if $allowEditing eq true}
            {modurl modname='MUSound' type=$lct func='edit' ot='collection' forcelongurl=true assign='createLink'}
        {/if}
        {musoundRelationSelectorAutoComplete group=$group id=$alias aliasReverse=$aliasReverse mandatory=$mandatory __title='Choose the collection' selectionMode='single' objectType='collection' linkingItem=$linkingItem idPrefix=$idPrefix createLink=$createLink withImage=false}
        <div class="musound-relation-leftside">
            {if isset($linkingItem.$alias)}
                {include file='collection/includeSelectEditItemListOne.tpl' item=$linkingItem.$alias}
            {else}
                {include file='collection/includeSelectEditItemListOne.tpl'}
            {/if}
        </div>
        <br class="z-clearer" />
    {/if}
    </div>
{if isset($panel) && $panel eq true}
    </fieldset>
{else}
    </fieldset>
{/if}

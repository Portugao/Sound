{* purpose of this template: collections view json view *}
{musoundTemplateHeaders contentType='application/json'}
[
{foreach item='item' from=$items name='collections'}
    {if not $smarty.foreach.collections.first},{/if}
    {$item->toJson()}
{/foreach}
]
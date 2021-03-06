{* Purpose of this template: Display albums within an external context *}
<dl>
    {foreach item='album' from=$items}
        <dt>{$album->getTitleFromDisplayPattern()}</dt>
        {if $album.description}
            <dd>{$album.description|strip_tags|truncate:200:'&hellip;'}</dd>
        {/if}
        <dd><a href="{modurl modname='MUSound' type='user' ot='album' func='display'  id=$album.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>

{* purpose of this template: collections atom feed *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{musoundTemplateHeaders contentType='application/atom+xml'}<?xml version="1.0" encoding="{charset assign='charset'}{if $charset eq 'ISO-8859-15'}ISO-8859-1{else}{$charset}{/if}" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">{gt text='Latest collections'}</title>
    <subtitle type="text">{gt text='A direct feed showing the list of collections'} - {$modvars.ZConfig.slogan}</subtitle>
    <author>
        <name>{$modvars.ZConfig.sitename}</name>
    </author>
{assign var='amountOfItems' value=$items|@count}
{if $amountOfItems gt 0}
{capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$items[0].createdDate|dateformat:'%Y-%m-%d'}:{modurl modname='MUSound' type=$lct func='display' ot='collection' id=$items[0].id}{/capture}
    <id>{$uniqueID}</id>
    <updated>{$items[0].updatedDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
{/if}
<link rel="alternate" type="text/html" hreflang="{lang}" href="{modurl modname='MUSound' type=$lct func='main' fqurl=true}" />
<link rel="self" type="application/atom+xml" href="{php}echo substr(\System::getBaseUrl(), 0, strlen(\System::getBaseUrl())-1);{/php}{getcurrenturi}" />
<rights>Copyright (c) {php}echo date('Y');{/php}, {$baseurl}</rights>

{foreach item='collection' from=$items}
    <entry>
        <title type="html">{$collection->getTitleFromDisplayPattern()|notifyfilters:'musound.filterhook.collections'}</title>
        <link rel="alternate" type="text/html" href="{modurl modname='MUSound' type=$lct func='display' ot='collection' id=$collection.id fqurl=true}" />
        {capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$collection.createdDate|dateformat:'%Y-%m-%d'}:{modurl modname='MUSound' type=$lct func='display' ot='collection' id=$collection.id}{/capture}
        <id>{$uniqueID}</id>
        {if isset($collection.updatedDate) && $collection.updatedDate ne null}
            <updated>{$collection.updatedDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
        {/if}
        {if isset($collection.createdDate) && $collection.createdDate ne null}
            <published>{$collection.createdDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</published>
        {/if}
        {if isset($collection.createdUserId)}
            {usergetvar name='uname' uid=$collection.createdUserId assign='cr_uname'}
            {usergetvar name='name' uid=$collection.createdUserId assign='cr_name'}
            <author>
               <name>{$cr_name|default:$cr_uname}</name>
               <uri>{usergetvar name='_UYOURHOMEPAGE' uid=$collection.createdUserId assign='homepage'}{$homepage|default:'-'}</uri>
               <email>{usergetvar name='email' uid=$collection.createdUserId}</email>
            </author>
        {/if}

        <summary type="html">
            <![CDATA[
            {$collection.description|truncate:150:"&hellip;"|default:'-'}
            ]]>
        </summary>
        <content type="html">
            <![CDATA[
            {$collection.title|replace:'<br>':'<br />'}
            ]]>
        </content>
    </entry>
{/foreach}
</feed>

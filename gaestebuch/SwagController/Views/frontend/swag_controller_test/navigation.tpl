{extends file="frontend/index/index.tpl"}
{extends file="parent:frontend/index/main-navigation.tpl"}

{block name="frontend_index_navigation_categories_top_after" append}


<li class="navigation--entry {if $Controller=='SwagControllerTest'} is--active{/if}" role="menuitem">
    <a class="navigation--link" href="{url controller=SwagControllerTest action=index}" title="Guestbook" itemprop="url">
        <span itemprop="name">Guestbook</span>
    </a>
</li>
{/block}
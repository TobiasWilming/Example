{* Extend the base template to get the header, navbar etc *}
{extends file="parent:frontend/index/index.tpl"}
{block name='frontend_index_content_left'}
{/block}
{* Overwrite the main content section to add some custom content*}
{block name='frontend_index_content'}
<div class="content listing--content">
    {if $showalert}
        {include file='frontend/swag_controller_test/alert.tpl' entry=$entry alert=$alert color=$color}
    {/if}

    {if $commentsset}
        {include file='frontend/swag_controller_test/Comment.tpl' commentaries=$comment}
    {/if}

    {if $pages>1}
        <div class="listing--bottom-paging">
            {include file="frontend/listing/actions/action-pagination.tpl" sPage=$page pages=$pages baseUrl=SwagControllerTest}
        </div>
    {/if}
</div>
{/block}

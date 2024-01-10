{headScript()->appendFile('/resource/cmsAdmin/js/category/index.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.category.search.header#}</strong>
                    </div>
                    <div class="card-body">
                        {if($categorySearch)}
                            {$categorySearch}
                            <div class="clear"></div>
                        {/if}
                        {if($result)}
                            <div class="content-box-content clearfix" style="margin-top: 30px">
                                <table class="table table-striped table-sort" data-sort-url="{@module=cmsAdmin&controller=category&action=sort@}">
                                    <thead>
                                    <tr>
                                        <th>{#template.category.search.column.name#}</th>
                                        <th>{#template.category.search.column.address#}</th>
                                        <th>{#template.category.search.column.breadcrumbs#}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    {foreach $result['rows'] as $extendedCategory}
                                        {$category = $extendedCategory['category']}
                                        {$extension = $extendedCategory['extension']}
                                        {$allowed = categoryAclAllowed($category->id)}
                                        {$frontUrl = $skinset->getSkinConfigByKey($scopeName)->getFrontUrl()}
                                        {$templateConfig = $skinset->getTemplateConfigByKey($category->template)}
                                        {if $templateConfig}
                                            {$compatibleChildrenKeys = $templateConfig->getCompatibleChildrenKeys()}
                                        {else}
                                            {$compatibleChildrenKeys = []}
                                        {/if}
                                        {$nestingEnabled = $compatibleChildrenKeys|count}
                                        <tr data-id="{$category->id}">
                                            <td class="align-middle">
                                                <i class="icon-{if $nestingEnabled}folder{else}doc{/if} p-1 mr-2 {if !$category->active}alert-danger{elseif $category->visible}alert-success{else}alert-warning{/if}"></i>
                                                {if $nestingEnabled}<a href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->id}@}">{/if}
                                                    {if $category->name}{$category->name|stripTags}{else}({#template.category.index.label.default#}){/if}{if $nestingEnabled}</a>{/if}
                                                <small>
                                                    {$templateConfig = $skinset->getTemplateConfigByKey($category->template)}
                                                    ({if $templateConfig}{_($templateConfig->getName())}{/if})
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                {$category->getUri()}
                                            </td>
                                            <td class="align-middle">
                                                {foreach name="breadcrumbs" $extension['breadcrumbs'] as $breadcrumbCategory}
                                                    {if !$_breadcrumbsLast}
                                                        {if $breadcrumbCategory->name}{$breadcrumbCategory->name|stripTags}{else}({#template.category.index.label.default#}){/if}
                                                        &gt;
                                                    {else}
                                                        {if $breadcrumbCategory->name}{$breadcrumbCategory->name|stripTags}{else}({#template.category.index.label.default#}){/if}
                                                    {/if}
                                                {/foreach}
                                            </td>
                                            <td align="right" {if !$allowed}class="inactive"{/if}>
                                                {if $allowed}
                                                    <a class="button btn btn-primary btn-inline-block" title="{#template.category.index.edit#}" href="{@module=cmsAdmin&controller=category&action=edit&id={$category->id}&force=1@}">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                    {if $category->active}
                                                        <a class="button btn btn-primary btn-inline-block" title="{#template.category.index.show#}" href="{$frontUrl}/{$category->getUri()}" target="_blank">
                                                            <i class="icon-globe"></i>
                                                        </a>
                                                    {else}
                                                        <a class="button btn btn-primary btn-inline-block" title="{#template.category.index.preview#}" href="{@module=cmsAdmin&controller=category&action=preview&id={$category->id}@}" target="_blank">
                                                            <i class="icon-eyeglass"></i>
                                                        </a>
                                                    {/if}
                                                {/if}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <div>{$paginator}</div>
                            <div class="clear"></div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

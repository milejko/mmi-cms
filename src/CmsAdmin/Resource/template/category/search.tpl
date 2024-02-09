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
                            <b>Znaleziono: {$result['totalCount']}</b>
                            <div class="content-box-content clearfix" style="margin-top: 30px">
                                <table class="table table-striped table-sort" data-sort-url="{@module=cmsAdmin&controller=category&action=sort@}">
                                    <thead>
                                    <tr>
                                        <th style="width: 100px;"></th>
                                        <th>{#template.category.search.column.name#}</th>
                                        <th>{#template.category.search.column.breadcrumbs#}</th>
                                        <th>{#template.category.search.column.address#}</th>
                                        <th>{#template.category.search.column.dateModify#}</th>
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
                                            <td class="align-middle text-center">
                                                <i class="icon-{$templateConfig::ICON} p-1 {if !$category->active}alert-danger{elseif $category->visible}alert-success{else}alert-warning{/if}"></i>
                                                <br/>
                                                <small>
                                                    {if $templateConfig}{_($templateConfig->getName())}{/if}
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                {if $nestingEnabled}<a href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->id}@}">{/if}
                                                    {if $category->name}{$category->name|stripTags}{else}({#template.category.index.label.default#}){/if}{if $nestingEnabled}</a>{/if}
                                            </td>
                                            <td class="align-middle">
                                                <i class="icon-home"></i>
                                                {foreach name="breadcrumbs" $extension['breadcrumbs'] as $breadcrumbCategory}
                                                    > {if $breadcrumbCategory->name}{$breadcrumbCategory->name|stripTags}{else}({#template.category.index.label.default#}){/if}
                                                {/foreach}
                                            </td>
                                            <td class="align-middle">
                                                {$category->getUri()}
                                            </td>
                                            <td class="align-middle">
                                                {$category->dateModify}
                                            </td>
                                            <td align="right" {if !$allowed}class="inactive"{/if}>
                                                {if $allowed}
                                                    <a class="button btn btn-primary btn-inline-block" title="{#template.category.index.goToManagement#}" href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->parentId}&highlight={$category->id}@}">
                                                        <i class="icon-target"></i>
                                                    </a>
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

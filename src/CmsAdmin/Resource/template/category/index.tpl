{headScript()->appendFile('/resource/cmsAdmin/js/category/index.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.category.index.header#}</strong>
                    </div>
                    <div class="card-body">
                        <div class="content-box-content clearfix">
                            <div class="available-templates" style="overflow-x: auto; white-space:nowrap;">
                                <a class="button btn btn-primary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=edit&parentId={$request->parentId}@}">
                                    <i class="icon-plus"></i> Folder
                                </a>
                                {foreach $skinset->getSkins() as $skin}
                                    {foreach $skin->getTemplates() as $template}
                                        <a class="button btn btn-primary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=edit&parentId={$request->parentId}&template={$skin->getKey()}/{$template->getKey()}@}">
                                            <i class="icon-plus"></i> {$template->getName()}
                                        </a>
                                    {/foreach}
                                {/foreach}

                            </div>
                            <br />
                            <table class="table table-striped table-sort" data-sort-url="{@module=cmsAdmin&controller=category&action=sort@}">
                                <thead>
                                {if $breadcrumbs}
                                    <tr>
                                        <th colspan="2">
                                            <a href="{@module=cmsAdmin&controller=category&action=index@}"><i class="icon-home"></i></a> <i class="icon-arrow-right small"></i>
                                            {foreach name="breadcrumbs" $breadcrumbs as $breadcrumbCategory}
                                                {if !$_breadcrumbsLast} 
                                                    <a href="{@module=cmsAdmin&controller=category&action=index&parentId={$breadcrumbCategory->id}@}">
                                                        {if $breadcrumbCategory->name}{$breadcrumbCategory->name}{else}({#template.category.index.label.default#}){/if}
                                                    </a> <i class="icon-arrow-right small"></i>
                                                {else}
                                                    {if $breadcrumbCategory->name}{$breadcrumbCategory->name}{else}({#template.category.index.label.default#}){/if}
                                                {/if}
                                            {/foreach}
                                        </th>
                                    </tr>
                                {else}
                                    <tr>
                                        <th colspan="2"><i class="icon-home"></i></th>
                                    </tr>
                                {/if}
                                </thead>
                                <tbody class="ui-sortable">
                                {foreach $categories as $category}
                                    <tr data-id="{$category->id}">
                                        <td style="vertical-align: middle;">
                                            {if !$category->template}
                                                <i class="icon-folder"></i>
                                                <a href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->id}@}">
                                            {else}
                                                <i class="icon-doc"></i>
                                                <a href="{@module=cmsAdmin&controller=category&action=edit&id={$category->id}@}">
                                            {/if}
                                                {if !$category->active}<i class="icon-close"></i>{/if}
                                                {if $category->name}{$category->name}{else}({#template.category.index.label.default#}){/if}
                                            </a>
                                        </td>
                                        <td align="right">
                                            <a class="button btn btn-primary btn-inline-block operation-button sort-row ui-sortable-handle" href="#">
                                                <i class="icon-cursor-move"></i>
                                            </a>
                                            {if !$category->template}
                                                <a class="button btn btn-secondary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=edit&id={$category->id}@}">
                                                    <i class="icon-pencil"></i>
                                                </a>
                                            {/if}
                                            {if $category->template}
                                                <a class="button btn btn-secondary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=copy&id={$category->id}@}">
                                                    <i class="icon-docs"></i>
                                                </a>
                                            {/if}
                                            <a class="button btn btn-secondary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=move&id={$category->id}@}">
                                                <i class="icon-share-alt"></i>
                                            </a>
                                            <a class="button btn btn-danger btn-inline-block confirm" title="{if $category->template}{#template.category.index.delete.page#}{else}{#template.category.index.delete.folder#}{/if}" href="{@module=cmsAdmin&controller=category&action=delete&id={$category->id}@}">
                                                <i class="icon-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

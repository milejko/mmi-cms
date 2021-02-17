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
                            <table class="table table-striped">
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
                                {foreach $categories as $category}
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            {if !$category->template}
                                                <a href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->id}@}">
                                                    <i class="icon-folder"></i>
                                                    {if !$category->active}<i class="icon-close"></i>{/if}
                                                    {if $category->name}{$category->name}{else}({#template.category.index.label.default#}){/if}
                                                </a>
                                            {else}
                                                    <i class="icon-doc"></i>
                                                    {if !$category->active}<i class="icon-close"></i>{/if}
                                                    {if $category->name}{$category->name}{else}({#template.category.index.label.default#}){/if}
                                            {/if}                                            
                                        </td>
                                        <td align="right">
                                            <a class="button btn btn-primary btn-inline-block" href="{@module=cmsAdmin&controller=category&action=edit&id={$category->id}@}"><i class="icon-pencil"></i> edycja</a>
                                            <a class="button btn btn-primary btn-inline-block" href="#"><i class="icon-cursor-move"></i></a>
                                            <a class="button btn btn-danger btn-inline-block confirm" title="?" href="{@module=cmsAdmin&controller=category&action=delete&id={$category->id}@}"><i class="icon-trash"></i></a>
                                            <a class="button btn btn-secondary btn-inline-block" href=""><i class="icon-share-alt"></i></a>
                                            <a class="button btn btn-secondary btn-inline-block" href=""><i class="icon-docs"></i></a>
                                        
                                        </td>
                                    </tr>
                                {/foreach}
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

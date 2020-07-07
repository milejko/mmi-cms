<div class="floating-buttons">
    {if $category}
        <a href="{@module=cmsAdmin&controller=category&action=edit&id={$category->id}&originalId={$category->cmsCategoryOriginalId}&uploaderId={$category->id}@}" style="color: #fff;" class="btn btn-secondary confirm" title="{#template.category.edit.cancel.alert#}">
            <i class="icon-close"></i>
            {#template.category.edit.cancel#}
        </a>
    {/if}
    {'cmsAdmin/form/element/submit'}
</div>
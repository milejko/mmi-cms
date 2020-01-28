<div class="card boxSection" id="widget-section-4" data-id="{$section->id}" data-required="{$section->required}">
    <div class="card-header">
        <strong>{$section->name}</strong>
    </div>
    <div class="card-body" id="widget-list-container" data-category-id="33107">
            <div style="overflow-x: auto; white-space:nowrap;">
                {foreach $section->getCompatibleWidgets() as $compatibleWidget}
                    <button id="cmsadmin-form-category-submit" type="submit" class="button btn btn-primary btn-inline-block" name="cmsadmin-form-category[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&widgetId={$compatibleWidget->id}&action=config&categoryId={$category->id}&uploaderId={$request->uploaderId}&originalId={$category->cmsCategoryOriginalId}&sectionId={$section->id}@}">
                        <i class="icon-plus"></i> {$compatibleWidget->name}
                    </button>
                {/foreach}
            </div>
    </div>
</div>
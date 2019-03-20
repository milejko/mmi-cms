{$attributes = $category->getAttributeValues()}

<h1>{$category->name}</h1>

{*listing atrybutów*}
<h2>{#template.category.article.attributes#}:</h2>
{foreach $attributes as $attribute}
    {if $attribute instanceof \Mmi\Orm\RecordCollection}
        {foreach $attribute as $value}
            {if $value instanceof \Cms\Orm\CmsFileRecord}
                <img src="{thumb($value, 'scalecrop', '160x100')}" alt="" />
            {else}
                {$value}
            {/if}
        {/foreach}
    {else}
        {$attribute}
    {/if}
{/foreach}

{foreach $category->getWidgetModel()->getWidgetRelations() as $widgetRelation}
{if !$widgetRelation->active}{continue}{/if}
{if $widgetRelation->active == 2 && !$request->preview}{continue}{/if}
{$widgetRequest = $widgetRelation->getWidgetRecord()->getMvcParamsAsRequest()}
{$widgetName = $widgetRelation->getWidgetRecord()->name}
<div class="cms-widget widget-{$widgetRelation->getWidgetRecord()->id} widget-{$widgetName|url}">
    {categoryWidget($widgetRelation)}
</div>
{/foreach}
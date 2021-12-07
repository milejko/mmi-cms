{headScript()->appendFile('/resource/cmsAdmin/js/select-color.js')}
{headLink()->appendStylesheet('/resource/cmsAdmin/css/select-color.css')}
<div class="select-color" data-for-id="{$_element->getId()}">
    {foreach name=colors $_element->getMultioptions() as $key => $option}
    <div class="color{if $key == $_element->getValue()} selected{/if}" data-color-class="{$key}">
        <div class="color-label" style="background-color: #{$option}">
        {$_colorsIndex}
        </div>
    </div>
    {/foreach}
</div>
<input type="hidden" {$_htmlOptions} value="{$_element->getValue()}">
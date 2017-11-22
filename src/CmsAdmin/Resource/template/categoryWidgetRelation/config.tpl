{headScript()->appendFile('/resource/cmsAdmin/js/tabs-legacy.js')}
<div class="row">
    <div class="col-md-12">
        <div class="card mt-4">
            <div class="card-header">
                <strong>{if $widgetRecord}{$widgetRecord->name} - {/if}{#konfiguracja#}</strong>
            </div>
            <div class="card-body">
                {if $widgetRelationForm}
                {$widgetRelationForm}
                {else}
                    {* Przeładowanie widgetów *}
                    {headScript()->appendFile('/resource/cmsAdmin/js/window-messenger.js')}
                {/if}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header">
                <strong>{if $widgetRecord}{$widgetRecord->name} - {/if}{#konfiguracja#}</strong>
            </div>
            <div class="card-body">
                {if $widgetRelationForm}
                {$widgetRelationForm}
                {else}
                {* Przeładowanie widgetów *}
                <script>
                    window.opener.CMS.category().reloadWidgets();
                    window.close();
                </script>
                {/if}
            </div>
        </div>
    </div>
</div>

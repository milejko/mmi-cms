{headScript()->appendFile('/resource/cmsAdmin/js/connector.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Import plików#}</strong>
                    </div>
                    <div class="card-body">
                        {$form}
                        {if $files}
                        
                        <div class="auto-download list-group" data-url="{$downloadUrl}">
                            {foreach $files as $name => $label}
                                <a href="#" data-name="{$name}" class="list-group-item list-group-item-action">{$label}</a>
                            {/foreach}
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
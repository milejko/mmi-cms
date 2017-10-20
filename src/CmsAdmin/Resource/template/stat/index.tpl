{headScript()->appendFile('/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/flot.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/tooltip.js')}
{headLink()->appendStylesheet('/resource/cmsAdmin/css/stat.css')}
<script type="text/javascript">
    document.onreadystatechange = function () {
        if (document.readyState === 'complete') {
            {$dailyChart}
            {$monthlyChart}
            {$yearlyChart}
            {$avgHourlyChart}
            {$avgHourlyAllChart}
        }
    };
</script>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $label}{$label->label}{else}{#Statystyki#}{/if}{if $label}{/if}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                {$objectForm}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {if $label}
                                <p>{$label->description}</p>
                                {else}
                                <p>{#Ustaw parametry by przeglądać statystyki#}.</p>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{if $label}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Statystyki dzienne#}</strong>
                    </div>
                    <div class="card-body">
                        <div id="dailyChart" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Statystyki miesięczne#} / {#roczne#}</strong>
                    </div>
                    <div class="card-body">
                        <div id="monthlyChart" class="chart"></div>
                        <div id="yearlyChart" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Rozkład godzinowy bieżący miesiąc#} / {#rozkład godzinowy od początku#}</strong>
                    </div>
                    <div class="card-body">
                        <div id="avgHourlyChart" class="chart"></div>
                        <div id="avgHourlyAllChart" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
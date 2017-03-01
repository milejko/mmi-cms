{headScript()->appendFile('/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/flot.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/tooltip.js')}
{headLink()->appendStylesheet('/resource/cmsAdmin/css/stat.css')}
<script type="text/javascript">
	{$dailyChart}
	{$monthlyChart}
	{$yearlyChart}
	{$avgHourlyChart}
	{$avgHourlyAllChart}
</script>
<div class="content-box">
	<div class="content-box-header">
		<h3 class="charts">{if $label}{$label->label}{else}{#Statystyki#}{/if}{if $label}{/if}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{$objectForm}
		<div class="clear"></div>
		{if $label}
			<p>{$label->description}</p>
		{else}
			<p>{#Ustaw parametry by przeglądać statystyki#}.</p>
		{/if}
	</div>
</div>
{if $label}
	<div class="content-box">
		<div class="content-box-header">
			<h3>{#Statystyki dzienne#}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			<div id="dailyChart" class="chart"></div>
		</div>
	</div>

	<div class="content-box">
		<div class="content-box-header">
			<h3>{#Statystyki miesięczne#} / {#roczne#}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			<div id="monthlyChart" class="chart"></div>
			<div id="yearlyChart" class="chart"></div>
		</div>
	</div>

	<div class="content-box">
		<div class="content-box-header">
			<h3>{#Rozkład godzinowy bieżący miesiąc#} / {#rozkład godzinowy od początku#}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			<div id="avgHourlyChart" class="chart"></div>
			<div id="avgHourlyAllChart" class="chart"></div>
		</div>
	</div>
{/if}
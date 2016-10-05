<div class="content-box">
	<div class="content-box-header">
		<h3>{#Konfiguracja widgeta#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{if $widgetConfigForm}
			{$widgetConfigForm}
		{else}
			{* Przeładowanie widgetów *}
			<script>
				window.opener.CMS.category().reloadWidgets();
				window.close();
			</script>
		{/if}
		<div class="cl"></div>
	</div>
</div>

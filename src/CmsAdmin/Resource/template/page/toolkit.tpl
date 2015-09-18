<div class="cms-page-composer-toolkit">
	Ustaw opcje i przeciągnij:
    <div class="template drag-section">{#Wiersz#}</div>
	<input type="radio" name="wrapper" class="option-section" value="full-wrapper">Pełna szerokość
	<br />
	<input type="radio" name="wrapper" class="option-section" value="fluid-wrapper">Pływająca szerokość
    <div class="template drag-placeholder" options="">{#Kolumna#}</div>
	<input type="checkbox" name="stretch" value="stretch">Rozciągliwe
	<br />
	<div style="text-align: center">
		<input type="radio" name="align" value="top-l">
		<input type="radio" name="align" value="top">
		<input type="radio" name="align" value="top-r">
		<br />
		<input type="radio" name="align" value="center-l">
		<input type="radio" name="align" value="center">
		<input type="radio" name="align" value="center-r">
		<br />
		<input type="radio" name="align" value="bottom-l">
		<input type="radio" name="align" value="bottom">
		<input type="radio" name="align" value="bottom-r">
	</div>
	<hr />
	{foreach $widgets as $widget}
		<div class="template drag-widget" data-widget="'{$widget->module}', '{$widget->controller}', '{$widget->action}', array({$widget->params})">
			{$widget->name}
		</div>
	{/foreach}
	<button class="preview">{#podgląd#}</button> 
	<button class="save">{#zapisz#}</button> 
</div>
<div class="cms-page-composer-compilation"></div>
<div class="cms-page-composer-configurator">
	<div class="box"></div>
</div>
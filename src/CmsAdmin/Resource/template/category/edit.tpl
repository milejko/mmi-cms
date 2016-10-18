{headLink()->appendStyleSheet($baseUrl . '/resource/cmsAdmin/css/category.css')}
{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js')}
{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/category.js')}
<div class="content-box">
	<div class="content-box-header">
		<h3>{#Zarządzanie treścią#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		<div id="categoryTreeContainer">
			<div id="jstree">
				{jsTree([], $baseUrl . '/resource/cmsAdmin/js/tree.js')}
			</div>
		</div>
		<div id="categoryNodeContainer">
			<div id="categoryMessageContainer"></div>
			<div id="categoryContentContainer">
				{if $categoryForm}
					<ul class="tabs">
						<li>
							<a href="#tab-config">Konfiguracja</a>
						</li>
						<li>
							<a href="#tab-content">Tagi i atrybuty</a>
						</li>
						<li>
							<a href="#tab-seo">SEO</a>
						</li>
						<li>
							<a href="#tab-advanced">Zaawansowane</a>
						</li>
						<li>
							<a href="#tab-widget">Widgety</a>
						</li>
						<li>
							<a class="reload-preview" href="#tab-preview">Podgląd</a>
						</li>
					</ul>
					{$categoryForm->start()}
					<div class="tab-content clearfix" id="tab-config">
						{$categoryForm->getElement('cmsCategoryTypeId')}
						{$categoryForm->getElement('cmsCategoryTypeChanged')}
						{$categoryForm->getElement('name')}
						{$categoryForm->getElement('dateStart')}
						{$categoryForm->getElement('dateEnd')}
						{$categoryForm->getElement('active')}
						{$categoryForm->getElement('submit1')}
					</div>
					<div class="tab-content clearfix" id="tab-seo">
						{$categoryForm->getElement('title')}
						{$categoryForm->getElement('description')}
						{$categoryForm->getElement('customUri')}
						{$categoryForm->getElement('follow')}
						{$categoryForm->getElement('submit2')}
					</div>
					<div class="tab-content clearfix" id="tab-content">
						{foreach $categoryForm->getElements() as $element}
						{if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
						{$element}
					{/foreach}
					{$categoryForm->getElement('tags')}
					{$categoryForm->getElement('submit3')}
				</div>
				<div class="tab-content clearfix" id="tab-advanced">
					{$categoryForm->getElement('redirectUri')}
					{$categoryForm->getElement('mvcParams')}
					{$categoryForm->getElement('https')}
					{$categoryForm->getElement('blank')}
					{$categoryForm->getElement('submit4')}
				</div>
				{$categoryForm->end()}
				{$categoryId = $categoryForm->getRecord()->id}
				<div class="tab-content clearfix" id="tab-widget">
					<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=add&id={$categoryId}@}" class="button new-window" target="_blank"><i class="icon-plus"></i> dodaj widget</a>
					<div id="widget-list-container" data-category-id="{$categoryId}">
						{widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $categoryId])}
					</div>
				</div>
				<div class="tab-content clearfix" id="tab-preview">
					<iframe onload="this.style.height = 0;
							this.style.height = this.contentWindow.document.body.scrollHeight + 'px';" id="preview-frame" src="{if $categoryForm->getRecord()->customUri}{@module=cms&controller=category&action=dispatch&uri={$categoryForm->getRecord()->customUri}@}{else}{@module=cms&controller=category&action=dispatch&uri={$categoryForm->getRecord()->uri}@}{/if}"></iframe>
				</div>
			{/if}
		</div>
	</div>
	<div class="cl"></div>
</div>
</div>
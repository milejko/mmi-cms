{headLink()->appendStyleSheet($baseUrl . '/resource/cmsAdmin/css/category.css')}
{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/tiny/tinymce.min.js')}
<div class="content-box">
	<div class="content-box-header">
		<h3>{#Zarządzanie treścią#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		<div id="categoryTreeContainer">
			<div id="jstree">
				{jsTree([], $baseUrl . '/resource/cmsAdmin/js/category.js')}
			</div>
		</div>
		<div id="categoryNodeContainer">
			<div id="categoryMessageContainer"></div>
			<div id="categoryContentContainer">
				<ul class="tabs">
					<li>
						<a href="#tab-config">Konfiguracja</a>
					</li>
					<li>
						<a href="#tab-section">Sekcje</a>
					</li>
				</ul>
				<div class="tab-content clearfix" id="tab-config">
					{$categoryForm}
				</div>
				<div class="tab-content clearfix" id="tab-section">
					<a class="button" href="{@module=cmsAdmin&controller=category&action=index&addsection=1&id={$request->id}@}#tab-section"><i class="icon-plus"></i> dodaj sekcję</a>
				</div>
			</div>
		</div>
		<div class="cl"></div>
	</div>
</div>
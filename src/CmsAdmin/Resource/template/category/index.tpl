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
				{$categoryForm}
			</div>
		</div>
		<div class="cl"></div>
	</div>
</div>
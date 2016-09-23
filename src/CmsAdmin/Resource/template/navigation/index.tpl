{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js')}
{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js')}
{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/navigation.js')}
<div class="content-box">
	<div class="content-box-header">
		<h3>{#Menu serwisu#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{if $navigation}
			{if $navigation.parents}
				{foreach $navigation['parents'] as $child}
					<a href="{url(array('id' => $child['id']))}">{if $child.label}{$child.label}{else}{#Katalog główny#}{/if}</a> &raquo;
				{/foreach}
				{if system_isset($navigation.label)}
					{$navigation.label}
				{/if}
			{else}
				{#Katalog główny#}
			{/if}
			{if !php_empty($navigation.children)}
				<ul class="list" id="navigation-list">
					{foreach $navigation.children as $id => $child}
						<li id="navigation-item-{$id}" class="navigation-{$child.type}">
							<div>
								{if $child['lang']}{$child['lang']|uppercase}{/if}
								<i class="icon-{if $child.type == 'folder'}folder-close{elseif $child.type == 'simple'}book{elseif $child.type == 'link'}globe{else}cogs{/if}"></i>
								<a href="{url(array('id' => $child['id']))}">{$child.label}</a>
							</div>
							<div>{if $child.disabled}<i class="icon-minus-sign"></i> {#wyłączony#}{else}{if $child.visible==1}<i class=" icon-eye-open"></i> {#widoczny#}{else}<i class=" icon-eye-close"></i> {#ukryty#}{/if}{/if}</div>
							<a href="{@module=cmsAdmin&controller=navigation&action=edit&action=edit&type={$child.type}&id={$child.id}@}" class="button edit"><i class="icon-edit"></i> {#edytuj#}</a>
							<a href="{@module=cmsAdmin&controller=navigation&action=edit&action=delete&id={$child.id}@}" class="button delete confirm" title="{#Czy na pewno usunąć pozycję menu wraz z podmenu#}?"><i class="icon-remove-sign"></i> {#usuń#}</a>
						</li>
					{/foreach}
				</ul>
			{else}
				<p>{#Brak dodanych elementów menu#}.</p>
			{/if}
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=cms&parent={$navigation.id}@}"><i class="icon-cogs"></i> {#dodaj obiekt cms#}</a>
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=link&parent={$navigation.id}@}"><i class="icon-globe"></i> {#dodaj link#}</a>
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=folder&parent={$navigation.id}@}"><i class="icon-folder-close"></i> {#dodaj folder#}</a>
		{else}
			<p>{#Brak dodanych elementów menu#}.</p>
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=cms&parent=0@}"><i class="icon-cogs"></i> {#dodaj obiekt cms#}</a>
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=link&parent=0@}"><i class="icon-globe"></i> {#dodaj link#}</a>
			<a class="button add" href="{@module=cmsAdmin&controller=navigation&action=edit&type=folder&parent=0@}"><i class="icon-folder-close"></i> {#dodaj folder#}</a>
		{/if}
	</div>
</div>
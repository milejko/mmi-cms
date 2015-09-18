{headScript()->appendFile($baseUrl . '/resource/cmsAdmin/js/acl.js')}
<div class="content-box column-left">
	<div class="content-box-header">
		<h3>{#Role użytkowników#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		<ul id="roles-list">
			{foreach $roles as $role}
				<li>
					{if $request->roleId && $request->roleId == $role->id}
						{$roleName=$role->name}
						{$role->name}
					{else}
						<a href="{@module=cmsAdmin&controller=acl&action=index&roleId={$role->id}@}">{$role->name}</a>
					{/if}
				</li>
			{/foreach}
		</ul>
		{$roleForm}
	</div>
</div>

{if $request->roleId}
	<div class="content-box column-right">
		<div class="content-box-header">
			<h3>{#Uprawnienia roli#} {$roleName}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			<div id="rules">
				<table class="grid striped">
					<tr>
						<th>{#Lp#}.</th>
						<th>{#zasób#}</th>
						<th>{#polityka#}</th>
						<th>{#operacje#}</th>
					</tr>
					{$i=1}
					{foreach $rules as $ruleId => $rule}
						<tr id="rule-row-{$rule->id}">
							<td>
								{$i}{$i++}
							</td>
							<td>
								<select class="rule-select" id="rule-resource-{$rule->id}">
									{foreach $options as $key => $option}
										<option value="{$key}" {if php_strtolower($key) == $ruleId}selected=""{/if}>{$option}</option>
									{/foreach}
								</select>
							</td>
							<td>
								<select class="rule-select policy {if ($rule->access == 'allow')}allow{else}deny{/if}" id="rule-policy-{$rule->id}">
									<option value="allow"{if 'allow' == $rule->access} selected=""{/if}>{#dozwolone#}</option>
									<option value="deny"{if 'allow' != $rule->access} selected=""{/if}>{#zabronione#}</option>
								</select>
							</td>
							<td>
								<a id="rule-remove-{$rule->id}" class="remove-rule confirm" title="{#Czy na pewno chcesz usunąć tę regułę#}" href="#"><i class="icon-remove-circle"></i></a>
							</td>
						</tr>
					{/foreach}
				</table>
				<br /> <br />
				<h5>{#Nowa reguła#}:</h5>
				{$aclForm}
			</div>
		</div>
	</div>
{/if}
<div class="clear"></div>
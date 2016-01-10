<div class="content-box">
	<div class="content-box-header">
		<h3>
			{#Administracja#}
		</h3>
	</div>
	<div class="content-box-content clearfix">
		<h5>{#Twoje dane#}:</h5>
		{if $user}
		<table class="striped grid">
				<tr>
					<th>{#ID#}</th><td>#{$user->id}</td>
				</tr>
				<tr>
					<th>{#Login#}</th><td>{$user->username} (<a href="{@module=cmsAdmin&controller=index&action=logout@}">wyloguj</a>)</td>
				</tr>
				<tr>
					<th>{#E-mail#}</th><td>{$user->email}</td>
				</tr>
				<tr>
					{$passwordHash = $user->password|length}
					<th>{#Hasło#}</th><td>{if $passwordHash > 0}{#zakodowane#} - {$user->password|length} {#znaków#}{else}LDAP{/if} (<a href="{@module=cmsAdmin&controller=index&action=password@}">zmiana hasła</a>)</td>
				</tr>
				<tr>
					<th>{#Ilość błędnych logowań#}</th><td>{$user->failLogCount}</td>
				</tr>
				<tr>
					<th>{#Data i IP ostatniego poprawnego logowania#}</th><td>{$user->lastLog}<br />[{$user->lastIp}]</td>
				</tr>
				<tr>
					<th>{#Data i IP ostatniego błędnego logowania#}</th><td>{$user->lastFailLog}<br />[{$user->lastFailIp}]</td>
				</tr>
		</table>
		{/if}
	</div>
</div>
/**
 * Obsługa drzewka kategorii CMS
 */

var request = request || {};
//konfiguracja
var CATEGORYCONF = CATEGORYCONF || {};
//klucz do stanu drzewka
CATEGORYCONF.stateKey = 'cms-category-jstree';
//ile czekać na przeładowanie strony
CATEGORYCONF.delay = 200;

//zarządzanie kategoriami
$(document).ready(function () {
	$('#jstree').jstree({
        'core': {
			'data': {
				'url': request.baseUrl + '/cmsAdmin/category/node',
				'data': function (node) {
					return {'parentId': node.id};
				}
			},
            'themes': {
                'name': 'default',
				'variant': 'small',
				'responsive' : true,
				'stripes' : true
            },
			'strings': {
				'New node': 'Nowa kategoria',
				'Loading ...': 'Ładowanie ...'
			},
			'multiple': false,
			'expand_selected_onload': true,
            'check_callback': function (op) {
				if (op === 'delete_node') {
					return confirm("Czy na pewno usunąć kategorię?");
				}
				return true;
			}
        },
		'state' : {
			'key' : CATEGORYCONF.stateKey
		},
		'unique' : {
			'duplicate': function (name, counter) {
				return name + ' ' + counter;
			}
		},
		'types': {
			'#': { 'valid_children': ["root"] },
			'root': { 'valid_children': ["default"], 'icon': request.baseUrl + '/resource/cmsAdmin/images/tree.png' },
			'default': { 'valid_children': ["default"] }
		},
		'contextmenu': {
			'items': function (node) {
				var tmp = $.jstree.defaults.contextmenu.items();
				delete tmp.ccp;
				tmp.create.label = "Utwórz podkategorię";
				tmp.rename.label = "Zmień nazwę";
				tmp.remove.label = "Usuń";
				if (this.get_type(node) === "root") {
					delete tmp.rename;
					delete tmp.remove;
					tmp.create.separator_after = false;
					tmp.create.label = "Utwórz kategorię bazową";
				}
				return tmp;
			}
		},
		'plugins' : [ "state", "unique", "types", "contextmenu", "dnd", "wholerow" ]
    })
	.on('delete_node.jstree', function (e, data) {
		$.post(request.baseUrl + '/cmsAdmin/category/delete', {'id': data.node.id})
			.done(function (d) {
				if (!d.status) {
					data.instance.refresh();
				} else {
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', data.parent);
				}
			})
			.fail(function () {
				data.instance.refresh();
			});
	});
});

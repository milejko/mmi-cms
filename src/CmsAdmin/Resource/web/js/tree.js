/**
 * Obsługa drzewka stron CMS
 */

var request = request || {};
//konfiguracja
var CATEGORYCONF = CATEGORYCONF || {};
//klucz do stanu drzewka
CATEGORYCONF.stateKey = 'cms-category-jstree';
//po jakim czasie ukryć message
CATEGORYCONF.msgDelay = 2500;
//czy przeładować
CATEGORYCONF.reload = false;
//czy otwarto menu kontekstowe
CATEGORYCONF.contextMenu = false;

//zarządzanie stronami
$(document).ready(function () {
	//przeniesienie messengera
	$('ul#messenger').appendTo('#categoryMessageContainer').show();
	
	//odpalenie drzewka
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
				'responsive' : false,
				'stripes' : true
            },
			'strings': {
				'New node': 'Nowa strona',
				'Loading ...': 'Ładowanie ...'
			},
			'multiple': false,
			'expand_selected_onload': true,
			'force_text': true,
            'check_callback': function (op) {
				if (op === 'delete_node') {
					return confirm("Czy na pewno usunąć stronę?");
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
			'root': { 'valid_children': ["default", "leaf"], 'icon': request.baseUrl + '/resource/cmsAdmin/images/tree.png' },
			'default': { 'valid_children': ["default", "leaf"] },
			'leaf': { 'valid_children': ["default", "leaf"] }
		},
		'contextmenu': {
			'items': function (node) {
				CATEGORYCONF.contextMenu = true;
				var tmp = $.jstree.defaults.contextmenu.items();
				delete tmp.ccp;
				tmp.create.label = "Utwórz podstronę";
				tmp.rename.label = "Zmień nazwę";
				tmp.remove.label = "Usuń";
				if (this.get_type(node) !== "leaf") {
					delete tmp.remove;
				}
				if (this.get_type(node) === "root") {
					tmp.create.label = "Utwórz nową stronę";
					delete tmp.rename;
					tmp.create.separator_after = false;
				} else {
					tmp.edit = {
						"separator_before": true,
						"separator_after": false,
						"label": "Edytuj",
						"action": function (data) {
							var inst = $.jstree.reference(data.reference);
							var node = inst.get_node(data.reference);
							CATEGORYCONF.editForm(node);
						}
					};
				}
				return tmp;
			}
		},
		'plugins' : [ "state", "unique", "types", "contextmenu", "dnd", "wholerow" ]
    })
	.on('delete_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/delete', {'id': data.node.id})
			.done(function (d) {
				if (d.status) {
					CATEGORYCONF.reload = true;
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', data.parent);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się usunąć strony'});
			});
	})
	.on('create_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/create', {'parentId': data.node.parent, 'order': data.position, 'name': data.node.text})
			.done(function (d) {
				if (d.status) {
					data.instance.set_id(data.node, d.id);
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', d.id);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się utworzyć strony'});
			});
	})
	.on('rename_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		$.post(request.baseUrl + '/cmsAdmin/category/rename', {'id': data.node.id, 'name': data.text})
			.done(function (d) {
				if (d.status) {
					CATEGORYCONF.reload = true;
					data.node.text = d.name;
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', d.id);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się zmienić nazwy strony'});
			});
	})
	.on('move_node.jstree', function (e, data) {
		CATEGORYCONF.hideMessage();
		var params = {'id': data.node.id, 'parentId': data.parent, 'oldParentId': data.old_parent, 'order': data.position, 'oldOrder': data.old_position};
		$.post(request.baseUrl + '/cmsAdmin/category/move', params)
			.done(function (d) {
				if (d.status) {
					CATEGORYCONF.reload = true;
					$('#jstree').jstree('deselect_all');
					$('#jstree').jstree('select_node', d.id);
				} else {
					data.instance.refresh();
				}
				CATEGORYCONF.showMessage(d);
			})
			.fail(function () {
				data.instance.refresh();
				CATEGORYCONF.showMessage({'error': 'Nie udało się przenieść strony'});
			});
	})
	.on('changed.jstree', function (e, data) {
		if (!data || !data.selected || !data.selected.length || !(0 in data.selected)) {
			return;
		}
		setTimeout(function() {
			if (!CATEGORYCONF.reload && parseFloat(request.id) === parseFloat(data.selected[0])) {
				return;
			}
			if (CATEGORYCONF.contextMenu && !CATEGORYCONF.reload) {
				CATEGORYCONF.contextMenu = false;
				return;
			}
			if (CATEGORYCONF.reload || parseFloat(request.id) !== parseFloat(data.selected[0])) {
				CATEGORYCONF.loadUrl(data);
			}
		}, 150);
	});
});

//przeładowanie strony
CATEGORYCONF.loadUrl = function (data) {
	//przerwanie odtwarzania audio i wideo
	var stopPlaying = function (elem) {
		if (!isNaN(elem.duration)) {
			elem.pause();
			elem.currentTime = elem.duration;
		}
	};
	$('audio, video').each(function () {
		stopPlaying(this);
	});
	//przy ładowaniu zewnętrznej ramki wyrzuca cors
	//$('iframe#preview-frame').contents().find('audio, video').each(function () {
		//stopPlaying(this);
	//});
	window.location.assign(request.baseUrl + '/cmsAdmin/category/edit?id=' + data.selected[0] + window.location.hash);
};

CATEGORYCONF.editForm = function (node) {
	window.location.assign(request.baseUrl + '/cmsAdmin/category/edit?id=' + node.id + window.location.hash);
};

CATEGORYCONF.showMessage = function (data) {
	$('#categoryMessageContainer').empty();
	var msg = null;
	var msgClass = ' class="notice warning"';
	var icon = '<i class="icon-warning-sign icon-large"></i>';
	if (typeof data.error === 'string') {
		msg = data.error;
		msgClass = ' class="notice error"';
		icon = '<i class="icon-remove-sign icon-large"></i>';
	} else if (typeof data.message === 'string') {
		msg = data.message;
		msgClass = ' class="notice success"';
		icon = '<i class="icon-ok icon-large"></i>';
	}
	if (msg === null) {
		return;
	}
	var html = '<ul id="messenger">';
	html += '<li' + msgClass + '>' + icon + '<div class="alert">' + msg + '<a class="close-alert" href="#"></a></div></li>';
	html += '</ul>';
	$('#categoryMessageContainer').html(html);
	$('#categoryMessageContainer ul#messenger').show();
};

CATEGORYCONF.hideMessage = function () {
	$('#categoryMessageContainer').empty();
};
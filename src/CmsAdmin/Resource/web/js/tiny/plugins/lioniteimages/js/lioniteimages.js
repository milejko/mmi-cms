;(function(){
	var parentWin = (!window.frameElement && window.dialogArguments) || opener || parent || top;
	var parentEditor = parentWin.lionite_activeEditor;
	
	var LioniteImages = window.LioniteImages = {
		options: {
			uploadUrl: parentEditor.settings.baseUrl + '/cmsAdmin/upload/plupload',
			params: {
				formObject: parentEditor.settings.object,
				formObjectId: parentEditor.settings.objectId,
				cmsFileId: 0
			},
			removeUrl: 'connector/php/remove.php',
			galleryUrl: parentEditor.settings.baseUrl + '/?module=cms&controller=file&action=listLayout&object=' + parentEditor.settings.object + '&objectId=' + parentEditor.settings.objectId + '&t=' + parentEditor.settings.time + '&hash=' + parentEditor.settings.hash,
			callback: function(src,options) {
				
			}
		},
		
		init : function() {
			var o = this.options;
			this.ajaxUpload($('#lionite-file-upload'),o.uploadUrl);
			$.ajax({
				url: o.galleryUrl,
				success: function(response) {
					$('#lionite-gallery').html(response);
				}
			});
			this.events();
		},
		events: function() {
			var self = this;
			var o = this.options;
			$('#lionite-gallery').click(function(e){
				e.preventDefault();
				var el = $(e.target);
				if(el.is('a')) {
					if(el.is('.insert')) {
						var image = '<figure class="image" contenteditable="false"><img contenteditable="true" src="' + el.attr('href') + '" alt="" /><figcaption>opis</figcaption></figure>';
						self.insert(image);
						self.close();
					} else if(el.is('.delete')) {
						$.ajax({
							url: o.removeUrl,
							data:'url=' + encodeURIComponent(el.attr('href'))
						});
						el.parents('li:first').remove();
					}
				}

			});
			$('#lionite-gallery-manager .urlinsert').click(function(){
				var src = $("input[name='src']").val();
				if(src != '') {
					var image = '<img src="' + src + '" />';
					
					self.insert(image);
				}
				self.close();
			});
			$('.close-link').click(function(){
				self.close();
			});
			
		},
		insert : function(text) {
			parentEditor.insertContent(text);
		},
		close: function() {
			parentEditor.windowManager.close();
		},
		ajaxUpload: function(el,connector) {
			var o = this.options;
			 var url = connector,
				uploadButton = $('<button/>')
					.addClass('btn btn-primary')
					.prop('disabled', true)
					.text('Processing...')
					.on('click', function () {
						var $this = $(this),
							data = $this.data();
						$this
							.off('click')
							.text('Abort')
							.on('click', function () {
								$this.remove();
								data.abort();
							});
						data.submit().always(function () {
							$this.remove();
						});
					});
			$(el).fileupload({
				url: url,
				dropZone: $(el).parents(".dropzone:first"),
				dataType: 'json',
				autoUpload: true,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				maxFileSize: 5000000,
				formData: function(form) {
					var params =  form.serializeArray();
																				
					params.push({name: 'fileId', value: this.files[0].name.substr(1, 4) + this.files[0].lastModified});
					params.push({name: 'name', value: this.files[0].name});
					params.push({name: 'origSize', value: this.files[0].size});
					
					for(var i in o.params) {
						params.push({name:i,value:o.params[i]});
					}
					return params;
				}
			});
			$(el).on('fileuploadadd', function (e, data) {				
				data.context = $('<div/>').appendTo('#files');
				
			});
			$(el).on('fileuploadprocessalways', function (e, data) {
				var index = data.index,
					file = data.files[index],
					node = $(data.context.children()[index]);
				if (file.preview) {
					node
						.prepend('<br>')
						.prepend(file.preview);
				}
				if (file.error) {
					node
						.append('<br>')
						.append($('<span class="text-danger"/>').text(file.error));
				}
				if (index + 1 === data.files.length) {
					data.context.find('button')
						.text('Upload')
						.prop('disabled', !!data.files.error);
				}
			});
			$(el).on('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
				);
			});
			$(el).on('fileuploaddone', function (e, data) {
				$.each(data.result.files, function (index, file) {
					if(file.html) {
						$('#lionite-gallery').append(file.html);
						$('#progress .progress-bar').css({width:0});
					} else if (file.url) {
						var link = $('<a>')
							.attr('target', '_blank')
							.prop('href', file.url);
						$(data.context.children()[index])
							.wrap(link);
					} else if (file.error) {
						var error = $('<span class="text-danger"/>').text(file.error);
						$(data.context.children()[index])
							.append('<br>')
							.append(error);
					}
				});
			});
			$(el).on('fileuploadfail', function (e, data) {
				$.each(data.files, function (index, file) {
					var error = $('<span class="text-danger"/>').text('File upload failed.');
					$(data.context.children()[index])
						.append('<br>')
						.append(error);
				});
			}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
		
		}
		
	};
})();
$(function(){
	LioniteImages.init();
});

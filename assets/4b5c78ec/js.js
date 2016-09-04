
function init_CKEditor(nd_id)
{
	if (CKEDITOR.instances[nd_id])
		CKEDITOR.instances[nd_id].destroy(true);

	CKEDITOR.replace(nd_id, 
	{
		toolbar :
		[
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
	{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
	'/',
	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
	{ name: 'others', items: [ '-' ] },
	{ name: 'about', items: [ 'About' ] }
],
		extraPlugins: 'mybutton,blockquote',
		format_tags: 'h1;h2;h3;p',
		enterMode: CKEDITOR.ENTER_BR,
	});
}

function init_CKEditor_options(nd_id, options)
{
	if (CKEDITOR.instances[nd_id])
		CKEDITOR.instances[nd_id].destroy(true);

	CKEDITOR.replace(nd_id, options);
}


$(document).ready(function() {
		$(".fancybox").fancybox({
			padding: 10,
			beforeShow: function() {
				$('.fancybox-skin').css('opacity', 0);
	        },
			afterShow: function() {

	        	var that = this;

	        	var img = new Image();
				img.onload = function() {

					$('.fancybox-skin').contents().find(".fancybox-image").attr('src', img.src);
					//$('.fancybox-skin').contents().find(".fancybox-image").css("max-height", "600px");

					setTimeout(function(){
					    var h = $('.fancybox-skin').contents().find('.fancybox-image').height();

						that.height = h*0.7;

						// 0.7 имеется ввиду от высоты браузера

						var h_img = $('.fancybox-skin').contents().find('.fancybox-image').height();
						var w_img = $('.fancybox-skin').contents().find('.fancybox-image').width();
						var h_screen = $(window).height();
						var w_screen = $(window).width();

						scale = (h_screen*0.7)/h_img; //коэффициент на который нужно уменьшить ширину, что бы фото осталось в тех же пропорциях

						$('.fancybox-skin').contents().find('.fancybox-image').height(h_screen*0.7);
						$('.fancybox-skin').contents().find('.fancybox-image').width(w_img*scale);


						$(".fancybox-inner").height(h_screen*0.7);
						$('.fancybox-inner').width(w_img*scale);
						$('.fancybox-skin').height(h_screen*0.7 + 10);
						$('.fancybox-skin').width(w_img*scale);

						$.fancybox.reposition();

						$('.fancybox-wrap').css('left', w_screen/2-(w_img*scale)/2); // центрую картинку с новой шириной по центру


						$('.fancybox-skin').css('opacity', 1);

					}, 100);
			
				}
				img.src = $('.fancybox-skin').contents().find(".fancybox-image").attr('src');
	        },
		});

	$('.js-delete-photo').on('click', function deletePhoto(e) {
		e.preventDefault();
		if (confirm('Вы уверены?'))
		{
			var url = $(this).attr('href');
			$.post(url).success(setTimeout(function () {location.reload()}, 500));
		}
  	});

  	$("#Item_start").datepicker({format: 'yyyy-mm-dd'});
  	$("#end").datepicker({format: 'yyyy-mm-dd'});

});

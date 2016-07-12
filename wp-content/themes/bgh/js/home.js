$(function(){




	if ($('#slides').length > 0) {
		var autoplay = true
		if ($('#slides .slide').length == 1) {
			$('#slides').append('<div class="slide"></div>')
			autoplay = false
		}
		$('#slides').slidesjs({
			width: 716,
			height: 310,
			play: {
				active: false,
				auto: autoplay,
				interval: 5000,
				swap: true,
				pauseOnHover: true,
				restartDelay: 2500
			}
		})
		if (autoplay == false)
			$('.slidesjs-pagination').remove()
	}

	// Menu placeholder behavior
	// -------------------
	$('.search input').on('focus', function() {
		$('.search .placeholder').fadeOut()
	})
	$('.search input').on('blur', function() {
		if ($(this).val() == '')
			$('.search .placeholder').fadeIn()
	})
	setInterval(function() {
		if (!$('.search .placeholder').is(':visible'))
			return
		var messages = $('.search .placeholder').data('messages').replace(/; /g, ';').split(';'),
			message = $('.search .placeholder').data('message')
		$('.search .placeholder').animate({ 'color': 'transparent' }, 600, function() {
			if (message + 1 <= messages.length - 1)
				message += 1
			else
				message = 0
			$('.search .placeholder').data('message', message)
			$('.search .placeholder').html(messages[message])
			$('.search .placeholder').animate({ 'color': '#565656' }, 600)
		})
	}, 6000)



	$('.product-list > ul > li > a').on('mousedown',function(){
		console.log('product-list click ' +  $(this).data('ix'));
		ga('send', 'event', 'home-destacados', 'mousedown', '', $(this).data('ix'));
	});


	$('#section_tiles .clean').on('mousedown',function(){
		console.log('section_tiles click ' +  $(this).data('ix'));
		ga('send', 'event', 'home-novedades', 'mousedown', '', $(this).data('ix'));
	});

})
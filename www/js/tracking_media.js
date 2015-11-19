$(function(){
	$('audio').bind('play', function(){
		var title = $(this).siblings('.title').text()
			, category = $(this).siblings('input[name="category"]').val()
		;
		_gaq.push(['_trackEvent', category, 'play', title, 0, true]);
	});
	$('audio').bind('ended', function(){
		var title = $(this).siblings('.title').text()
			, category = $(this).siblings('input[name="category"]').val()
			, time = $(this).get(0).duration
			;
		_gaq.push(['_trackEvent', category, 'ended', title, time, true]);
	});
	$('audio').bind('pause', function(){
		var title = $(this).siblings('.title').text()
			, category = $(this).siblings('input[name="category"]').val()
			, time = $(this).get(0).currentTime
			;
			if(time == $(this).get(0).duration){
				return;
			}
		_gaq.push(['_trackEvent', category, 'pause', title, time, true]);
	});
});

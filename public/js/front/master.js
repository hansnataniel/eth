function number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
	//  discuss at: http://locutus.io/php/number_format/
	// original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// improved by: Kevin van Zonneveld (http://kvz.io)
	// improved by: davook
	// improved by: Brett Zamir (http://brett-zamir.me)
	// improved by: Brett Zamir (http://brett-zamir.me)
	// improved by: Theriault (https://github.com/Theriault)
	// improved by: Kevin van Zonneveld (http://kvz.io)
	// bugfixed by: Michael White (http://getsprink.com)
	// bugfixed by: Benjamin Lupton
	// bugfixed by: Allan Jensen (http://www.winternet.no)
	// bugfixed by: Howard Yeend
	// bugfixed by: Diogo Resende
	// bugfixed by: Rival
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	//  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	//  revised by: Luke Smith (http://lucassmith.name)
	//    input by: Kheang Hok Chin (http://www.distantia.ca/)
	//    input by: Jay Klehr
	//    input by: Amir Habibi (http://www.residence-mixte.com/)
	//    input by: Amirouche
	//   example 1: number_format(1234.56)
	//   returns 1: '1,235'
	//   example 2: number_format(1234.56, 2, ',', ' ')
	//   returns 2: '1 234,56'
	//   example 3: number_format(1234.5678, 2, '.', '')
	//   returns 3: '1234.57'
	//   example 4: number_format(67, 2, ',', '.')
	//   returns 4: '67,00'
	//   example 5: number_format(1000)
	//   returns 5: '1,000'
	//   example 6: number_format(67.311, 2)
	//   returns 6: '67.31'
	//   example 7: number_format(1000.55, 1)
	//   returns 7: '1,000.6'
	//   example 8: number_format(67000, 5, ',', '.')
	//   returns 8: '67.000,00000'
	//   example 9: number_format(0.9, 0)
	//   returns 9: '1'
	//  example 10: number_format('1.20', 2)
	//  returns 10: '1.20'
	//  example 11: number_format('1.20', 4)
	//  returns 11: '1.2000'
	//  example 12: number_format('1.2000', 3)
	//  returns 12: '1.200'
	//  example 13: number_format('1 000,50', 2, '.', ' ')
	//  returns 13: '100 050.00'
	//  example 14: number_format(1e-8, 8, '.', '')
	//  returns 14: '0.00000001'
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
	var n = !isFinite(+number) ? 0 : +number
	var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
	var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
	var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
	var s = ''
	var toFixedFix = function (n, prec) {
	var k = Math.pow(10, prec)
	return '' + (Math.round(n * k) / k)
	  .toFixed(prec)
	}
	// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || ''
		s[1] += new Array(prec - s[1].length + 1).join('0')
	}
	return s.join(dec)
}

function loginOpen () {
	$('.login-switch').addClass('active');

	if($('.window-width').width() >= 768)
	{
		$('.top-right .links-child.login').stop().fadeIn(600);
		$('.top-right .links-child-item.login').stop().each(function(e){
			$(this).delay(e*100).animate({
				'top': 0,
				'opacity': 1
			}, 700, "easeInOutCubic");
		});
	}
	else
	{
		$('.login-mob .links-child.login').stop().fadeIn(600);
		$('.login-mob .links-child-item.login').stop().each(function(e){
			$(this).delay(e*100).animate({
				'top': 0,
				'opacity': 1
			}, 700, "easeInOutCubic");
		});
	}
}

function loginClose () {
	$('.login-switch').removeClass('active');
	$('.links-child.login').stop().fadeOut(200);
	$('.links-child.login').find('.links-child-item').stop().delay(200).animate({
		'top': 100,
		'opacity': 0
	}, 0);
}

function profileOpen () {
	$('.profile-switch').addClass('active');
	$('.links-child.profile').stop().fadeIn(200);
	$('.links-child.profile').find('.links-child-item').stop().each(function(e){
		$(this).delay(e*100).animate({
			'top': 0,
			'opacity': 1
		}, 800, "easeInOutCubic");
	});
}

function profileClose () {
	$('.profile-switch').removeClass('active');
	$('.links-child.profile').stop().fadeOut(200);
	$('.links-child.profile').find('.links-child-item').stop().delay(200).animate({
		'top': 100,
		'opacity': 0
	}, 0);
}

function mobOpen () {
	$('.menu-switch').addClass('active');
	$('.menu-mob').stop().fadeIn(200);
	$('.mob-child').stop().each(function(e){
		$(this).delay(e*80).animate({
			'top': 0,
			'opacity': 1
		}, 700, "easeInOutCubic");
	});
}

function mobClose () {
	$('.menu-switch').removeClass('active');
	$('.menu-mob').stop().fadeOut(200);
	$('.mob-child').stop().delay(200).animate({
		'top': 100,
		'opacity': 0
	}, 0);
}

$(document).ready(function(){
	$('.login-switch').click(function(e){
		e.stopPropagation();

		if($(this).hasClass('active'))
		{
			loginClose();
			profileClose();
		}
		else
		{
			profileClose();
			loginOpen();
		}
	});

	$('.profile-switch').click(function(e){
		e.stopPropagation();
		
		if($(this).hasClass('active'))
		{
			profileClose();
			loginClose();
		}
		else
		{
			loginClose();
			profileOpen();
		}
	});

	$('.menu-switch').click(function(e){
		e.stopPropagation();
		
		if($(this).hasClass('active'))
		{
			mobClose();
		}
		else
		{
			mobOpen();
		}
	});

	if($('.login-error').hasClass('active'))
	{
		if($('.window-width').width() >= 768)
		{
			$('.top-right .login-switch').click();
		}
		else
		{
			$('.login-switch.login-mob').click();
		}
	}

	$('.links-child.login').click(function(e){
		e.stopPropagation();
	});

	$('body').live('click', function(){
		loginClose();
		profileClose();
		mobClose();
	});

	var satu = $('.regis-group-list.satu').height();
	var dua = $('.regis-group-list.dua').height();

	if(satu >= dua)
	{
		$('.regis-group-list.dua').height(satu);
	}
	else
	{
		$('.regis-group-list.satu').height(dua);
	}

	// alert($('.window-width').width() + " - " + $('.window-height').height());
});
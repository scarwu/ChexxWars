// JavaScript Document
/*
 * Page Loading
 */
$(function() {	
	$(document).ready(function() {
		$('#replayer').hide();
		$('#container .middle').html('Loading...<br />建議使用Chrome Firefox IE9+<br />如遇畫面讀不出來請按 F5');
		$('#msg').hide();
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'read', data: 'status', id: 'hero'},
  			success: function(json_data) {
				show(json_data, 'status', 'hero');
			}
		});
	});

/*
 * Main Optioin
 */
	$('.sub_option').hide().eq(0).show();
	$('ul.main_option li').eq(0).addClass('active');
	$('ul.main_option li').click(function() {
		var _data = $(this).find('a').attr('_data');
		var _clickTab = '#'+_data;
		$(_clickTab+' li').eq(0).addClass('active').siblings('.active').removeClass('active');
		$('#replayer').flash().remove();
		$('#replayer').hide();
		$('#sub_option').show();
		$('#container').show();
		$('#container .middle').html('Loading...');
		if(_data == 'status' || _data == 'store')
			var _id = 'hero';
		else if(_data == 'mission')
			var _id = '000';
		else if(_data == 'battle' || _data == 'friends')
			$('#sub_option').hide();
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'read', data: _data, id: _id},
  			success: function(json_data) {
				show(json_data, _data, _id);
			}
		});
		$(this).addClass('active').siblings('.active').removeClass('active');
		$(_clickTab).stop(false, true).fadeIn().siblings().hide();
		$('#container').hide().stop(false, true).fadeIn();
		return false;
	});

/*
 * Sub Optioin
 */
	$('ul.sub_option li').eq(0).addClass('active');
	$('ul.sub_option li').click(function() {
		var _data = $(this).find('a').attr('_data');
		var _id = $(this).find('a').attr('_id');
		$('#replayer').flash().remove();
		$('#replayer').hide();
		$('#sub_option').show();
		$('#container').show();
		$('#container .middle').html('Loading...');
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'read', data: _data, id: _id},
  			success: function(json_data) {
				show(json_data, _data, _id);
			}
		});
		$(this).addClass('active').siblings('.active').removeClass('active');
		$('#container').hide().stop(false, true).fadeIn();
		return false;
	});
	
/*
 * Display copyright
 */
  	$('a.copyright').click(function() {
		$('#replayer').hide();
		$('#sub_option').hide();
		$('#container .middle').html('<div class="copyright"></div>');
		$('div.copyright').append(
			'<h1>版權宣告</h1>'+
			'<br><h2>製作人</h2>'+
			'<br>巫文翔、池育安、彭振勛、王育安'+
			'<br><br><br><h2>音樂素材</h2>'+
			'<br><a href="http://www.oo39.com/music/music.html">oo39.com</a>'+
			'<br><br><br><h2>遊戲地圖素材</h2>'+
			'<br>RPG 遊戲製作大師'
		);
		return false;
	});
	
/*
 * Store Hero Option
 */
	$('div.store_hero_avatars a').live("click", function() {
		var _data =  _data = $(this).attr('_data');
		var _target = 'div.store_hero > ul > li.'+_data;
		$(_target+' span.hp_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.hp_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.atk_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.atk_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.dis_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.dis_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.res_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.res_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.skill_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.skill_point').html(),8)*8+'px'}, "slow");
		$(_target+' span.item_point').css({'width': '1px'}).animate({'width': parseInt($(_target+' span.item_point').html(),10)*8+'px'}, "slow");
		$(_target).stop(false, true).fadeIn().siblings().hide();
		return false;
	});

/*
 * Status Hero Option
 */
	$('div.status_hero_avatars a').live("click", function() {
		var _data =  _data = $(this).attr('_data');
		var _target = 'div.status_hero > ul > li.'+_data;
		$(_target+' li').eq(1).show().siblings().hide();
		$(_target+' li').eq(0).show();
		$(_target+' li.0 span.hp_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' li.0 span.hp_point').html(),10)*8+'px'}, "slow");
		$(_target+' li.0 span.atk_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' li.0 span.atk_point').html(),10)*8+'px'}, "slow");
		$(_target+' li.0 span.dis_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' li.0 span.dis_point').html(),10)*8+'px'}, "slow");
		$(_target+' li.0 span.res_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' li.0 span.res_point').html(),10)*8+'px'}, "slow");
		$(_target).stop(false, true).fadeIn().siblings().hide();
		return false;
	});
	
	
/*
 * Status Hero Change Order
 */
  	$('div.status_hero .order a').live("click", function() {
		var _id = $(this).parent().attr('_id');
		var _order = $(this).parent().attr('_order');
		var _target = 'div.status_hero > ul > li.'+_id+' li';
		$(_target).eq(parseInt(_order,10)+1).show().siblings().hide();
		$(_target).eq(0).show();
		_target = _target + '.'+_order;
		$(_target+' span.hp_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' span.hp_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.atk_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' span.atk_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.dis_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' span.dis_point').html(),10)*8+'px'}, "slow");
		$(_target+' span.res_point').css({'width': '1px'})
			.animate({'width': parseInt($(_target+' span.res_point').html(),10)*8+'px'}, "slow");
		return false;
	});

/*
 * Buy Hero, Item, Skill
 */
	$('a.buy').live("click", function() {
		var _data = $(this).attr('_data');
		var _idA = $(this).attr('_idA');
		var _idB = $(this).attr('_idB');
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'write', data: _data, idA: _idA, idB: _idB},
  			success: function(json_data) {
				bar(json_data['status']);
			}
		});
		return false;
	});

/*
 * Load Mission/Battle Flash
 */
	$('a.start').live("click", function() {
		var _data = $(this).attr('_data');
		var _id = $(this).attr('_id');
		$('#sub_option').hide();
		$('#container').hide();
		$('#replayer').show();
		$.ajax({
			dataType: 'text',
			cache: false,
			type: 'GET',
			url: 'extends/search.php',
			data: {data: _data, id: _id},
  			success: function(text_data) {
				$('#replayer').flash({swf: 'swf/replay.swf', flashvars: {data: text_data}, width: 720, height: 480});
				$('#replayer').append('<h3><a href="javascript:void(0);" class="skip" _data="'+_data+'">跳過戰鬥動畫</a></h3>');
			}
		});
		return false;
	});

/*
 * Remove Mission/Battle Flash
 */
	$('a.skip').live("click", function() {
		$('#container').show();
		$('#replayer').hide();
		$('#replayer').flash().remove();
		var _data = $(this).attr('_data');
		if(_data == 'mission') {
			$('#sub_option').show();
			$.ajax({
				dataType: 'json',
				cache: false,
				type: 'GET',
				url: 'extends/shell.php',
				data: {action: 'read'},
  				success: function(json_data) {
					show(json_data);
				}
			});	
		}
		else {
			$.ajax({
				dataType: 'json',
				cache: false,
				type: 'GET',
				url: 'extends/shell.php',
				data: {action: 'read', data: 'battle'},
  				success: function(json_data) {
					show(json_data, _data);
				}
			});	
		}
		return false;
	});
	
/*
 * Save Hero Arrange
 */
  	$('a.save_arrange').live("click", function() {
		var _target = 'div.status_arrange .heroB > span';
		_json = new Array();
		for(var i = 0;i < $(_target).length;i++) {
			var _temp = {
				'id': $(_target).eq(i).attr('_id'),
				'order': $(_target).eq(i).attr('_order'),
				'x': $(_target).eq(i).attr('_x'),
				'y': $(_target).eq(i).attr('_y')
			};
			_json[i] = _temp;
		}
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'write', data: 'status', idA: 'arrange', idB: JSON.stringify(_json)},
  			success: function(json_data) {
				show(json_data, 'status', 'arrange');
			}
		});
		return false;
	});
	
/*
 * Save Hero Equip
 */
  	$('a.save_equip').live("click", function() {
		var _target = 'div.status_hero > ul > li';
		var _euqip = new Array();
		var _skill = new Array();
		var _item = new Array();
		var _count = 0;
		for(var id =0;id < $(_target).length;id++) {
			var _hero_order = new Array();
			for(var order = 1;order < $(_target).eq(id).find('ul > li').length;order++) {
				var _order = $(_target).eq(id).find('ul > li').eq(order);
				var temp_skill = new Array();
				for(var skill_id = 0;skill_id < _order.find('.skill_list > span').length;skill_id++) {
					temp_skill[skill_id] = _order.find('.skill_list > span').eq(skill_id).attr('_id');
				}
				var temp_item = new Array();
				for(var item_id = 0;item_id < _order.find('.item_list > span').length;item_id++) {
					temp_item[item_id] = _order.find('.item_list > span').eq(item_id).attr('_id');
				}
				_euqip[_count++] = {
					'id': $(_target).eq(id).attr('class'), 
					'order': $(_target).eq(id).find('ul > li').eq(order).attr('class'),
					'skill': temp_skill,
					'item': temp_item
				}
			}
		}
		_target = 'div.status_equip span.status_skill > span';
		_count = 0;
		for(var i = 0;i < $(_target).length;i++) {
			_skill[_count++] = {
				'id': $(_target).eq(i).attr('_id'),
				'own': $(_target).eq(i).attr('_own')
			}
		}
		_target = 'div.status_equip span.status_item > span';
		_count = 0;
		for(var i = 0;i < $(_target).length;i++) {
			_item[_count++] = {
				'id': $(_target).eq(i).attr('_id'),
				'own': $(_target).eq(i).attr('_own')
			}
		}
		var _json = {
			'equip': _euqip, 
			'skill': _skill,
			'item': _item
		};
		$.ajax({
			dataType: 'json',
			cache: false,
			type: 'GET',
			url: 'extends/shell.php',
			data: {action: 'write', data: 'status', idA: 'hero', idB: JSON.stringify(_json)},
  			success: function(json_data) {
				bar(json_data['status']);
			}
		});
		return false;
	});
});
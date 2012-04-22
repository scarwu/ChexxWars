// JavaScript Document
function show(data, _class, _id) {
	bar(data['status']);
	switch(_class) {
		case 'status':
			if(_id == 'hero')
				status_hero(data['data']);
			else if(_id == 'arrange')
				status_arrange(data['data']);
			break;
		case 'store':
			if(_id == 'hero')
				store_hero(data['data']);
			else if(_id == 'item')
				store_item(data['data']);
			else if(_id == 'skill')
				store_skill(data['data']);
			break;
		case 'friends':
			friends(data['data']);
			break;
		case 'mission':
			mission(data['data']);
			break;
		case 'battle':
			battle(data['data']);
			break;
	}
}

/*
 * Show Status Bar
 */
function bar(temp) {
	$('#bar').html(
		"金錢:"+temp['coin']+
		" 經驗值:"+temp['exp']+
		" 勝場:"+temp['win']+
		" 敗場:"+temp['lose']
	);	
}

/*
 * Show Status Hero
 */
function status_hero(data) {
	$('#container .middle').html(
		'<div class="status_hero_avatars"></div>'+
		'<div class="status_hero"><ul></ul></div>'+
		'<div class="status_equip"><span class="status_skill">技能<br /></span>'+
		'<span class="status_item">物品<br /></span>'+
		'<h3><a href="javascript:void(0);" class="save_equip">儲存英雄配置</a></h3></div>'
	);
//Print Hero Data
	var temp = data['hero'];
	for(var idx in temp) {
		$('div.status_hero_avatars').append(
			'<a href="javascript:void(0);" _data="'+idx+'">'+
			'<img src="images/hero/hero'+idx+'s.jpg"></a>');
		$('div.status_hero > ul').append('<li class="'+idx+'"><ul class="'+idx+'"><li><span class="'+idx+'"></span></li></ul></li>');
		for(var idy in temp[idx]) {
			$('div.status_hero > ul > li.'+idx+' ul.'+idx+' li span.'+idx)
				.append('<span class="order" _id="'+idx+'" _order="'+idy+'">'+
						'<a href="javascript:void(0);">'+(parseInt(idy,10)+1)+'</a></span>'
				);
			var _target = 'div.status_hero > ul > li.'+idx+' > ul.'+idx;
			$(_target).append(
				'<li class="'+idy+'"><table cellspacing="0" cellpadding="0"><tr><td>'+
				'<span class="img"><img src="images/hero/hero'+idx+'b.png"></span></td><td>'+
				'<span class="name">'+temp[idx][idy]['name']+'</span><br />'+
				'<span class="hp">血量</span><span class="hp_point" _hp="'+temp[idx][idy]['hp']+'">'+temp[idx][idy]['hp']+'</span><br />'+
				'<span class="atk">攻擊力</span><span class="atk_point" _atk="'+temp[idx][idy]['atk']+'">'+temp[idx][idy]['atk']+'</span><br />'+
				'<span class="dis">移動距離</span><span class="dis_point" _dis="'+temp[idx][idy]['dis']+'">'+temp[idx][idy]['dis']+'</span><br />'+
				'<span class="res">耗費資源</span><span class="res_point" _res="'+temp[idx][idy]['res']+'">'+temp[idx][idy]['res']+'</span><br />'+
				'<span class="skill">技能<span class="skill_list" _var="'+temp[idx][idy]['skill']+'"></span></span>'+
				'<span class="item">物品<span class="item_list" _var="'+temp[idx][idy]['item']+'" _id="'+idx+'" _order="'+idy+'"></span></span></li>'
			);
			$(_target+' li.'+idy+' span.skill_list').css({height: temp[idx][idy]['skill']*25+'px'});
			$(_target+' li.'+idy+' span.item_list').css({height: temp[idx][idy]['item']*25+'px'});
			for(var id in temp[idx][idy]['skill_list']) {
				$(_target+' li.'+idy+' span.skill_list').append(
					'<span class="skill_set_block" _id="'+temp[idx][idy]['skill_list'][id]['id']+
					'" _atk="'+temp[idx][idy]['skill_list'][id]['atk']+
					'" _min_dis="'+temp[idx][idy]['skill_list'][id]['min_dis']+
					'" _max_dis="'+temp[idx][idy]['skill_list'][id]['max_dis']+'">'+
					temp[idx][idy]['skill_list'][id]['name']+'</span>'
				);
			}
			for(var id in temp[idx][idy]['item_list']) {
				$(_target+' li.'+idy+' span.item_list').append(
					'<span class="item_set_block" _id="'+temp[idx][idy]['item_list'][id]['id']+
					'" _hp="'+temp[idx][idy]['item_list'][id]['hp']+
					'" _atk="'+temp[idx][idy]['item_list'][id]['atk']+
					'" _dis="'+temp[idx][idy]['item_list'][id]['dis']+'">'+
					temp[idx][idy]['item_list'][id]['name']+'</span>'
				);
			}
			$(_target+' li.'+idy+' span.hp_point').css({width: temp[idx][idy]['hp']*8+'px'});
			$(_target+' li.'+idy+' span.atk_point').css({width: temp[idx][idy]['atk']*8+'px'});
			$(_target+' li.'+idy+' span.dis_point').css({width: temp[idx][idy]['dis']*8+'px'});
			$(_target+' li.'+idy+' span.res_point').css({width: temp[idx][idy]['res']*8+'px'});
			$(_target+' li').eq(1).show().siblings().hide();
			$(_target+' li').eq(0).show();
		}
	}
	//Print Skill Data
	var temp = data['skill'];
	for(var idx in temp) {
		$('div.status_equip span.status_skill').append(
			'<span class="skill_unset_block" _id="'+temp[idx]['id']+
			'" _own="'+temp[idx]['own']+'" _atk="'+temp[idx]['atk']+
			'" _min_dis="'+temp[idx]['min_dis']+'" _max_dis="'+temp[idx]['max_dis']+'">'+temp[idx]['name']+
			'<span class="own"> X'+temp[idx]['own']+'</span></span>'
		);
	}
	//Print Item Data
	var temp = data['item'];
	for(var idx in temp) {
		$('div.status_equip span.status_item').append(
			'<span class="item_unset_block" _id="'+temp[idx]['id']+
			'" _own="'+temp[idx]['own']+'" _hp="'+temp[idx]['hp']+
			'" _atk="'+temp[idx]['atk']+'" _dis="'+temp[idx]['dis']+'">'+temp[idx]['name']+
			'<span class="own"> X'+temp[idx]['own']+'</span></span>'
		);
	}
	$('span.skill_set_block').draggable({helper: 'clone'});
	$('span.skill_unset_block').draggable({helper: 'clone'});
	$('span.status_skill').droppable({
		accept: 'span.skill_set_block',
		drop: function(ev, ui) {
			var _id = $(ui.draggable).attr('_id');
			var _set = false;
			var _target = 'span.status_skill span';
			for(var i = 0;i < $(_target).length;i++) {
				if($(_target).eq(i).attr('_id') == _id) {
					var _own = $(_target).eq(i).attr('_own');
					$(_target).eq(i).remove();
					$(this).append($(ui.draggable)
						.attr({'class': 'skill_unset_block', '_own': (parseInt(_own,10)+1)})
						.append('<span class="own"> X'+(parseInt(_own,10)+1)+'</span>')
					);
					_set = true;
					break;
				}
			}
			if(!_set) {
				$(this).append($(ui.draggable).attr({'class': 'skill_unset_block', '_own': 1}).append('<span class="own"> X1</span>'));
			}
		}
	});
	$('span.skill_list').droppable({
		accept: 'span.skill_unset_block',
		drop: function(ev, ui) {
			if($(this).find('span').length < $(this).attr('_var')) {
				var _own = $(ui.draggable).attr('_own');
				if(_own > 1) {
					$('span.status_skill').append($(ui.draggable).clone()
						.attr({'class': 'skill_unset_block', '_own': (parseInt(_own,10)-1)})
						.children('.own').html(' X'+(parseInt(_own,10)-1)).end());
					$('span.skill_unset_block').draggable({helper: 'clone'});
				}
				$(this).append($(ui.draggable).attr('class', 'skill_set_block').children('.own').remove().end());
			}
		}
	});
	$('span.item_set_block').draggable({helper: 'clone'});
	$('span.item_unset_block').draggable({helper: 'clone'});
	$('span.status_item').droppable({
		accept: 'span.item_set_block',
		drop: function(ev, ui) {
			var _target = 'ul.'+$(ui.draggable).parent().attr('_id')+' li.'+$(ui.draggable).parent().attr('_order');
			var _hp = parseInt($(_target+' span.hp_point').attr('_hp'),10) - parseInt($(ui.draggable).attr('_hp'),10);
			var _atk = parseInt($(_target+' span.atk_point').attr('_atk'),10) - parseInt($(ui.draggable).attr('_atk'),10);
			var _dis = parseInt($(_target+' span.dis_point').attr('_dis'),10) - parseInt($(ui.draggable).attr('_dis'),10);
			$(_target+' span.hp_point').animate({'width': _hp*8+'px'}, "slow").attr('_hp', _hp).html(_hp);
			$(_target+' span.atk_point').animate({'width': _atk*8+'px'}, "slow").attr('_atk', _atk).html(_atk);
			$(_target+' span.dis_point').animate({'width': _dis*8+'px'}, "slow").attr('_dis', _dis).html(_dis);
			var _set = false;
			var _target = 'span.status_item span';
			for(var i = 0;i < $(_target).length;i++) {
				var _id = $(ui.draggable).attr('_id');
				if($(_target).eq(i).attr('_id') == _id) {
					var _own = $(_target).eq(i).attr('_own');
					$(_target).eq(i).remove();
					$(this).append($(ui.draggable)
						.attr({'class': 'item_unset_block', '_own': (parseInt(_own,10)+1)})
						.append('<span class="own"> X'+(parseInt(_own,10)+1)+'</span>')
					);
					_set = true;
					break;
				}
			}
			if(!_set) {
				$(this).append($(ui.draggable).attr({'class': 'item_unset_block', '_own': 1}).append('<span class="own"> X1</span>'));
			}
		}
	});
	$('span.item_list').droppable({
		accept: 'span.item_unset_block',
		drop: function(ev, ui) {
			if($(this).find('span').length < $(this).attr('_var')) {
				var _target = 'ul.'+$(this).attr('_id')+' li.'+$(this).attr('_order');
				var _hp = parseInt($(_target+' span.hp_point').attr('_hp'),10) + parseInt($(ui.draggable).attr('_hp'),10);
				var _atk = parseInt($(_target+' span.atk_point').attr('_atk'),10) + parseInt($(ui.draggable).attr('_atk'),10);
				var _dis = parseInt($(_target+' span.dis_point').attr('_dis'),10) + parseInt($(ui.draggable).attr('_dis'),10);
				$(_target+' span.hp_point').animate({'width': _hp*8+'px'}, "slow").attr('_hp', _hp).html(_hp);
				$(_target+' span.atk_point').animate({'width': _atk*8+'px'}, "slow").attr('_atk', _atk).html(_atk);
				$(_target+' span.dis_point').animate({'width': _dis*8+'px'}, "slow").attr('_dis', _dis).html(_dis);
				var _own = $(ui.draggable).attr('_own');
				if(_own > 1) {
					$('span.status_item').append($(ui.draggable).clone()
						.attr({'class': 'item_unset_block', '_own': (parseInt(_own,10)-1)})
						.children('.own').html(' X'+(parseInt(_own,10)-1)).end());
					$('span.item_unset_block').draggable({helper: 'clone'});
				}
				$(this).append($(ui.draggable).attr('class', 'item_set_block').children('.own').remove().end());
			}
		}
	});
	$('div.status_hero > ul > li').eq(0).siblings().hide();
	var _target = 'div.status_hero > ul > li.000 li.0';
	$(_target+' span.hp_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.hp_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.atk_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.atk_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.dis_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.dis_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.res_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.res_point').html(),10)*8+'px'}, "slow");
}

/*
 * Show Status Arrange
 */
function status_arrange(temp) {
	// Print Unset Hero
	$('#container .middle').html(
		'<div class="status_arrange">'+
		'<span class="heroA"></span>'+
		'<span class="map" _res="30" _cost="0"><span class="res_cost"></span>'+
		'<table cellspacing="1" cellpadding="0"></table>'+
		'<h3><a href="javascript:void(0);" class="save_arrange">儲存戰鬥配置</a></h3></span>'+
		'<span class="heroB"></span></div>'
	);
	hero = temp['unset'];
	for(var idx in hero) {
		for(var idy in hero[idx]) {
			$('div.status_arrange .heroA').append(
				'<span class="icon" _id="'+hero[idx][idy]['id']+'" _order="'+hero[idx][idy]['order']+'" _res="'+hero[idx][idy]['res']+'">'+
				'<span class="img"><span class="order">'+(parseInt(hero[idx][idy]['order'],10)+1)+'</span>'+
				'<img src="images/hero/hero'+hero[idx][idy]['id']+'s.jpg"></span>'+
				'<span class="info">血量: '+hero[idx][idy]['hp']+
				'<br />攻擊: '+hero[idx][idy]['atk']+
				'<br />距離: '+hero[idx][idy]['dis']+
				'<br />資源: '+hero[idx][idy]['res']+'</span></span>'
			);
		}
	}
	// Print Arrange Map
	var _target = 'div.status_arrange .map';
	for(i = 0;i < 8;i++) {
		$(_target+' table').append('<tr class="'+(i+2)+'" _x="'+(i+2)+'">'+
			'<td class="1" _y="1"></td>'+
			'<td class="2" _y="2"></td>'+
			'<td class="3" _y="3"></td></tr>'
		);
	}
	// Print Setted Hero
	hero = temp['set'];
	for(var idx in hero) {
		$(_target+' table tr.'+hero[idx]['x']+' td.'+hero[idx]['y']).append(
			'<span class="icon" _id="'+hero[idx]['id']+'" _order="'+hero[idx]['order']+'" _res="'+hero[idx]['res']+'" _set="1">'+
			'<span class="img"><span class="order">'+(parseInt(hero[idx]['order'],10)+1)+'</span>'+
			'<img src="images/hero/hero'+hero[idx]['id']+'s.jpg"></span>'+
			'<span class="info">血量: '+hero[idx]['hp']+
			'<br />攻擊: '+hero[idx]['atk']+
			'<br />距離: '+hero[idx]['dis']+
			'<br />資源: '+hero[idx]['res']+'</span></span>'
		);
		$(_target+' table tr.'+hero[idx]['x']+' td.'+hero[idx]['y']+' span.icon').find('span.info').hide().end();
		$(_target).attr('_cost', (parseInt($(_target).attr('_cost'),10)+parseInt(hero[idx]['res'],10)));
		$('div.status_arrange .heroB').append(
			'<span class="sort" _id="'+hero[idx]['id']+'" _order="'+hero[idx]['order']+'" _x="'+hero[idx]['x']+'" _y="'+hero[idx]['y']+'">'+
			'<span class="img"><span class="order">'+(parseInt(hero[idx]['order'],10)+1)+'</span>'+
			'<img src="images/hero/hero'+hero[idx]['id']+'s.jpg"></span>'+
			'<span class="info">血量: '+hero[idx]['hp']+
			'<br />攻擊: '+hero[idx]['atk']+
			'<br />距離: '+hero[idx]['dis']+
			'<br />資源: '+hero[idx]['res']+'</span></span>'
		);
	}
	$(_target+' .res_cost').html('<h3>使用資源: '+$(_target).attr('_cost')+' / 30<h3>');
	//Drag And Drop UI
	$('span.icon').draggable({helper: 'clone'});
	$('span.heroA').droppable({
		accept: 'span.icon',
		drop: function(ev, ui) {		
			if($(ui.draggable).attr('_set') == 1) {
				var _res = $(ui.draggable).attr('_res');
				var _cost = $('div.status_arrange .map').attr('_cost');
				$('div.status_arrange .map').attr('_cost', parseInt(_cost,10)-parseInt(_res,10));
				$('div.status_arrange .map .res_cost').html('<h3>使用資源: '+$('div.status_arrange .map').attr('_cost')+' / 30</h3>');
			}
			$(this).append($(ui.draggable).removeAttr('_set').find('span.info').show().end());
			var _id = $(ui.draggable).attr('_id');
			var _order = $(ui.draggable).attr('_order');
			var _target = 'div.status_arrange .heroB span';
			for(var i = 0;i < $(_target).length;i++) {
				if($(_target).eq(i).attr('_order') == _order && $(_target).eq(i).attr('_id') == _id) {
					$(_target).eq(i).remove();
					break;
				}
			}
		}
	});
	$('span.map td').droppable({
		accept: 'span.icon',
		over: function(event, ui) {
			$(this).css({'background-color': '#000'});
		},
		out: function(event, ui) {
			$(this).css({'background-color': ''});
		},
		drop: function(ev, ui) {
			var _res = $(ui.draggable).attr('_res');
			var _cost = $('div.status_arrange .map').attr('_cost');
			$(this).css({'background-color': ''});
			if($(this).find('span').length == 0 && (parseInt(_cost,10)+parseInt(_res,10) <= 30 || $(ui.draggable).attr('_set') == 1)) {
				if($(ui.draggable).attr('_set') != 1) {
					$('div.status_arrange .map').attr('_cost', parseInt(_cost,10)+parseInt(_res,10));
					$('div.status_arrange .map .res_cost').html('<h3>使用資源: '+$('div.status_arrange .map').attr('_cost')+' / 30</h3>');
				}
				$(this).append($(ui.draggable).attr('_set', 1).find('span.info').hide().end());
				var _id = $(ui.draggable).attr('_id');
				var _order = $(ui.draggable).attr('_order');
				var _target = 'div.status_arrange .heroB span';
				var _insort = false;
				for(var i = 0;i < $(_target).length;i++) {
					if($(_target).eq(i).attr('_order') == _order && $(_target).eq(i).attr('_id') == _id) {
						$(_target).eq(i).attr({
							'_x': $(this).parent().attr('_x'),
							'_y': $(this).attr('_y')
						});
						_insort = true;
						break;
					}
				}
				if(!_insort) {
					$('div.status_arrange .heroB').append($(ui.draggable).clone().attr({
						'class': 'sort ui-draggable',
						'_x': $(this).parent().attr('_x'),
						'_y': $(this).attr('_y')
					}).find('span.info').show().end());
				}
			}
		}
	});
	$('span.heroB').sortable();
	$('span.heroB').disableSelection();
}

/*
 * Show Store Hero
 */
function store_hero(temp) {
	$('#container .middle').html('<div class="store_hero_avatars"></div><div class="store_hero"><ul></ul></div>');
	for(var idx in temp) {
		$('div.store_hero_avatars').append(
			'<a href="javascript:void(0);" _data="'+idx+'">'+
			'<img src="images/hero/hero'+idx+'s.jpg"></a>'
		);
		$('div.store_hero ul').append(
			'<li class="'+idx+'"><table cellspacing="0" cellpadding="0"><tr><td>'+
			'<span class="img"><img src="images/hero/hero'+idx+'b.png"></span></td><td>'+
			'<span class="name">'+temp[idx]['name']+'</span><br />'+
			'<span class="hp">血量</span><span class="hp_point">'+temp[idx]['hp']+'</span><br />'+
			'<span class="atk">攻擊力</span><span class="atk_point">'+temp[idx]['atk']+'</span><br />'+
			'<span class="dis">移動距離</span><span class="dis_point">'+temp[idx]['dis']+'</span><br />'+
			'<span class="res">耗費資源</span><span class="res_point">'+temp[idx]['res']+'</span><br />'+
			'<span class="skill">技能格數</span><span class="skill_point">'+temp[idx]['skill']+'</span><br />'+
			'<span class="item">物品格數</span><span class="item_point">'+temp[idx]['item']+'</span><br />'+
			'<span class="price">價格</span><span class="price_point">'+temp[idx]['price']+'</span><br />'+
			'<span class="buyhero"><a href="javascript:void(0);" class="buy" _data="store" _idA="hero" _idB="'+temp[idx]['id']+'">購買</a></span>'+
			'</td></tr></table></li>'
		);
		$('div.store_hero > ul > li.'+idx+' .hp_point').css({width: temp[idx]['hp']*8+'px'});
		$('div.store_hero > ul > li.'+idx+' .atk_point').css({width: temp[idx]['atk']*8+'px'});
		$('div.store_hero > ul > li.'+idx+' .dis_point').css({width: temp[idx]['dis']*8+'px'});
		$('div.store_hero > ul > li.'+idx+' .res_point').css({width: temp[idx]['res']*8+'px'});
		$('div.store_hero > ul > li.'+idx+' .skill_point').css({width: temp[idx]['skill']*8+'px'});
		$('div.store_hero > ul > li.'+idx+' .item_point').css({width: temp[idx]['item']*8+'px'});
	}
	$('div.store_hero > ul > li').eq(0).siblings().hide();
	var _target = 'div.store_hero > ul > li.000';
	$(_target+' span.hp_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.hp_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.atk_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.atk_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.dis_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.dis_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.res_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.res_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.skill_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.skill_point').html(),10)*8+'px'}, "slow");
	$(_target+' span.item_point').css({'width': '1px'})
		.animate({'width': parseInt($(_target+' span.item_point').html(),10)*8+'px'}, "slow");
}

/*
 * Show Store Item
 */
function store_item(temp) {
	$('#container .middle').html('<div class="store_item"><table cellspacing="0" cellpadding="0"></table></div>');
	$('div.store_item table').append('<tr><td>名稱</td><td>血量</td><td>攻擊</td><td>距離</td><td>售價</td><td></td></tr>')
	for(var idx in temp) {
		$('div.store_item table').append(
			'<tr><td>'+temp[idx]['name']+
			'</td><td>'+temp[idx]['hp']+
			'</td><td>'+temp[idx]['atk']+
			'</td><td>'+temp[idx]['dis']+
			'</td><td>'+temp[idx]['price']+
			'</td><td><a href="javascript:void(0);" class="buy" _data="store" _idA="item" _idB="'+temp[idx]['id']+'">購買</a></td></tr>'
		);
	}
}

/*
 * Show Store Skill
 */
function store_skill(temp) {
	$('#container .middle').html('<div class="store_skill"><table cellspacing="0" cellpadding="0"></table></div>');
	$('div.store_skill table').append('<tr><td>名稱</td><td>攻擊傷害</td><td>攻擊範圍</td><td>售價</td><td></td></tr>')
	for(var idx in temp) {
		$('div.store_skill table').append(
			'<tr><td>'+temp[idx]['name']+
			'</td><td>'+temp[idx]['atk']+
			'</td><td>'+temp[idx]['min_dis']+'-'+temp[idx]['max_dis']+
			'</td><td>'+temp[idx]['price']+
			'</td><td><a href="javascript:void(0);" class="buy" _data="store" _idA="skill" _idB="'+temp[idx]['id']+'">購買</a></td></tr>'
		);
	}
}

/*
 * Show Friends Menu
 */
function friends(temp) {
	$('#container .middle').html('<div class="friends"></div>');
	for(var idx in temp) {
		$('div.friends').append(
			'<span class="avatars"><span class="img"><img src="'+temp[idx]['pic_square']+'"></span><span class="info"><b>'+temp[idx]['name']+
			'</b><br />勝場: '+temp[idx]['status']['win']+
			'<br />敗場: '+temp[idx]['status']['lose']+
			'</span></span>');
	}
}

/*
 * Show Battle Menu
 */
function battle(temp) {
	$('#container .middle').html('<div class="battle"></div>');
	for(var idx in temp) {
		$('div.battle').append(
			'<span class="avatars"><span class="img"><img src="'+temp[idx]['pic_square']+'"></span><span class="info"><b>'+temp[idx]['name']+
			'</b><br />勝場: '+temp[idx]['status']['win']+
			' 敗場: '+temp[idx]['status']['lose']+
			'<br /><a href="javascript:void(0);" class="start" _data="battle" _id="'+temp[idx]['uid']+'">決鬥</a></span></span>'
		);
	}
	
}

/*
 * Show Mission Menu
 */
function mission(temp) {
	$('#container .middle').html('<div class="mission"></div>');
	$('div.mission').append(
		'<span class="img"><img src="images/maps/map'+temp['maps']+'.jpg"></span>'+
		'<span class="info"><h2>'+temp['name']+'</h2>'+
		'<br>經驗值: '+temp['exp']+
		'<br>金幣報酬: '+temp['coin']+
		'<br><h3><a href="javascript:void(0);" class="start" _data="mission" _id="'+temp['id']+'">執行任務</a></h3></span>'
	);
}
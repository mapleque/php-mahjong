/**
 * 与服务端交互的方法及定义
 */
;(function(){
	if (!window.C) window.C = {};
	_.extend(C, {
		OP_CHI:		'c',
		OP_PENG:	'p',
		OP_GANG:	'g',
		OP_HU:		'h',
		OP_PUSH:	'u',
		OP_GET:		't',
		OP_INIT:	'i',
		OP_PASS:	's',
		op: {
			'c' : '吃',
			'p' : '碰',
			'g' : '杠',
			'h' : '胡',
			'u' : '出',
			't' : '抓',
			'i' : '起',
			's' : '过'
		}
	});
	if (!window.U) window.U = {};
	U.action = function(url, conf){
		var url = '/' + url + '.php';
		console.log('[send]', url, conf.data);
		$.ajax({
			url:url,
			type:'POST',
			dataType: 'json',
			data:{data:JSON.stringify(conf.data||{})},
			success:function(res){
				console.log('[recv]',res);
				if (res.status == 0) {
					typeof conf.success === 'function' && conf.success(res.data);
				} else {
					typeof conf.error === 'function' ? conf.error(res.status, res.err) : defaultError(res.status);
				}
			}
		});
	};

	U.tpl = function(tpl_name, data, callback){
		if (typeof callback === 'undefined' && typeof data === 'function') {
			callback = data;
			data = {};
		}
		var die = function(tpl){
			var $tpl = $(_.template(tpl)(data));
			var $body = $(document).find('article');
			$body.empty().append($tpl);
			callback($tpl);
		};
		if (!U.tpl_catch) {
			U.tpl_catch = {};
		}
		if (U.tpl_catch[tpl_name]) {
			die(U.tpl_catch[tpl_name]);
		} else {
			$.ajax({
				url: '/tpl/' + tpl_name + '.html',
				type: 'GET',
				dataType: 'text/html',
				success: function(res){
					U.tpl_catch[tpl_name] = res;
					die(res);
				}
			});
		}
	};

	var defaultError = function(status){
		var errorMessage = {
			1: '未登录',
			2: '服务器内部错误',
			3: '请求非法'
		};
		$('.error').empty().append(errorMessage[status]);
	};

	// not in use
	U.purl = function(){
		var href = window.location.href;
		var search = window.location.search.substring(1);
		var hash = window.location.hash.substring(1);
		var param = {};
		var paramstrs = search.split('&').concat(hash.split('&'));
		for (var i = 0; i < paramstrs.length; i++) {
			var tmp = paramstrs[i].split('=');
			if (tmp.length > 1) {
				param[tmp[0]] = tmp[1];
			} else if (tmp.length > 0) {
				param[tmp[0]] = tmp[0];
			}
		}
		return {
			href: href,
			search: search,
			hash: hash,
			param: param
		};
	};
})();

/**
 * 与服务端交互的方法
 */
;(function(){
	if (!window.U) window.U = {};
	U.action = function(url, conf){
		var url = '/api/' + url + '.php';
		console.log('[send]', url, conf.data);
		$.ajax({
			url:url,
			method:'POST',
			data:{data:JSON.stringify(conf.data||{})},
			success:function(res){
				console.log('[recv]',res);
				if (res.status == 0) {
					typeof conf.success === 'function' || conf.success(res.data);
				} else {
					typeof conf.error === 'function' ? conf.error(res.status, res.err) : defaultError(res.status);
				}
			}
		});
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
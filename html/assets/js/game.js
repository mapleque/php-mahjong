/**
 * 游戏控制
 */
;(function(){
	/***************************************/
	/* view
	/***************************************/

	var loadLoginForm = function(){
		console.log('load login form');
		U.tpl('login', function($root){
			// click to login
			$root.on('click', 'input[name=login]', function(){
				var $username = $root.find('input[name=username]');
				var $password = $root.find('input[name=password]');
				login($username.val(), $password.val());
			});
			// login(username, password)
			$root.on('click', 'input[name=register]', function(){
				loadRegisterForm();
			});
		});
	};
	loadRegisterForm = function(){
		console.log('load register form');
		U.tpl('register', function($root){
			$root.on('click', 'input[name=return]', function(){
				loadLoginForm();
			});
		});
	};

	var loadGameList = function(data){
		console.log('load game list' , JSON.stringify(data));
		U.tpl('game_list', data, function($root){
			// show not on desk
			// click to creat or join into
			$root.on('click', 'input[name=create]', function(){
				var game_id = $(this).data('id');
				if (!game_id) {
					game_id = null;
				}
				create(game_id);
			});
		});
	};
	var loadReadyView = function(data){
		console.log('load ready view' , JSON.stringify(data));
		U.tpl('ready_view', data, function($root){
			// waiting for user ready
			// show ready button
			// click to start a new set
			$root.on('click', 'input[name=ready]', function(){
				var game_id = $(this).data('id');
				start(game_id);
			});
		});
	};

	var loadPlayingView = function(data, with_click){
		console.log('load playing view' , JSON.stringify(data));
		U.tpl('playing_view', data, function($root){
			// update info to show
			// show op button
			var multi_sel = 0;
			var sel_cache = [];
			var cmd_cache = null;
			if (!with_click) {
				return;
			}
			// waiting for user op
			// click to request op with cmd
			// op(cmd, sel)
			$root.on('click', 'input.card', function(){
				var index = $(this).data('index');
				if (!multi_sel) {
					var sel = [];
					if (index || index == 0) {
						sel.push(index);
					}
					op(C.OP_PUSH, sel);
				} else {
					if (sel_cache.length == multi_sel) {
						return;
					} else if (sel_cache.length < multi_sel) {
						sel_cache.push(index);
						if (sel_cache.length == multi_sel) {
							op(cmd_cache, sel_cache);
						}
					}
				}
			});
			$root.on('click', 'input.op', function(){
				var cmd = $(this).data('op');
				// TODO switch cmd commit sel_cache
				switch (cmd) {
					case C.OP_GET:
					case C.OP_PASS:
						op(cmd);
						return;
				}
				multi_sel = 3;
			});
		});
	};

	/***************************************/
	/* controller
	/***************************************/
	var game_info = {};
	var login = function(username, password){
		U.action('login', {
			data: {
				username:username,
				password:password
			},
			success: function(){
				checkGameStatus();
			}
		});
	};

	var register = function(){};

	var checkUserStatus = function(){
		U.action('login', {
			success: function(){
				checkGameStatus();
			},
			error: function(){
				loadLoginForm();
			}
		});
	};

	var checkGameStatus = function(){
		U.action('game_info', {
			success: function(res){
				if (!res.game_id) {
					loadGameList(res);
				} else if (!res.set_id) {
					game_info.game_id = res.game_id;
					loadReadyView(res);
					setTimeout(function(){
						checkGameStatus();
					}, 1000);
				} else {
					game_info.set_id = res.set_id;
					checkSetStatus();
				}
			}
		});
	};

	var checkSetStatus = function(){
		U.action('set_info', {
			data:{
				set_id:game_info.set_id
			},
			success: function(res){
				if (res.cur_seq == res.seq) {
					loadPlayingView(res, true);
				} else {
					loadPlayingView(res, false);
					setTimeout(function(){
						checkSetStatus();
					}, 500);
				}
			}
		});
	};

	var start = function(game_id){
		U.action('start', {
			data:{
				game_id:game_id
			},
			success:function(){
				// waiting for status refresh
				//checkGameStatus();
			}
		});
	};

	var create = function(game_id){
		U.action('create', {
			data:{
				game_id:game_id
			},
			success:function(res){
				// waiting for status refresh
				checkGameStatus();
			}
		});
	};

	var op = function(cmd, sel){
		U.action('op', {
			data: {
				cmd: cmd,
				card_index_list: sel
			},
			success:function(){
				// waiting for status refresh
				checkSetStatus();
			}
		});
	};

	/***************************************/
	/* init
	/***************************************/
	var init = function(){
		checkUserStatus();
	};
	init();

})();

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
	var loadWaitingView = function(data){
		console.log('load waiting view' , JSON.stringify(data));
		U.tpl('waiting_view', data, function($root){
			// waiting for user op
			// show op button
			// click to request op with cmd
			// op(set_id, cmd, sel)
		});
	};

	var loadPlayingView = function(data){
		console.log('load playing view' , JSON.stringify(data));
		U.tpl('playing_view', data, function($root){
			// show playing view
			// only update info to show
		});
	};

	/***************************************/
	/* controller
	/***************************************/
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
					loadReadyView(res);
					setTimeout(function(){
						checkGameStatus();
					}, 1000);
				} else {
					checkSetStatus(res.set_id);
				}
			}
		});
	};

	var checkSetStatus = function(set_id){
		U.action('set_info', {
			data:{
				set_id:set_id
			},
			success: function(res){
				if (res.waiting) {
					loadWaitingView(res);
				} else {
					loadPlayingView(res);
				}
				setTimeout(function(){
					checkSetStatus(set_id);
				}, 500);
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
			}
		});
	};

	var get = function(set_id){
		U.action('get', {
			data: {
				set_id:set_id
			},
			success:function(){
				// waiting for status refresh
			}
		});
	};

	var op = function(set_id, cmd, sel){
		U.action('op', {
			data: {
				set_id:set_id,
				cmd: cmd,
				card_index_list: sel
			},
			success:function(){
				// waiting for status refresh
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

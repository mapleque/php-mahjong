/**
 * 游戏控制
 */
;(function(){
	/***************************************/
	/* view
	/***************************************/
	var $body = $(document).find('article');

	var $loginForm = (function(){
		var $root = $('<div class="login-form"></div>');
		var $form = $('<form>'
			+'<input type="text" name="username">'
			+'<input type="password" name="password">'
			+'<input type="button" value="登陆">'
			+'<input type="button" value="注册">'
			+'</form>');
		$root.append($form);
		return $root;
	})();

	var loadLoginForm = function(){
		$body.empty().append($loginForm);
		// click to login
		// login(username, password)
	};

	var loadGameList = function(data){
		//TODO:
		// show not on desk
		// click to creat or join into
		// create(game_id||null)
	};
	var loadReadyView = function(){
		// waiting for user ready
		// show ready button
		// click to start a new set
		// start(game_id)
	};
	var loadWaitingView = function(data){
		// waiting for user op
		// show op button
		// click to request op with cmd
		// op(set_id, cmd, sel)
	};

	var loadPlayingView = function(data){
		// show playing view
		// only update info to show
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
					loadGameList();
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
			success: function(res){
				if (res.waiting) {
					loadWaitingView(res);
				} else {
					loadPlayingView(res);
				}
				setTimeout(function(){
					checkSetStatus();
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
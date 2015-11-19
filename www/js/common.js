$(function(){
	$('#mailprompt').click(function(){
		window.prompt('連絡・質問等はこちら', jq8o());
	});
	
	function jq8o(){
		var s = '147,56,145,142,157,144,141,151,100,147,155,141,151,154,56,143,157,155'
			, radix = 8;
		return s.split(',').map(function(c){return String.fromCharCode(parseInt(c, radix));}).join('');
	}
	
	$('ul.clip').click(function(){
		$(this).toggleClass('clip');
	});
	
	
	// initSiteSounds();
	
});

var soundEngin;
var sePlayer;
var bgmPlayer;
	// litroSoundMain();

function initSiteSounds()
{
	if(window.LitroSound == null){
		return false;
	}
	
	//サウンドエンジン
	soundEngin = new LitroSound();
	soundEngin.init();
	//効果音用
	sePlayer = new LitroPlayer();
	sePlayer.init('se');
	// sePlayer.loadSystemSound('litrokeyboard');
	sePlayer.loadPack(1, "20", function(){
		sePlayer.playForKey(0);
	});
	
	//曲用
	bgmPlayer = new LitroPlayer();
	// bgmPlayer.init('bgm');
	
	
}

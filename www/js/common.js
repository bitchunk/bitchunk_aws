$(function(){
	$('#mailprompt').click(function(){
		window.prompt('連絡・質問等はこちら', jq8o());
	});
	
	function jq8o(){
		var s = '143,157,156,164,141,143,164,100,155,141,151,154,56,142,151,164,143,150,165,156,153,56,156,145,164'
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

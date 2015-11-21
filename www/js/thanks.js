/**
 * THANKS
 * Since 2015-10-17 15:30:11
 * @author しふたろう
 * ver 0.00.00
 */

//環境判定

var IMAGE_DIR = './img/resource';

var RECEIVER_URL = {};


var PaformTime = 0; //時間計測
var litroKeyboardInstance = null;
var VIEWMULTI = 3;
var DISPLAY_WIDTH = 160;
var DISPLAY_HEIGHT = 120;
// var CHIPCELL_SIZE = 16;
var CHIPCELL_SIZE = 8;
var UI_SCREEN_ID = 'screen';
var layerScroll = null;
var COLOR_BG = [252, 224, 168, 255];
var COLOR_STEP = [184, 248, 216, 255];
var COLOR_TIME = [248, 216, 120, 255];

var COLOR_NOTEFACE = [184, 248, 184, 255];
var COLOR_NOTEPRINT = [0, 168, 0, 255];
var COLOR_PARAMKEY = [188, 188, 188, 255];
var COLOR_DISABLE = [120, 120, 120, 255];
var COLOR_LINE = [88, 216, 84, 255];
var COLOR_ARRAY = [[248, 120, 88, 255], [252, 168, 68, 255], [248, 184, 0, 255], [88, 216, 84, 255], [60, 188, 252, 255], [152, 120, 248, 255], [248, 120, 248, 255], [248, 88, 152, 255], ];

var USER_ID = 1;
var HU;
var DEBUG_RASTER_DIFF = 0.1;
var DEBUG_RASTER_SIN = 0.7;

function THANKS(){
	return;
}

THANKS.prototype = {
	init : function() {
		var self = this;
		this.debug = null;
		this.uiImageName = 'logo_top';
		this.testImageName = 'top_x1';
		
		// this.litroSound = new LitroSound();
		
		//効果音用
		// this.sePlayer = new LitroPlayer();
		// this.player = new LitroPlayer();
		//

		// this.litroSound.init(CHANNELS_NUM);
		// this.sePlayer.init("se");
		//// this.player.init("edit");
		// this.initSoundEffect();

		//基本キー
		// this.keyControll = new KeyControll();
		// this.initKeys();

		this.loadImages();
		this.initCanvas();
		this.initViewMode();
		this.initWords();
		// this.initEventFunc();
		
		this.drawCount = 0;
		this.stackDraw = [];
	},
	
	initKeys: function(){
		this.keyControll.initDefaultKey('right');
	},
	
	initViewMode: function(){
		var href = window.location.href
			, sound_id = href.match(/[?|&]+sound_id\=([0-9]+)/)
			, multi = href.match(/[?|&]+screen\=([0-9]+)/)
			, buff = href.match(/[?|&]+buff\=([0-9a-zA-Z]+)/)
			, debug = href.match(/[?|&]+debug\=([0-9]+)/)
			, self = this;
			
		if(buff != null){
			PROCESS_BUFFER_SIZE = parseInt(buff[1], 10) == null ? 4096 : buff[1];
			if(this.litroSound.context != null){
				console.log('Process Buffer: ' + PROCESS_BUFFER_SIZE);
				this.litroSound.connectOff();
				this.litroSound.createContext();
				this.litroSound.connectModules(PROCESS_BUFFER_SIZE);
			}
		}
		if(multi != null){
			if(multi[1] == 0){
				this.hiddenScreen = true;
				multi[1] = 1;
			}
			VIEWMULTI = multi[1] | 0;
			// console.log(VIEWMULTI);
		}
		if(sound_id != null){
			this.player.loadFromServer(this.loginParams.user_id, sound_id[1], 
			function(data){
					if(data == null || data === false){
						self.setError(data != null ? data : {error_code: 0, message: 'error'});
						return;
					}
				},
				function(data){
					self.setError(data != null ? data : {error_code: 0, message: 'error'});
				});
		}
		if(debug != null){
			this.debug = new DebugCell();
			this.debug.init(scrollByName('sprite'));
			// this.debugCell = true;
			// window.document.getElementById('screen').addEventListener('mousemove', function(e){
					// var bounds = this.getBoundingClientRect()
						// ;
					// self.debugCellPos.x = (((e.clientX - bounds.left) / VIEWMULTI) / cellhto(1)) | 0;
					// self.debugCellPos.y = (((e.clientY - bounds.top) / VIEWMULTI) / cellhto(1)) | 0;
			// }, false);
			
		}
		return;
	},
	
	initSoundEffect: function(){
		var se = this.sePlayer
			, self = this
			, func = function(){return;}
			, errorFunc = function(){return;}
		;
		se.playOnce = true;
		//[15,16,17]
		// se.loadSoundPackage('15,16,17', func, errorFunc);
		// se.loadSystemSound('litrokeyboard', func, errorFunc);
	},
	
	initEventFunc: function()
	{
	},
	
	initWords: function()
	{
		var word;//, WordPrint = wordPrint;
		word = new WordPrint();
		word.init('8px');
		word.setFontSize('8px');
		word.rowSpace = 0;
		this.word = word;
	},
	
	initCanvas: function()
	{
		makeCanvasScroll();
		
		var bg1 = makeScroll('bg1', false)
			, bg2 = makeScroll('bg2', false)
			, bg3 = makeScroll('bg3', false)
			, bg4 = makeScroll('bg4', false)
			, spr = makeScroll('sprite', false)
			, view = makeScroll('view', false)
			, scr = makeScroll(UI_SCREEN_ID, true)
			;
		scr.clear();
		view.clear();
		bg1.clear();
		bg2.clear();
		bg3.clear();
		bg4.clear();
		spr.clear();
		// document.getElementById('display').getElementsByTagName('img')[0].style('display', 'none');
		document.getElementById('display').getElementsByTagName('img')[0].style.display = 'none';
		document.getElementById('display').style.width = (DISPLAY_WIDTH * VIEWMULTI) + 'px';
		document.getElementById('display').style.height = (DISPLAY_HEIGHT * VIEWMULTI) + 'px';
		
	},
	
	initSprite: function()
	{
		var i
		;
		// this.waveSprite = makePoint(this.uiImageName, 1);
		
		this.word.setFontSize('8px');
		this.cellCursorSprite = makeSprite(this.word.imageName, this.cellCursorSprite);

	},
	
	initFrameSprites: function()
	{
		var img = this.uiImageName, self = this 
			, timg = this.testImageName
			, msq = function(query){return makeSpriteQuery(img, query);}
			, fspr = this.frameSprites
			, ms = function(id){return makeSprite(img, id);}
			, w = this.word
		;
		
		this.frameSprites = {
			logo: msq('0'),
		};
		
		
		this.wordSprites = {
			'♡': w.getSpriteArray('♥'),
			t: w.getSpriteArray('T'),
			h: w.getSpriteArray('H'),
			a: w.getSpriteArray('A'),
			n: w.getSpriteArray('N'),
			k: w.getSpriteArray('K'),
			'　': w.getSpriteArray('　'),
			y: w.getSpriteArray('Y'),
			o: w.getSpriteArray('O'),
			u: w.getSpriteArray('U'),
			'♡2': w.getSpriteArray('♥'),
		};
	},
	
	//リピートchipchunk(Array, Array)
	makeChipChunk: function(name, sprite, repeatRect)
	{
		return {name: name, sprite: sprite, rect: repeatRect};
	},
	presetWordFormat: function(name)
	{
		var word = this.word, form;
		switch(name){
			case 'charboard': form = {size: '8px', scroll: scrollByName('bg1'), maxrows: 2, linecols: this.titleMaxLength, malign: 'vertical'}; break;
		}
		
		word.setFontSize(form.size);
		word.setScroll(form.scroll);
		word.setMaxRows(form.maxrows);
		word.setLineCols(form.linecols);
		word.setMarkAlign(form.malign);
	},
		
	loadImages: function()
	{
		// this.loader.init();
		var self = this, resorce = loadImages([
			 [this.uiImageName, 168, 32],
			 // [this.testImageName, 160, 120],
			 // [this.uiImageName, 16, 16],
			 // [this.snsImageName, 16, 16],
			 // ['font4v6p', 4, 6],
			 ['font8p', 8, 8]], function(){
			self.imageLoaded = true;
			self.initSprite();
			self.initFrameSprites();
			self.drawbgBatch();
			requestAnimationFrame(main);
		});

	},
	
	pushStackDraw: function(name, func)
	{
		this.stackDraw.push({name: name, func: func});
	},
	
	clearStackDraw: function(name, func)
	{
		this.stackDraw = [];
	},

	removeStackDraw: function(name)
	{
		var i, f;
		for(i = 0; i < this.stackDraw.length; i++){
			if(this.stackDraw[i].name == name){
				f = this.stackDraw.splice(i, 1);
				f = null;
				break;
			}
		}
	},
	
	keycheck: function ()
	{
		// if(this.keyControll.getTrig('space')){
			// scrollByName('bg1').screenShot();
			// scrollByName('bg2').screenShot();
			// scrollByName('bg3').screenShot();
			// scrollByName('view').screenShot();
		// }
		// return;
	},
	
	drawStackDraw: function()
	{
		var i;
		for(i = 0; i < this.stackDraw.length; i++){
			this.stackDraw[i].func();
		}
		this.drawCount++;

	},
	
	drawbgBatch: function()
	{
		this.drawTitle();
		this.drawThanks();
	},
	
	drawTitle: function()
	{
		var bg = scrollByName('bg2')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			, y = cto(6)
			, rect = makeRect(cto(0), cto(0), cto(20), cto(5))
		;
		
		dsc(f.logo, 0, rect.y);
		bg.setRasterHorizon(rect.y, 0, y);

		this.pushStackDraw('title', function(){
			var cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, cnt = self.drawCount
			, endcnt = 300
			// , diff = endcnt - cnt
			, diff = cnt
			// , d = Math.sin(diff) * 0.5
			, x
			;
			// if(diff > 0){
				for(i = 0; i < rect.h; i++){
					x = Math.sin((i + diff) * 0.14) * 3;
					// x = Math.sin(i + (cnt * DEBUG_RASTER_SIN)) * 240 * DEBUG_RASTER_DIFF;
					bg.setRasterHorizon(rect.y + i, x, y + i);
				}
			// }else{
					// bg.setRasterHorizon(rect.y, 0, y);
				
			// }
			
			
		});

	},


	drawThanks: function()
	{
		var bg = scrollByName('sprite')
			, f = this.wordSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			, y = cto(6)
			, rect = makeRect(cto(5), cto(13), cto(1), cto(1))
		;
		

		this.pushStackDraw('thanks', function(){
			var cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, cnt = self.drawCount
			, endcnt = 180
			, diff = endcnt - cnt
			, keys = Object.keys(f)
			, len = keys.length
			// , d = Math.sin(diff) * 0.5
			, x
			;
			for(i = 0; i < len; i++){
				bg.drawSpriteChunk(f[keys[i]], rect.x + cto(i), rect.y - (Math.sin((cnt - (i * (Math.PI * 6))) * 0.2) * 2));
			}
			
		});

	},

};

function printDebug(val, row){
		// if(litroKeyboardInstance == null){return;}
		if(!imageResource.isload()){return;}
		var scr = scrollByName('sprite'), ltkb = litroKeyboardInstance
			, word = ltkb.word
			, mc = {x: 0, y: 29};
		;
		if(row == null){
			row = 0;
		}
		if(word == null){
			return;
		}
		word.setFontSize('4v6px');
		word.setMarkAlign('horizon');
		word.setScroll(scr);
		word.setColor(COLOR_WHITE, COLOR_BLACK);
		word.print(val, cellhto(mc.x), cellhto(mc.y - row));
};

function DebugCell(){
	return;
}
DebugCell.prototype = {
	init: function(){
		var self = this;
		this.word = new WordPrint();
		this.word.init();
		this.cellPos = {x: 0, y: 0};
		this.word.setFontSize('8px');
		this.word.setMarkAlign('horizon');
		this.cellSprite = null;

		this.debugCell = true;
		window.document.getElementById(UI_SCREEN_ID).addEventListener('mousemove', function(e){
				var bounds = this.getBoundingClientRect()
					, view = scrollByName(UI_SCREEN_ID)
					, w = DISPLAY_WIDTH, h = DISPLAY_HEIGHT 
					, x = ((((e.clientX - bounds.left) / VIEWMULTI) | 0) - view.x + w) % w
					, y = ((((e.clientY - bounds.top) / VIEWMULTI) | 0) - view.y + h) % h
					;
				self.cellPos.x = (x / cellhto(1)) | 0;
				self.cellPos.y = (y / cellhto(1)) | 0;
				bounds = null;
				view = null;
				
		}, false);

	},
	draw: function(scroll){
		var cto = cellhto
			, cx = this.cellPos.x, cy = this.cellPos.y
			, x = cto(cx), y = cto(cy)
			, wx = cto((cx < 3 ? 3 : cx))
			, wy = cto((cy < 2 ? 2 : cy))
			, fgcol = COLOR_WHITE
		;
		if(this.cellSprite == null){
			this.cellSprite = this.word.getSpriteArray('◯', COLOR_RED);
			// console.log(this.cellSprite);
		}

		this.word.setScroll(scroll);
		scroll.drawSpriteChunk(this.cellSprite, x, y);
		
		this.word.print((cx < 10 ? 'x:0' : 'x:') + cx + '', wx - cto(3), wy - cto(2), fgcol);
		this.word.print((cy < 10 ? 'y:0' : 'y:') + cy + '', wx- cto(3), wy - cto(1), fgcol);
		// console.log(x, y, this.cellPos);
//		this.word.print('おてすと', 20, 20);
		cto = null;
		fgcol = null;
	},
};
	
function mainDraw()
{
	var scr = getScrolls()
		, p, spmax = 64
		, self = TH
	;
	// spr.drawSpriteChunk(self.frameSprites.full, 0, 0);
	// console.log(f.pillar);

	if(self.debug != null){
		self.debug.draw(scr.sprite);
	}
	// HU.drawbgBatch();
	TH.drawStackDraw();
	
	drawCanvasStacks(3200);
	
	scr.bg1.drawto(scr.view);
	scr.bg2.rasterto(scr.view);
	// scr.bg3.rasterto(scr.view);
	scr.sprite.drawto(scr.view);
	scr.sprite.clear();
	scr.view.clear();
	scr.screen.clear();
	screenView(scr.screen,scr.view);
	scr = null;
	self = null;
}

//call at 60fps
function THMain()
{
	// ltkb.test();
	TH.keycheck();
	mainDraw();
	// console.time("key");
	// console.timeEnd("key");
	// HU.playLitro();
	// drawLitroScreen();
};

function main() {
	THMain();
	// keyStateCheck();
	requestAnimationFrame(main);
};


function getClient()
{
	var client
	, agent = navigator.userAgent
	, spDevices = ['iPhone', 'iPad', 'ipod', 'Android']
	, deviceName = ''
	, isSmartDevice = false
	;
	spDevices.map(function(d){
		if(agent.indexOf(d) >= 0){
			deviceName = d;
			isSmartDevice = true;
		}
	});
	client = {isSmartDevice: isSmartDevice, deviceName: deviceName};
	return client;
	
};

window.addEventListener('load', function() {
	var client = getClient()
		, query = location.href.match(/\?([^?/]*)/)
		;
	TH = new THANKS();
		
	if(client.isSmartDevice){
		VIEWMULTI = 2;
	}
	
	
	window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
	
	TH.init();
	query = null;
	// requestAnimationFrame(main);
	removeEventListener('load', this, false);
	
	window.onbeforeunload = function(event){
		return;
		// event = event || window.event;
//		return event.returnValue = 'LitroKeyboardを中断します';
	};
}, false);



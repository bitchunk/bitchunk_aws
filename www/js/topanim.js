/**
 * HITOKUCHIHU TOP ANIMATION Interface
 * Since 2015-10-17 15:30:11
 * @author しふたろう
 * ver 0.00.00
 */

//環境判定
var IS_PRODUCTION = window.location.href.indexOf('ltkb.bitchunk.') >= 0;
var IS_DEVELOPMENT = window.location.href.indexOf('ham78.orz.hm') >= 0;
var IS_LOCAL = window.location.href.indexOf('localhost') >= 0;

var PRODUCTION = 0;
var DEVELOPMENT = 1;
var LOCAL = 2;
var HOST_ID =
	 (IS_PRODUCTION ? PRODUCTION : 0)
	+ (IS_DEVELOPMENT ? DEVELOPMENT : 0)
	+ (IS_LOCAL ? LOCAL : 0)
;
var IMAGE_DIR = './img/resource';

var RECEIVER_URL = {};
RECEIVER_URL[PRODUCTION] = '//ltsnd.bitchunk.net/receiver';
RECEIVER_URL[DEVELOPMENT] = 'http://ham78.orz.hm';
RECEIVER_URL[LOCAL] = 'http://localhost:58105';

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

function HuTop(){
	return;
}

HuTop.prototype = {
	init : function() {
		var self = this;
		this.debug = null;
		this.uiImageName = 'top_sprites';
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
		this.keyControll = new KeyControll();
		this.initKeys();

		this.loadImages();
		this.initViewMode();
		this.initWords();
		this.initCanvas();
		this.initEventFunc();
		
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
		scr.clear(COLOR_BLACK);
		view.clear(COLOR_BLACK);
		bg1.clear(COLOR_BLACK);
		bg2.clear();
		bg3.clear();
		bg4.clear();
		spr.clear();
		// document.getElementById('display').getElementsByTagName('img')[0].style('display', 'none');
		document.getElementById('display').getElementsByTagName('img')[0].style.display = 'none';
		
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
		;
		
		this.frameSprites = {
			full: msq('0'),
			pillar_top: msq('(112 112 113)^3!;116 116 113'),
			pillar_bottom: msq('(114 115 113);(112 112 113)^2'),
			titleboard: msq('(112*20);(149*20)^5!;(112*20)'),
			searface: msq('(149)*20'),
			hitokuchihu: msq('8+4:8+4 12+4:8+4 0+4:12+4 4+4:12+4 8+4:12+4'),
			copyright: msq('128 129 130 135 144 145 146'),
			hu_a_01: msq('52 53 50 51'),
			hu_a_02: msq('52 50 53 51'),
			hu_a_03: msq('52 50 50 51'),
			hu_b: msq('48 49 50 51'),
			
			shaft: msq('102*10'),
			tube: msq('(70;86;135;86|vf)*10'),
			cutcase_bg: msq('(150 150 150 151)^2'),
			cutcase_bottom: msq('7+1:4+4 (8+1:4+4)*4 9+1:4+4 10+1:4+4  (11+1:4+4)*2 12+1:4+4'),
			cutcase_press: msq('117*5 118*3'),
			cutcase_front_l: msq('160;176'),
			cutcase_front_r: msq('163 164;179 135'),
			cutcase_back_l: msq('161 162;177 178'),
			cutcase_back_r: msq('166 167;182 183'),
			cutcase_back_br: msq('165;181'),

			hu_cut: msq('(64;80;96) (66;82;98)*4 (3+3:4+1;3+3:5+1;3+3:6+1)'),
			hu_plane: msq('(64;80;96) (65;81;97)*4 (3+3:4+1;13+3:5+1;3+3:6+1)'),
			hu_rightErase: msq('135^3'),
			conveyor: msq('(131 132*6 133 134)^2'),
			
			tekt_01: msq('0+3:0+3'),
			tekt_02: msq('3+3:0+3'),
			
			comod_01: msq('7+3:0+2'),
			comod_02: msq('10+3:0+2'),
			comod_arm_01: msq('7+3:2+1'),
			comod_arm_02: msq('10+3:2+1'),
			comod_bottom_01: msq('7+3:3+1'),
			comod_bottom_02: msq('10+3:3+1'),
			
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
			 [this.uiImageName, 8, 8],
			 [this.testImageName, 160, 120],
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
		if(this.keyControll.getTrig('space')){
			scrollByName('bg1').screenShot();
			scrollByName('bg2').screenShot();
			scrollByName('bg3').screenShot();
			scrollByName('view').screenShot();
		}
		return;
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
		this.drawBg();
		this.drawTitle();
		this.drawConveyor();
		this.drawHuStream();
		this.drawCutCase();
		this.drawTektMogmog();
		this.drawComodKuru();
		
	},
	
	drawBg: function()
	{
		var bg1 = scrollByName('bg1')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg1.drawSpriteChunk(a, b, c);}
		;
		bg1.clear(COLOR_BG);
		dsc(f.searface, 0, cto(14));
		dsc(f.pillar_top, cto(4), 0);
		dsc(f.pillar_top, cto(12), 0);
		dsc(f.pillar_bottom, cto(1), cto(11));
		dsc(f.pillar_bottom, cto(9), cto(11));
		dsc(f.pillar_bottom, cto(17), cto(11));
		
		dsc(f.titleboard, 0, cto(4));
		// dsc(f.hitokuchihu, cto(0), cto(6));
		dsc(f.copyright, cto(12), cto(10));
		
		
		// dsc(f.tekt_01, cto(6), cto(10.5));
		// dsc(f.tekt_02, cto(9), cto(10.5));
		dsc(f.hu_b, cto(5), cto(13.5));

		// dsc(f.hu_a_01, cto(1), cto(12.5));
		// dsc(f.comod_01, cto(1), cto(10.5));
		// dsc(f.comod_02, cto(13), cto(10.5));
		// dsc(f.comod_arm_01, cto(1), cto(11.5));
		// dsc(f.comod_arm_02, cto(1), cto(11.5));
		// dsc(f.comod_bottom_01, cto(1), cto(13.5));
		// dsc(f.comod_bottom_02, cto(1), cto(13.5));
		// dsc(f.hu_a, cto(13), cto(12.5)+1);
		
		dsc(f.shaft, cto(0), cto(3));
		// dsc(f.tube, cto(0), cto(1));

		dsc(f.cutcase_back_l, cto(11), cto(0));
		
		// dsc(f.cutcase_press, cto(10), cto(0));
		dsc(f.cutcase_bg, cto(14), cto(2));
		// dsc(f.cutcase_bottom, cto(9), cto(1));
		
		// dsc(f.cutcase_front_l, cto(10), cto(0));
		// dsc(f.cutcase_front_r, cto(14.5), cto(0));
		// dsc(f.cutcase_back_r, cto(16.5), cto(0));
		dsc(f.cutcase_back_br, cto(17), cto(0));
		
		// dsc(f.hu_plane, cto(10), cto(11));
		// dsc(f.hu_cut, cto(10), cto(11));
		// dsc(f.conveyor, cto(11), cto(13));
	},
	
	drawCutCase: function()
	{
		var bg = scrollByName('bg3')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
		;
		dsc(f.tube, cto(0), cto(1));
		dsc(f.cutcase_bottom, cto(9), cto(1));
		dsc(f.cutcase_front_l, cto(10), cto(0));
		dsc(f.cutcase_front_r, cto(14.5), cto(0));
		dsc(f.cutcase_back_r, cto(16.5), cto(0));

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
		
		dsc(f.hitokuchihu, 0, rect.y);
		bg.setRasterHorizon(rect.y, 0, y);

		this.pushStackDraw('title', function(){
			var cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, cnt = self.drawCount
			, endcnt = 300
			, diff = endcnt - cnt
			// , d = Math.sin(diff) * 0.5
			, x
			;
			if(diff > 0){
				for(i = 0; i < rect.h; i++){
					x = Math.sin(i + (diff * 0.25)) * diff * 0.15;
					// x = Math.sin(i + (cnt * DEBUG_RASTER_SIN)) * 240 * DEBUG_RASTER_DIFF;
					bg.setRasterHorizon(rect.y + i, x, y + i);
				}
			}else{
					bg.setRasterHorizon(rect.y, 0, y);
				
			}
			
			
		});

	},
	
	drawTektMogmog: function()
	{
		var bg = scrollByName('bg2')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			, y = cto(10.5)
			, rect = makeRect(cto(6), cto(5), cto(3), cto(3))
		;	
		dsc(f.tekt_01, rect.x, rect.y);
		// return;
		this.removeStackDraw('tektmogmog');
		dsc = null;
		bg.setRasterHorizon(rect.y, 0, y);

		this.pushStackDraw('tektmogmog', function(){
			var cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, cnt = ((self.drawCount / 20) | 0) % 20
			;
			bg.setRasterHorizon(rect.y, 0, y);
			if(cnt < 4){
				bg.clear(null, rect);
				dsc(f.tekt_01, rect.x, rect.y);
			}else if(cnt < 6){
				bg.clear(null, rect);
				dsc(f.tekt_02, rect.x, rect.y);
			}else{
				bg.clear(null, rect);
				dsc(cnt % 2 == 1 ? f.tekt_01 : f.tekt_02, rect.x, rect.y);
			}
			dsc = null;
			cto = null;
		});
		cto = null;
	},
	
	drawComodKuru: function()
	{
		var bg = scrollByName('bg2')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			, comdY = cto(0)
			, y = cto(10.5)
			, rect = makeRect(cto(1), cto(5), cto(4), cto(4))
			, comaY = rect.y + cto(2)
			, combY = rect.y + cto(3)
			, huY = rect.y + cto(2) + 2
			, pat1 = function(){
				dsc(f.comod_bottom_02, rect.x, combY);
				dsc(f.hu_a_01, rect.x, huY);
				dsc(f.comod_02, rect.x, rect.y + 1);
				dsc(f.comod_arm_02, rect.x, comaY + 1);
			}
			, pat2 = function(){
				dsc(f.comod_bottom_02, rect.x, combY);
				dsc(f.hu_a_01, rect.x, huY);
				dsc(f.comod_02, rect.x, rect.y + 1);
				dsc(f.comod_arm_02, rect.x + 1, comaY + 1);
			}
			, pat3 = function(){
				dsc(f.comod_bottom_01, rect.x, combY);
				dsc(f.hu_a_02, rect.x, huY + 1);
				dsc(f.comod_02, rect.x, rect.y + 1);
				dsc(f.comod_arm_01, rect.x, comaY + 1);
			}
			, pat4 = function(){
				dsc(f.comod_bottom_01, rect.x, combY);
				dsc(f.hu_a_03, rect.x, huY);
				dsc(f.comod_01, rect.x, rect.y);
				dsc(f.comod_arm_01, rect.x + 1, comaY);
			}
			, pat5 = function(){
				dsc(f.comod_bottom_01, rect.x, combY);
				dsc(f.hu_a_01, rect.x, huY);
				dsc(f.comod_01, rect.x, rect.y);
				dsc(f.comod_arm_01, rect.x, comaY);
			}
			, anmArray = [
				pat1, pat2, pat3, pat4, pat5,
				null, null, null, null, null, null, null, null, null, null,
				pat1, pat2, pat3, pat4, pat5,
				pat1, pat2, pat3, pat4, pat5,
				pat1, pat2, pat3, pat4, pat5,
				pat1, pat2, pat3, pat4, pat5,
				null, null, null, null, null, null, null, null, null, null,
				null, null, null, null, null, null, null, null, null, null,
				null, null, null, null, null, null, null, null, null, null,
				null, null, null, null, null, null, null, null, null, null,
				null, null, null, null, null, null, null, null, null, null,
			]

		;	
		dsc(f.hu_a_01, rect.x, huY + 1);
		dsc(f.comod_01, rect.x, rect.y + 1);
		dsc(f.comod_arm_01, rect.x, comaY);
		dsc(f.comod_bottom_01, rect.x, combY);
// return;
		this.removeStackDraw('comodkuru');
		// dsc = null;
		bg.setRasterHorizon(rect.y, 0, y);
		
		this.pushStackDraw('comodkuru', function(){
			var cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, cnt = ((self.drawCount / 7) | 0) % 85
			;
			bg.setRasterHorizon(rect.y, 0, y);
			if(anmArray[cnt] != null){
				bg.clear(null, rect);
				anmArray[cnt]();
			}

			dsc = null;
			cto = null;
		});
		cto = null;
	},
	
	drawHuStream: function()
	{
		var bg = scrollByName('bg2')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			, huY = cto(11)
			, pressY = cto(14)
		;
		// dsc(f.conveyor, cto(11), cto(4)); + 3
		// dsc(f.hu_plane, cto(0), huY);// + 3
		dsc(f.cutcase_press, cto(10), pressY); // +1
		this.removeStackDraw('hustream');
		dsc = null;
// return;
		this.pushStackDraw('hustream', function(){
			var i, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, len = 600
			, firstWait = 120
			, caseIn = 40 + firstWait
			, caseWait = 80 + caseIn
			, cutDown = 10 + caseWait
			, huDown = 20 + cutDown
			, conveyor = 256 + huDown
			, x = cto(3)
			, pos, rate, posx
			, cnt = self.drawCount % conveyor
			// , height = cto(3)
			;
			if(cnt < firstWait){
				if(cnt == 0){dsc(f.hu_plane, 0, huY);}
				rate = cto(4) / firstWait;
				pos = cto(4) - (rate * cnt) - 0;
				bg.setRasterHorizon(huY, 0, -256);
				bg.setRasterHorizon(pressY, 0, pos | 0);
			}else if(cnt < caseIn){
				cnt = (cnt - firstWait);
				rate = cto(17) / (caseIn - firstWait);
				pos = (rate * cnt) - cto(7);
				bg.setRasterHorizon(huY, pos | 0, cto(2));
				bg.setRasterHorizon(pressY, 0, 0);
			}else if(cnt < caseWait){
				if(cnt == caseIn){
					bg.clear(null, makeRect(cto(7), huY, cto(1), huY));
					dsc(f.hu_cut, cto(0), huY);
				}
				bg.setRasterHorizon(huY, cto(10), cto(2));
				bg.setRasterHorizon(pressY, 0, 0);
			}else if(cnt < cutDown){
				cnt = cnt - caseWait;
				rate = cto(4) / (cutDown - caseWait);
				pos = (rate * cnt);
				bg.setRasterHorizon(pressY, 0, pos | 0);
				bg.setRasterHorizon(huY, cto(10), cto(2));
			}else if(cnt < huDown){
				cnt = cnt - cutDown;
				rate = (cto(9)) / (huDown - cutDown);
				pos = cto(2) + (rate * cnt) - 2;
				bg.setRasterHorizon(huY, cto(10),  pos | 0);
				bg.setRasterHorizon(pressY, 0, cto(4));
			}else if(cnt < conveyor){
				cnt = cnt - huDown;
				rate = cto(4) / (conveyor - huDown);
				pos = cto(11) + (rate * cnt) - 2;
				rate = cto(12) / (conveyor - huDown);
				posx = cto(10) - (rate * cnt);
				bg.setRasterHorizon(huY, posx | 0, pos | 0);
				bg.setRasterHorizon(pressY, 0, cto(4));
			}
			// bg.setRasterHorizon(huY + height, 0, 0);
			dsc = null;
			cto = null;
		});
		cto = null;
	},
	
	drawConveyor: function(){
		var bg = scrollByName('bg2')
			, f = this.frameSprites
			, cto = cellhto
			, dsc = function(a, b, c){bg.drawSpriteChunk(a, b, c);}
			, self = this
			,conY = cto(9)
		;
		dsc(f.conveyor, cto(11), conY);
		// return;
		// dsc(f.conveyor, cto(11), cto(13));
		dsc = null;
		this.pushStackDraw('conveyor', function(){
			var i, cto = cellhto
			, len = cto(2)
			, x = cto(1), y = cto(13), start
			, rasdis = 3
			, cuffpos = 4
			, rasdis_b = 1
			, offset_b = cuffpos + 2
			, pos = ((self.drawCount / 8) | 0) % len
			;
			
			for(i = 0; i < len; i++){
				start = ((i - pos + len) % 4);
				if(i < cuffpos){
					// bg.setRasterHorizon(y + start, 256, 0);
					bg.setRasterHorizon(start + conY, -(i * rasdis_b), y - i + offset_b + 1);
				}else{
					start = ((i - pos + len) % (len - cuffpos)) + cuffpos;
					bg.setRasterHorizon(start + conY, x - (i * rasdis), y + i);
				}
			}
			cto = null;
		});
		cto = null;
	}

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
		, self = HU
	;
	// spr.drawSpriteChunk(self.frameSprites.full, 0, 0);
	// console.log(f.pillar);

	if(self.debug != null){
		self.debug.draw(scr.sprite);
	}
	// HU.drawbgBatch();
	HU.drawStackDraw();
	
	drawCanvasStacks(3200);
	
	scr.bg1.drawto(scr.view);
	scr.bg2.rasterto(scr.view);
	scr.bg3.rasterto(scr.view);
	scr.sprite.drawto(scr.view);
	scr.sprite.clear();
	screenView(scr.screen,scr.view);
	scr = null;
	self = null;
}

//call at 60fps
function HuMain()
{
	// ltkb.test();
	HU.keycheck();
	mainDraw();
	// console.time("key");
	// console.timeEnd("key");
	// HU.playLitro();
	// drawLitroScreen();
};

function main() {
	HuMain();
	keyStateCheck();
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
	HU = new HuTop();
		
	if(client.isSmartDevice){
		VIEWMULTI = 2;
	}
	
	
	window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
	
	HU.init();
	query = null;
	
	// requestAnimationFrame(main);
	removeEventListener('load', this, false);
	
	window.onbeforeunload = function(event){
		return;
		// event = event || window.event;
//		return event.returnValue = 'LitroKeyboardを中断します';
	};
}, false);



<style lang="less" scoped>
@import url(./styles/config.less);
.player{
    position: absolute;
    left:0px;
    /*top:240px;*/
    bottom:0px;
    width:100%;
    z-index: 9999;
    user-select:none;
    @media (max-width:768px) {
        opacity:1;
    }
    &:hover .panel{
        opacity: 1;
    }
    .spectrum{
        position: absolute;
        left:0px;
        bottom:0px;
        height:70px;
        width:300px;
        pointer-events: none;
        @media (max-width:768px) {
            width:100%;
        }
    }
    .control{
        position: absolute;
        bottom:50px;
        left:50%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition:all 600ms cubic-bezier(.4,0,0,1);
        transform: translate(-50%,0px);
        @media (max-width:768px) {
            opacity: .6;
        }
        span{
            margin:0 5px;
            cursor: pointer;
            font-size: 10px;
            color:#fff;
            &.time{
                width:50px;
                text-align: center;
                text-overflow: ellipsis;
                overflow: hidden;
            }
            img{
                width:18px;
                height:18px; 
            }
            &.play {
                img{
                    width:40px;
                    height:40px;
                }
            }
        }
    }

    .panel {
        position: absolute;
        bottom:20px;
        left:5%;
        right:5%;
         transition:all 600ms cubic-bezier(.4,0,0,1);
         opacity: .5;
         .name{
            font-family:DIN,"HelveticaNeue","Arial","PingFang SC","Hiragino Sans GB","STHeiti","Microsoft YaHei","WenQuanYi Micro Hei",sans-serif;
            font-size: 10px;
            line-height: 20px;
            color: #fff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space:nowrap;
            height: 20px;
            .time{
                float: right;
                tt{
                    font-size: 8px;
                }
            }
        }

        .progress{
            height:2px;
            border-radius: 2px;
            background: rgba(255,255,255,.5);
            position: relative;
            & > span.loaded{
                display: block;
                width:0%;
                height: 100%;
                border-radius: 2px;
                background:#fff;
                position: absolute;
                left:0;
                top:0;
            }
            & > div{
                width:0%;
                height: 100%;
                border-radius: 2px;
                background: @color-primary;
                position: absolute;
                left:0;
                top:0;

            }
            & > span.circle {
                content:"";
                position: absolute;
                left:0px;
                top:-4px;
                width:10px;
                height:10px;
                background:#fff;
                border-radius: 50%;
                transform:translateX(-5px);
            }
        }
    }
 
}
</style>

<template>
<div class="player">
    <audio crossOrigin="anonymous" id="audio" :src="url" type="audio/mpeg"></audio>
    <canvas class="spectrum" id="spectrum"></canvas>
    <div class="control">
        <span class="loop" @click.stop="loopToggle" role="button">
            <template v-if="loop"><img src="./images/player/loop.svg" width="14"  alt="循环播放"></template>
            <template v-else><img src="./images/player/loopone.svg" width="14"  alt="单曲循环"></template>
        </span>
        <span @click.stop="prev" role="button"><img class="prev" src="./images/player/prev.svg" alt="上一曲"></span>
        <span class="play" @click.stop="toggle" role="button">
            <template v-if="autoplay"><img src="./images/player/pause.svg" alt="暂停"></template>
            <template v-else><img src="./images/player/play.svg" alt="播放"></template>
        </span>
        <span @click.stop="next" role="button"><img class="next" src="./images/player/next.svg" alt="下一曲"></span>
        <span @click.stop="playlist" role="button"><img class="next" src="./images/player/playlist.svg" alt="播放列表"></span>
    </div>

    <div class="panel">   
        <div class="name">
            <span class="time" v-if="status">
                <template v-if="!loading">{{time}}</template>
                <tt v-else>LOADING...</tt>
            </span>
            {{name}}
        </div>
        <div class="progress">
            <span class="loaded" :style="{width:loaded+'%'}"></span>
            <div :style="{width:progress+'%'}"></div>
            <span class="circle" :style="{left:progress+'%'}"></span>
        </div>
    </div>

</div>
</template>

<script>
import passive from './scripts/passive'

export default {
    name:'player',
    data() {
        return {
            active:true,
            status:false,
        	audio:null,//播放器对象
        	autoplay:false,//是否自动播放
            mute:false,
        	time:"00:00",//显示时间
        	name:"",
        	url:"",
            loaded:0,
        	progress:0,
        	current:0,
            loop:true,
        	items:[],
            loading:true,
        }
    },
    created(){
        if(window.localStorage.player!=undefined){
            this.autoplay=(window.localStorage.player=='true')?true:false
        }
        if(window.localStorage.loop!=undefined){
            this.loop==window.localStorage.loop==="true"
        }
        if(this.isIOS()){
            this.autoplay=false
        }
        let that=this;
        document.addEventListener("keydown",function(e){
            if(e.target.nodeName=="TEXTAREA"||e.target.nodeName=="INPUT"){
                return false
            }
            if([32,37,38,39,40].indexOf(e.keyCode)!=-1){
                e.preventDefault()
                let code=e.keyCode
                if(code==32){
                   that.toggle() 
                }
                if(code==37){
                    that.forward(false)
                }
                if(code==38){
                   that.prev() 
                }
                if(code==39){
                    that.forward(true)
                }
                if(code==40){
                   that.next() 
                }
            }
        },passive)
    },
    mounted(){
        bus.$on("select",index=>{
            this.select(index)
        })
 		this.init()
    },
    methods:{
        isIOS(){
            if(/(iphone|ios|iPad)/i.test(navigator.userAgent)){
                return true
            }
        },
        init(){
            let url=this.$root.api("/music/");
            let params={}
            if(localStorage.token!=undefined){
                params.token=localStorage.token
            }
            this.$http.post(url,params).then(response=> {
                let data=response.data
                // console.log(data)

                if (data.code==200) {
                    data.result.sort(function(){
                        return  Math.random()>0.5?-1:1;
                    })
                    window.playlistData=data.result
                    bus.$emit("playlist:reload",data.result)
                    this.items=data.result
                    this.audio=document.getElementById('audio')
                    // console.log(this.getRandIndex())
                    this.set(0)
                    window.AudioContext=window.AudioContext||window.webkitAudioContext
                    let context=new AudioContext()
                    let analyser = context.createAnalyser()
                    let source = context.createMediaElementSource(this.audio)//把webAudio作为音源
                    source.connect(analyser)//链接频谱器
                    source.connect(context.destination)//链接喇叭
                    this.spectrum(analyser)
                    if(this.autoplay){
                        // 火狐不会自动播放
                        if(window.mozPaintCount>0)this.play()
                        this.audio.addEventListener("canplay",e=>{
                            // console.log("canplay")
                            this.play()
                            this.loading=true
                        },passive) 
                    }else{
                        this.status=false
                    }

                    this.audio.addEventListener("ended",e=>{
                        if(this.loop){
                            this.next()
                        }else{
                            this.play()
                        }
                    },passive)

                    this.audio.addEventListener("timeupdate",e=>{
                        if(this.audio.currentTime){
                            if(this.audio.currentTime>0){
                                this.loading=false
                            }
                            this.time=this.format(this.audio.currentTime)
                            this.progress=this.audio.currentTime/this.audio.duration*100
                        }
                    }, passive);

                    this.audio.addEventListener('progress', () => {
                        const percentage = this.audio.buffered.length ? this.audio.buffered.end(this.audio.buffered.length - 1) / this.audio.duration : 0;
                        this.loaded=percentage*100
                    },passive);

                    this.audio.addEventListener('abort', e=>{
                        this.pause()
                    },passive);
                }
            })
        },
        getRandIndex(){
            return Math.random()*this.items.length-1>>0
        },
        statusToggle(mode){
            if(mode.button===undefined){
                this.active=mode
            }else{
                this.active=!this.active
            }
            if(this.active){
                window.clearTimeout(window.player)
                window.player=window.setTimeout(()=>{
                   this.active=false
                },60000)
                
            }
        },
    	prev(){
            this.loading=true
            this.time="00:00"
            this.progress=0
    		this.current--
    		if(this.current<0)this.current=this.items.length-1
    		this.set(this.current)
            this.$nextTick(()=>{
                this.play()
            })
    	},
    	next(){
            this.loading=true
            this.time="00:00"
            this.progress=0
            this.current++
            if(this.current>this.items.length-1)this.current=0
            this.set(this.current)
            this.play()
            // this.statusToggle(true)
 
    	},
        loopToggle(){
            this.loop=!this.loop
            window.localStorage.loop=!this.loop
        },
        select(index){
            this.loading=true
            this.set(index)
            if(!this.autoplay){
                this.play()
            }
        },
    	set(index){
            if(this.items.length==0 || this.items[index]==undefined){
                return false;
            }
            this.audio.pause()  
            this.audio.currentTime = 0;
            this.time="00:00"
            this.progress=0
            this.current=index
            window.playlistCurrent=index
            bus.$emit("playlist:current",index)
            this.url=this.items[index].url
    		this.name=this.items[index].name
    	},
    	play(){
            this.autoplay=true
            this.status=true
            
            window.localStorage.player=true
            window.setTimeout(()=>{
                if (this.audio.paused) {
                    this.audio.play();
                }
            },500)
    	},
        playlist(){
            bus.$emit("playlist")
        },
        toggle(){
            if(this.autoplay){
                this.pause()
            }else{
                this.play()
            }
        },
        muting(){
            if(this.isIOS()){
                alert('iPhone/iPad由于系统限制无法静音')
                return
            }
            this.$nextTick(()=>{
                if(this.mute){
                    this.audio.volume=1
                    this.audio.muted=false
                    this.mute=false
                }else{
                    this.audio.volume=0
                    this.audio.muted=true
                    this.mute=true
                }
            })
        },
        // 画频谱
        spectrum(analyser) {
            let canvas = document.getElementById('spectrum');
            let cwidth = canvas.width
            let cheight = canvas.height
            let meterWidth = 2 //频谱条宽度
            let gap = 2 //频谱条间距
            let capHeight = 2
 
            let meterNum = 100 //频谱条数量
            let capYPositionArray = [] //将上一画面各帽头的位置保存到这个数组
            let ctx = canvas.getContext('2d')
            ctx.globalAlpha=0.6;
            // // 播放器渐变色条
            // let gradient = ctx.createLinearGradient(0, 0, 0, 40)
            // gradient.addColorStop(1, '#4ba96a')
            // gradient.addColorStop(0, '#7AAD8B')
            let drawMeter = function() {
                var array = new Uint8Array(analyser.frequencyBinCount);
                analyser.getByteFrequencyData(array)
                var step = Math.round(array.length / meterNum); //计算采样步长
                ctx.clearRect(0, 0, cwidth, cheight)
                for (var i = 0; i < meterNum; i++) {
                    var value = array[i * step]; //获取当前能量值
                    if (capYPositionArray.length < Math.round(meterNum)) {
                        capYPositionArray.push(value); //初始化保存帽头位置的数组，将第一个画面的数据压入其中
                    };
                    ctx.fillStyle = "#fff"
                    //开始绘制帽头
                    if (value < capYPositionArray[i]) { //如果当前值小于之前值
                        //则使用前一次保存的值来绘制帽头
                        ctx.fillRect(i * 3, cheight - (--capYPositionArray[i])+90, meterWidth, capHeight); 
                    } else {
                         //否则使用当前值直接绘制
                        ctx.fillRect(i * 3, cheight - value+90, meterWidth, capHeight);
                        capYPositionArray[i] = value;
                    };
                    //开始绘制频谱条
                    // ctx.fillStyle ="#ff"+Number(Math.min(Math.max(i+10,5)*3,250)).toString(16)+"00"
                    ctx.fillStyle = "#fff"
                    ctx.fillRect(i * 3, cheight - value + capHeight+90, meterWidth, cheight*2);
                }
                requestAnimationFrame(drawMeter);
            }
            requestAnimationFrame(drawMeter);
        },
    	pause(){
            if (!this.audio.paused) {
    			this.autoplay=false
                this.status=false
                window.localStorage.player=false
                window.setTimeout(()=>{
                    this.audio.pause()
                },150)
            }
    	},
    	forward(action){
            if(action){
                this.audio.currentTime+=5
            }else{
                this.audio.currentTime-=5                
            }
            window.setTimeout(()=>{
                if(this.audio.currentTime){
                    this.audio.currentTime= Math.floor(percent * this.audio.duration)
                }
                
               this.play()     
            },100)
            
 
    	},
        changeProgress(e){
            
            let offsetX=e.offsetX || e.layerX
            let target=e.srcElement||e.originalTarget
            let percent=offsetX / parseInt(getComputedStyle(e.target).getPropertyValue("width"))
 
            this.$nextTick(()=>{
                this.progress=percent*100
                if(this.audio.currentTime){
                    this.audio.currentTime= parseFloat(Math.floor(percent * this.audio.duration))
                }
                this.play()
            })
        },
        format(number) {
            let minute = parseInt(number / 60)
            let second = parseInt(number % 60)
                minute = minute >= 10 ? minute : "0" + minute
                second = second >= 10 ? second : "0" + second
            return minute + ":" + second
        },
    }
} 
</script>
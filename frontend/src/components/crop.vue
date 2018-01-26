<style scoped>
.crop{
    position: relative;
    width: 100%;
    height:100%;
    cursor: pointer;
    border-radius: inherit;
    background-color:rgba(55, 61, 73, 1);
}
 
.crop>input[type=file]{
    position: absolute;
    left:0;
    top:0;
    right:0;
    bottom:0;
    opacity: 0;
    cursor: pointer;
}
.crop .progress{
    position: absolute;
    left:0;
    bottom:0;
    right:0;
    top:0;
    width:100%;
    box-sizing:border-box;
    background-color:rgba(0, 0, 0,.8);
}
.crop .progress span{
    box-sizing: border-box;
    display: block;
    height:100%;
    width:0%;
    border-bottom:5px solid rgba(164, 208, 99,.8);
}
.crop .progress em{
    left:50%;
    top:50%;
    transform: translate(-50%,-50%);
    font-size:.8rem;
    line-height: 100%;
    color:#fff;
}
.crop section{
    display:block;
    position: relative;
    overflow: hidden;
/*    -webkit-user-select:none;
    user-select:none;*/
    border-radius:3px 3px 0 0;
}
.crop section.none{
    pointer-events: none;
}
.crop section.grab{
    cursor:grab;
    cursor:-moz-grab;
    cursor:-webkit-grab;
}
.crop section.grabbing{
    cursor:grabbing;
    cursor:-moz-grabbing;
    cursor:-webkit-grabbing;
}
.crop section>img{
    position: absolute;
}
.loading{
    position: absolute;
    left:50%;
    top:50%;
    transform: translate(-50%,-50%);
}
.v-beat{
    -webkit-animation: v-beatStretchDelay 0.7s infinite linear;
            animation: v-beatStretchDelay 0.7s infinite linear;
    -webkit-animation-fill-mode: both;
              animation-fill-mode: both;
    display: inline-block;
    width:10px;
    height:10px;
    background-color: #fff;
    border-radius: 50%;
}
.v-beat-odd{
    -webkit-animation-delay: 0s;
    animation-delay: 0s;
}
.v-beat-even{
    -webkit-animation-delay: 0.35s;
    animation-delay: 0.35s;
}
@-webkit-keyframes v-beatStretchDelay{
    50%{
        -webkit-transform: scale(0.75);          
        opacity: 0.2;
    }
    100%{
        -webkit-transform: scale(1);         
        opacity: 1;
    }
}
@keyframes v-beatStretchDelay{
    50%{
        transform: scale(0.75);           
        opacity: 0.2;
    }
    100%{
        transform: scale(1);          
        opacity: 1;
    }
}
 
.crop input[type=range] {
    -webkit-appearance: none;
    position: absolute;
    bottom:20px;
    left:50%;
    transform: translateX(-50%);
}
.crop input[type=range]:focus {
  outline: none;
}
.crop input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 2px;
  cursor: pointer;
  background: #ccc;
  border-radius: 2px;
}
.crop input[type=range]::-webkit-slider-thumb {
    height:20px;
    width: 20px;
    border-radius: 10px;
    background: rgba(53, 215, 187, 1);
    border:2px solid rgba(53, 215, 187, .8);
    cursor: pointer;
    -webkit-appearance: none;
    margin-top: -10px;
    }
 
.crop input[type=range]::-moz-range-track {
    width: 100%;
    height: 2px;
    cursor: pointer;
    background: rgba(53, 215, 187, .8);
}
.crop input[type=range]::-moz-range-thumb {
    height:16px;
    width:16px;
    border-radius: 8px;
    border:2px solid rgba(53, 215, 187, .8);
    background:rgba(53, 215, 187, 1);
    cursor: pointer;
}
.crop input[type=range]::-ms-track {
    width: 100%;
    height: 1px;
    cursor: pointer;
    background: transparent;
    border-color: transparent;
    color: transparent;
}
.crop input[type=range]::-ms-fill-lower {
    background: rgba(0, 0, 0, 0.5);
    border: 0px solid rgba(200, 200, 200, 0.2);
    border-radius: 0px;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, 0), 0px 0px 0px rgba(13, 13, 13, 0);
}
.crop input[type=range]::-ms-fill-upper {
    background: rgba(0, 0, 0, 0.5);
    border: 0px solid rgba(200, 200, 200, 0.2);
    border-radius: 0px;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, 0), 0px 0px 0px rgba(13, 13, 13, 0);
}
.crop input[type=range]::-ms-thumb {
    height: 16px;
    width: 16px;
    border-radius: 8px;
    background: #ffffff;
    cursor: pointer;
    height: 1px;
}
.crop input[type=range]:focus::-ms-fill-lower {
    background: rgba(0, 0, 0, 0.5);
}
.crop input[type=range]:focus::-ms-fill-upper {
    background: rgba(0, 0, 0, 0.5);
}
.crop .fn{
    position: absolute;
    right:10px;
    top:10px;
    font-size: 12px;
    background:rgba(53, 215, 187, .8);
    padding:6px 20px;
    border-radius: 20px;
    color:#fff;
}
</style>

<template>
 
<div class="crop" :style="{width:width+'px',height:height+'px'}" >
    <slot name="content" v-if="!edit"></slot>
    <input type="file" class="upload" @change="fileChange" accept="image/jpeg" v-show="!edit">

    <section  v-show="status" :style="{width:width+'px',height:height+'px'}" 
    :class="{'grab':grabbing==false,'grabbing':grabbing,'none':!edit}">
        <img 
        :style="{width:scaleWidth+'px',height:scaleHeight+'px',left:scaleX+'px',top:scaleY+'px'}"
        @mousedown.prevent="cropDragBegin"
        @mousemove.prevent="cropDragMove"  
        @mouseup.prevent="cropDragEnd"

        @touchstart.prevent="cropDragBegin"
        @touchmove.prevent="cropDragMove"  
        @touchend.prevent="cropDragEnd"
        :src="base64">
    </section>
 
     <div class="loading" v-if="loading">
        <div class="v-beat v-beat-odd"></div>
        <div class="v-beat v-beat-even"></div>
        <div class="v-beat v-beat-odd"></div>
    </div>
    <input v-show="edit" type="range" :value="scale" min="100" max="200" step="1" @input="cropZoom">
    <span class="fn" @click="edit=true" v-show="!edit&&base64!=''">编辑</span>
    <span class="fn" @click="cropOK" v-show="edit">完成</span>
</div>
 
</template>

<script>
/**
 * vue-crop (Vue 2.x)
 * E-mail   :129@jinzhe.net
 * website  :http://jinzhe.net
 * author   :zee
 * date     :2016/11/07
 * events   @select,@progress,@error,@success
 */
export default {

    props: {
        server: {
            type: String,
            require:true
        },
        
        params:{// POST Params 
            type:Object,
            default:()=>{
                return {}
            }
        },
        filename: {// Upload input filename,used for server side get the file stream.
            type: String,
            default:"file"
        },
        file: {  // Used for binding parent component data and show image preview.
            type: String,
            default:"",
        },
        header:{
            type:Object,
            default:()=>{
                return {}
            }
        },
        width:{
            type:Number,
            default:200,
        },
        height:{
            type:Number,
            default:200,
        },
        ok: {
            type: String,
            default: 'OK'
        },
 
        quality:{
            type:Number,
            default:0.8,
        },
 
    },
    data(){
        return {
            isMobile:/(iphone|ios|android|iPad)/i.test(navigator.userAgent),
            files:[],
            input:null,
            edit:false,
            loading:false,
            base64:"",
            status:false,
            scale:100,//缩放
            scaleX:0,
            scaleY:0,
            scaleLength:0,
            scaleWidth:0,
            scaleHeight:0,
            drag:false,
            grabbing:false,
            beginX:0,
            beginY:0,
            beginLength:0,
            img:null,
            canvas:null,
            context:null,
        }
    },
    methods:{
        fileChange(e){
            this.$emit("select",e)
            this.input=e.target
            if(e.target.files.length==0)return false
            this.loading=true
            // 读取本地图片转成base64显示到页面待使用
            let fr=new FileReader()
            fr.onload=e=>{
                this.img=new Image()
                this.img.src=fr.result
                this.img.onload=()=>{
                    // 按原始图片比例优先使用小值设置最低值
                    if(this.img.naturalWidth>this.img.naturalHeight){
                        this.scaleHeight=this.height
                        this.scaleWidth=Math.round(this.scaleHeight*this.img.naturalWidth/this.img.naturalHeight)
                    }else{
                        this.scaleWidth=this.width
                        this.scaleHeight=Math.round(this.scaleWidth*this.img.naturalHeight/this.img.naturalWidth)
                    }
                    this.scaleX=-Math.round((this.scaleWidth-this.width)/2)
                    this.scaleY=-Math.round((this.scaleHeight-this.height)/2)
             
                    this.base64=fr.result
                    
                    // 渲染到canvas
                    this.canvas=document.createElement("canvas")
                    this.canvas.width=this.width
                    this.canvas.height=this.height
                    this.canvas.setAttribute("style","position:absolute;left:0;top:0;width:0;height:0;opacity:0;pointer-evnets:none;z-index:99999")
                    this.context=this.canvas.getContext("2d")
                    this.cropToCanvas()
                    document.body.appendChild(this.canvas)
                    this.loading=false
                    this.status=true
                    this.edit=true
                }
            }
            fr.readAsDataURL(e.target.files[0])
        },
        getFiles(e) {
            e.stopPropagation();
            e.preventDefault();
            this.files = e.target.files || e.dataTransfer.files
            let _exts=this.ext.split(",")
            let _mimes=[]
            if(_exts.length>0){
                _exts.forEach(v=>{
                    if(this.mimes[v]!=undefined){
                        _mimes.push(this.mimes[v])
                    }
                })
            }
            for (var i = 0, file; file = this.files[i]; i++) {
                if (file.size > this.limit||!_mimes.includes(file.type)) {
                    this.removeFile(file.name)
                }
            }
        },
        removeFile(name) {
            var newFiles = []
            for (var i = 0, file; file = this.files[i]; i++) {
                if (file.name != name)newFiles.push(file)
            }
            this.files = newFiles
        },

        cropDragBegin(e){
            if(!this.edit)return false
            this.drag=true
            this.grabbing=true
            this.beginX=e.pageX-e.target.offsetLeft
            this.beginY=e.pageY-e.target.offsetTop
            if(this.isMobile){
                if(e.touches.length==2){
                    this.beginLength=this.beginLength==0?this.cropTouchData(e).length:this.beginLength
                }
                document.addEventListener('touchmove',this.cropDragMove,false)
                document.addEventListener('touchend',this.cropDragEnd,false)
            }else{
                document.addEventListener('mouseover',this.cropDragMove,false)
                document.addEventListener('mouseup',this.cropDragEnd,false)
            }
        },
        cropDragMove(e){
            if(!this.edit)return false
            if(!this.drag)return false
            if(this.isMobile){
                if(e.touches.length==2){//放大
                    this.scaleLength=this.cropTouchData(e).length
                    this.scale=Math.min(200,Math.max(100,this.scaleLength/this.beginLength*100))
                    this.cropZoom()
                }else{//移动
                    this.scaleX= e.pageX - this.beginX
                    this.scaleY= e.pageY - this.beginY
                }
            }else{
                this.scaleX= e.pageX - this.beginX
                this.scaleY= e.pageY - this.beginY
            }
            this.cropLimit()
        },
        cropDragEnd(e){
            if(!this.edit)return false
            this.drag=false
            this.grabbing=false
            this.cropToCanvas()
            if(this.isMobile){
                document.removeEventListener('touchmove',this.cropDragMove,false)
                document.removeEventListener('touchend',this.cropDragEnd,false)
            }else{
                document.removeEventListener('mouseover',this.cropDragMove,false)
                document.removeEventListener('mouseup',this.cropDragEnd,false)
            }
        },
        cropToCanvas(){
            this.context.clearRect(0,0,this.width,this.height)
            let sx= Math.round(Math.abs(this.scaleX)*this.img.naturalWidth/this.scaleWidth) //图像源x坐标
            let sy= Math.round(Math.abs(this.scaleY)*this.img.naturalHeight/this.scaleHeight) //图像源y坐标
            let sw=this.width*this.img.naturalWidth/this.scaleWidth
            let sh=this.height*this.img.naturalHeight/this.scaleHeight
            this.context.drawImage(this.img,sx,sy,sw,sh,0,0,this.width,this.height/this.cropGetRatio())
        },
        cropZoom(e){
            if(!this.edit)return false
            this.scale=e.target.value
            let s=this.scale/100
            let osx=this.scaleX-this.height/2
            let osy=this.scaleY-this.height/2
            let os
            // 按原始图片比例优先使用小值设置最低值
            if(this.img.naturalWidth>this.img.naturalHeight){
                os=this.scaleHeight/this.height
                this.scaleHeight=this.height*s
                this.scaleWidth=this.scaleHeight*this.img.naturalWidth/this.img.naturalHeight
            }else{
                os=this.scaleWidth/this.width
                this.scaleWidth=this.width*s
                this.scaleHeight=this.scaleWidth*this.img.naturalHeight/this.img.naturalWidth
            }
            this.scaleX=(osx)*s/os+this.width/2
            this.scaleY=(osy)*s/os+this.height/2
            this.cropLimit()
            this.cropToCanvas()
        },
        // 获取多点触控
        cropTouchData(e){
            if(e.touches.length<2){
                return false
            }
            let x1 = e.touches[0].pageX
            let x2 = e.touches[1].pageX
            let x3=(x1<=x2 ? (x2-x1)/2+x1 : (x1-x2)/2+x2)
            let y1 = e.touches[0].pageY - this.scrollbar.scrollTop
            let y2 = e.touches[1].pageY - this.scrollbar.scrollTop
            let y3=(y1<=y2 ? (y2-y1)/2+y1 : (y1-y2)/2+y2)
            return {
                length: Math.round(Math.sqrt(Math.pow(x1-x2,2)+Math.pow(y1-y2,2))),
                x: Math.round(x3),
                y: Math.round(y3)
            }
        },
        // 限制边界
        cropLimit(){
            if(this.scaleX < -(this.scaleWidth-this.width))this.scaleX=-(this.scaleWidth-this.width)
            if(this.scaleY < -(this.scaleHeight-this.height))this.scaleY=-(this.scaleHeight-this.height)
            if(this.scaleX>0)this.scaleX=0
            if(this.scaleY>0)this.scaleY=0
            if(this.scaleHeight===this.height)this.scaleY=0
            if(this.scaleWidth===this.width)this.scaleX=0
        },
        // 提交裁剪
        cropOK(){
            if(!this.edit)return false
            this.loading=true
            let data=this.canvas.toDataURL("image/jpeg",this.quality||0.8)
            //没有设置服务器直接返回base64 
            if(this.server==""){
                this.$emit('success',data)
                document.body.removeChild(this.canvas)
                this.input.value=""
                return false
            }
            data=window.atob(data.split(',')[1]);
            let aBuffer = new ArrayBuffer(data.length);
            let uBuffer = new Uint8Array(aBuffer);
            for (let i = 0; i < data.length; i++) {
                uBuffer[i] = data.charCodeAt(i);
            }
            let blob=new Blob([uBuffer], {type:"image/jpeg"})
            let formData = new FormData()
            formData.append(this.filename, blob)
            if(this.params!=undefined){
                for (let key in this.params)formData.append(key, this.params[key])  
            }
            let xhr = new XMLHttpRequest()
            if (xhr.upload) {
 
                xhr.onreadystatechange = e=> {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            let result=xhr.responseText
                            if(result.substr(0,1)=="{"){
                                result=JSON.parse(result)
                            }
                            this.$emit("success",result,xhr)
                        } else {
                            this.$emit("error",xhr)
                        }
                        window.setTimeout(()=>{
                            this.loading=false
                        },500)
                        
                    }
                }
                xhr.open("POST", this.server, true)
                if(this.header!=undefined){
                    for (let key in this.header)xhr.setRequestHeader(key, this.header[key])
                }
                xhr.withCredentials = false
                xhr.send(formData)
            }
            this.edit=false
            document.body.removeChild(this.canvas)
            this.input.value=""
        },
 
        // http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
        cropGetRatio(){
            var iw = this.img.naturalWidth, ih = this.img.naturalHeight
            var canvas = document.createElement('canvas')
            canvas.width = 1
            canvas.height = ih
            var ctx = canvas.getContext('2d')
            ctx.drawImage(this.img, 0, 0)
            var data = ctx.getImageData(0, 0, 1, ih).data
            // search image edge pixel position in case it is squashed vertically.
            var sy = 0
            var ey = ih
            var py = ih
            while (py > sy) {
                var alpha = data[(py - 1) * 4 + 3]
                if (alpha === 0) {
                    ey = py
                } else {
                    sy = py
                }
                py = (ey + sy) >> 1
            }
            var ratio = (py / ih)
            return (ratio===0)?1:ratio
        }
    }
}
</script>
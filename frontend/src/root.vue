<style src="./styles/base.less" lang="less"></style>
<style src="./styles/loading.less" lang="less"></style>
<style lang="less" scoped>
@import url(./styles/config.less);
@font-face {
    font-family: 'DIN';
    src: url('./fonts/din.eot');
    src: url('./fonts/din.eot?#iefix') format('embedded-opentype'),
         url('./fonts/din.woff') format('woff'),
         url('./fonts/din.ttf') format('truetype'),
         url('./fonts/din.svg#dinregular') format('svg');
    font-weight: normal;
    font-style: normal;
}

html,body {
    background-color:@color-bg;
    font-size: 14px;
    line-height: 100%;
    width:100%;
    height:100%;
    color: #a2acb3;
    overflow: hidden;
}
 
#app{
    position: absolute;
    width:100%;
    height:100%;
    overflow: hidden;
    .main{
        position: absolute;
        left:0;
        top:50px;
        right:0;
        bottom:0;
        overflow: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        transition:all 300ms cubic-bezier(.4,0,0,1);
        &::-webkit-scrollbar{
            width:10px;
        }
        &::-webkit-scrollbar-track-piece{
            background-color:@color-bg;
        }
        &::-webkit-scrollbar-thumb{
            border:2px solid @color-bg;
            background-color:@color-primary;
            background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            -webkit-border-radius:5px;
        }
        .router-view {
            min-height:calc(100vh - 50px);
            @media (min-width:992px) {
                // border-left:1px solid @color-primary;
            }
        }
        .fade-enter-active, .fade-leave-active {
          transition: opacity .2s
        }
        .fade-enter, .fade-leave-active {
          opacity: 0
        }
        @media (min-width:768px) {//手机
        }
        @media (min-width:992px) {//ipad 横版
            left:50%;
            top:0px;
        }
        @media (min-width:1200px) {// 12～14寸笔记本 1333～1920
            left:50%;
            top:0px;
        }
    }
    .totop{
        position: fixed;
        right:20px;
        bottom:80px;
        text-align: center;
        line-height: 40px;
        border-radius:50%;
        width:40px;
        height:40px;
        background:@color-primary;
        background-image: linear-gradient(180deg, @color-primary 0%, darken(@color-primary,15%) 100%);
        box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
        opacity: 0;
        cursor:pointer;
        transition:all 400ms cubic-bezier(.4,0,0,1);
        transform: translateY(-10px);
        pointer-events: none;

        &.fade{
            transform: translateY(0px);
            opacity: 1;
            pointer-events: auto;
            &:hover{
                transform: translateY(0px) scale(1.1);
            }
        }
        &.showPost{
            display: none;
        }
    }
    .publish{
        position: fixed;
        right:20px;
        bottom:30px;
        text-align: center;
        line-height: 40px;
        border-radius:50%;
        width:40px;
        height:40px;
        background:@color-primary;
        background-image: linear-gradient(180deg, @color-primary 0%, darken(@color-primary,15%) 100%);
        box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
        cursor:pointer;
        transition:all 400ms cubic-bezier(.4,0,0,1);
        &:hover{
            transform: scale(1.1);
        }
    }
}
 
</style>

<template>
<div id="app">
    <side></side>
    <div id="main" class="main">
        <transition name="fade">
            <router-view class="router-view"></router-view>
        </transition>
    </div>
    <span class="fixed totop"  :class="{'fade':toTopFadeIn}" @click.prevent="toTop" v-if="toTopStatus">
        <img src="./images/top.svg" width="12">
    </span>
    <span class="fixed publish" @click="publish" v-if="isAdmin">
        <img src="./images/publish.svg" width="12">
    </span>
    <post></post>
    <login></login>
    <topbar></topbar>
    <preview :show="preview.show" :items="preview.items" :index="preview.index" :rand="preview.rand"></preview>
    <toast :show="toast.show" :content="toast.content" @hide="toast.hide">{{toast.message}}</toast>
    <confirm :show="confirm.show" :content="confirm.content" @close="confirmClose" @ok="confirm.ok" @cancel="confirm.cancel"></confirm>
</div>
</template>

<script>
import passive from './scripts/passive'
import login from "./login.vue"
import topbar from "./topbar.vue"
import side from "./side.vue"
import post from "./post.vue"
import toast from './components/toast'
import confirm from './components/confirm'
import preview from "./components/preview.vue"

export default {
    name: 'app',
    components: {
        preview,login,side,toast,post,topbar,confirm
    },
    data(){
        return {
            language:'php',
            // server:'https://zee.kim',
            // server:'http://zee.local:7002',
            // server:'',
            server:'http://192.168.1.6/php-projects/blog',
            navFixed:false,
            toTopFadeIn:false,
            toTopStatus:true,
            showPost:false,
            toast:{
                show:false,
                content:"",
                hide:()=>{
                    this.toast.show=false
                }
            },
            preview:{
                show:false,
                index:0,
                items:[],
                rand:"",
            },
            confirm:{
                show:false,
                content:"",
                ok:new Function(),
                cancel:new Function(),
            },
            isAdmin:false,
        } 
    },
 
    mounted(){
        let that=this
        this.$nextTick(_=>{
            bus.$on("login",()=>{
                this.isAdmin=window.localStorage.token!=undefined;
            })
            bus.$on("showPost",id=>{
                this.showPost=true
            })
            bus.$on("hidePost",id=>{
                this.showPost=false
            })

            that.isAdmin=window.localStorage.token!=undefined;
            if(/(iphone|ios|iPad|android)/i.test(navigator.userAgent)){
                console.log(that.toTopStatus)
                that.toTopStatus=false
            }else{
                document.querySelector("#main").addEventListener("scroll",function(e){
                    let top=e.target.scrollTop
                    if(top>200){
                        that.toTopFadeIn=true
                    }else{
                        that.toTopFadeIn=false
                    }
                },passive);
            }
        })
    },
    methods:{
 
        confirmClose(){
            this.confirm.show=false
        },
        formatDecimal(value, decimal) {
            var value = (value || 0).toString().split(".");
            var number = value[0];
            var result = '';
            while (number.length > 3) {
                result = ',' + number.slice(-3) + result;
                number = number.slice(0, number.length - 3);
            }
            if (value) {
                result = number + result;
                if (value.length == 2) {
                    result += "." + value[1].substring(0, decimal || 2);
                }
            }
            return result;
        },
        toTop(){
            let d = 500     //运行时间（毫秒）
            let b =document.getElementById("main").scrollTop //开始位置
            let c = 0-b     //结束位置
            let now = Date.now()
            //减减速曲线
            let calc=(t, b, c, d)=>{
                return -c * ((t = t / d - 1) * t * t * t - 1) + b;
            }
            // 跑帧
            let go=()=>{
                let t = Date.now() - now
                if (t >= d){
                    cancelAnimationFrame(go)
                    return false
                }
                
                document.getElementById("main").scrollTop = calc(t,b,c,d)
                requestAnimationFrame(go)
            }
            // 初始化
            requestAnimationFrame(go)
        },
        publish(){
            // console.log(this.$route.path);
            if(this.$route.path=="/playlist/"){
                bus.$emit("addMusic")
            }else{
               bus.$emit("addPost") 
            }
            
        },
        openPreview(items,index){
            this.preview.show=true
            this.preview.items=items
            this.preview.index=index
            this.preview.rand=Math.floor(Math.random()*1e10).toString(16)
        },
        // 转换不同语言的api地址
        api(path){
            if(this.language=="php"){
                return this.server+"/api.php?path="+path
            }else{
                return this.server+path
            }
        }
    }
}
</script>



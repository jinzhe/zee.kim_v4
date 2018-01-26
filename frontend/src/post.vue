<style src="./styles/highlight.css"></style>
<style lang="less">
@import url(./styles/config.less);
.post{
    background:#fff;
    position: fixed;
    left:0;
    right:0;
    top:50px;
    bottom:0;
    opacity: 0;
    pointer-events: none;
    box-sizing: border-box;
    z-index: 998;
    transition: all .2s cubic-bezier(0.075, 0.82, 0.165, 1);
    @media (min-width:992px) {
        top:0px;
        right:50%;
        border-right: 1px solid lighten(@color-gray,15%);
    }
    &.show{
        opacity: 1;
        pointer-events: auto;
    }
    &::selection {
        background:@color-primary;
        color: white;
    }
    
    section{
        position: absolute;
        left:0px;
        top:0px;
        right:0;
        bottom:0;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        transition:all .3s ease-in-out;
        transform: translateY(10px);
        padding-top: 60px;
        opacity: 0;
 
        @media (min-width:992px) {
            top:0;
        }
        &.active{
            transform: translateY(0px);
            opacity: 1;
        }

        &::-webkit-scrollbar{
            width:10px;
        }
        &::-webkit-scrollbar-track-piece{
            background-color:#fff;
        }
        &::-webkit-scrollbar-thumb{
            border:2px solid #fff;
            background-color:@color-primary;
            background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            border-radius:5px;
        }

        .content {
            margin:15px 15px;    
            font-size: 1.2rem;
            line-height: 1.5;
            font-weight:400;
            word-wrap: break-word;
            color:@color-text;
            @media (min-width:992px) {
                max-width: auto;
                margin:15px 25px;
            }
            p {
                margin-bottom: 20px;
                // text-indent: 30px;
                &:last-child {
                    margin-bottom: 0;
                }
            }
            blockquote {
                padding: 10px 15px;
                border-left: 2px solid @color-primary;
                background-color: lighten(@color-primary,40%);
                border-radius: 5px;
                font-size: 1rem;
                color:@color-text;
                p{
                    text-indent: 0;
                }
            }
            ul {
                margin-left: 0px;
                margin-bottom: 20px;
                list-style: none;
                li{
                    position: relative;
                    margin-bottom:5px;
                    margin-left:15px;
                    font-size: 1rem;
                    color:@color-text;
                    line-height: 1.5;
                    &:after{
                        content:"";
                        position: absolute;
                        left:-15px;
                        top:8px;
                        width:5px;
                        height: 5px;
                        border-radius:50%;
                        background-color:@color-primary;
                        transition:all .2s ease-in-out;
                    }
                }
            }
            a{
                color: @color-primary;
            }
        }
        
        .photos{
            margin:0 20px;
            @media (min-width:992px) {
                max-width: auto;
                margin:10px 25px;
            }
            &>div{
                // margin-bottom: 20px;
            }
            img{
                display: block;
                margin:auto;
                border-radius: 5px;
                max-width:100%;
            }
            p{
                padding:10px;
                text-align: center;
                font-size:14px;
                line-height: 180%;
            }
        }
        .action{
            padding:50px 0;
            span{
                display: inline-block;
                width:60px;
                font-family: din;
                font-size: .678rem;
                border-radius: 2px;
                padding: 8px 20px;
                background-color:@color-primary;
                background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
                color:#fff;
                cursor: pointer;
                &:hover{
                    background-image: linear-gradient(136deg, darken(@color-primary,5%) 0%, darken(@color-primary,25%) 100%);
                }
                &.delete{
                    background-image: linear-gradient(135deg, #FEB692 0%, #EA5455 100%);
                    box-shadow: 0 2px 6px 0 rgba(red(#EA5455), green(#EA5455), blue(#EA5455), 0.5);
                }
            }
        }
        .qrcode{
            padding-bottom:50px;
        }
    }
    .loading{
        pointer-events: none;
        position: absolute;
        left:50%;
        top:50%;
        transform:translate(-50%,-50%);
        transition:opacity 1s ease-out;
        &.hide{
            opacity: 0;
        }
    }
    .title{
        position: absolute;
        left:0;
        right:0;
        top:0;
        border-bottom: 1px solid lighten(@color-gray,15%);
        background-color:rgba(255,255,255,.95);
        -webkit-backdrop-filter: brightness(1.5) blur(4px);
        transition:all .3s ease-in-out;
        transform: translateY(-10px);
        opacity: 0;
        @supports (-webkit-backdrop-filter: none) {
            background: rgba(255,255,255,.8);
            -webkit-backdrop-filter: brightness(1.5) blur(4px);
        }
        @media (min-width:992px) {
            height:60px;
        }
        &.active{
            opacity: 1;
            transform: translateY(0px);
        }
        .inner{
            position: relative;
            margin:auto;
            padding:10px 15px 10px 15px;

            @media (min-width:992px) {
                max-width: auto;
                padding:10px 15px 10px 25px;
            }
            p{
                word-wrap: break-word;
                text-transform: uppercase;
                font-size: 1.4rem;
                line-height: 1;
                color:@color-text;
                &.overflow{
                    height:22px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }
                @media (min-width:992px) {
                    width:90%;
                }
            }
            span{
                display: block;
                text-transform: uppercase;
                font-size: .8rem;
                line-height: 1.5;
                color:lighten(@color-text,30%);
     
            }
            .close{
                position: absolute;
                top:5px;
                right:10px;
                width:50px;
                height:50px;
                text-align: center;
                cursor: pointer;
                opacity: 0;
                @media (min-width:992px) {
                    opacity: 1;
                    right:25px;
                }
                svg{
                    margin-top: 15px;
                    path{
                        fill:@color-primary;
                    }
                }
            }
        }
       
    }
}
.post-admin{
    background:#fff;
    position: absolute;
    left:0;
    right:0;
    top:50px;
    bottom:0;
    opacity: 0;
    pointer-events: none;
    box-sizing: border-box;
    z-index: 998;
    transition: all .2s cubic-bezier(0.075, 0.82, 0.165, 1);

    transition:all .3s ease-in-out;
    @media (min-width:992px) {
        top:0px;
        left:50%;
    }
 
    &::-webkit-scrollbar{
        width:10px;
    }
    &::-webkit-scrollbar-track-piece{
        background-color:#fff;
    }
    &::-webkit-scrollbar-thumb{
        border:2px solid #fff;
        background-color:@color-primary;
        background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
        border-radius:5px;
    }
    &.show{
        opacity: 1;
        pointer-events: auto;
    }
    .title{
        position: absolute;
        left:0;
        right:0;
        top:0;
        border-bottom: 1px solid lighten(@color-gray,15%);
        background-color:rgba(255,255,255,.95);
        -webkit-backdrop-filter: brightness(1.5) blur(4px);
        transition:all .3s ease-in-out;
        height:60px;
        @supports (-webkit-backdrop-filter: none) {
            background: rgba(255,255,255,.8);
            -webkit-backdrop-filter: brightness(1.5) blur(4px);
        }
        @media (min-width:992px) {
            height:60px;
        }
        .back{
            position: absolute;
            top:5px;
            left:10px;
            width:50px;
            height:50px;
            text-align: center;
            cursor: pointer;
            svg{
                margin-top: 15px;
                path{
                    fill:@color-primary;
                }
            }
        }
        .save{
            position: absolute;
            top:5px;
            right:10px;
            width:50px;
            height:50px;
            text-align: center;
            svg{
                margin-top: 15px;
                path{
                    fill:@color-primary;
                }
            }
        }
    }
    .form{
        position: absolute;
        left:0px;
        top:0px;
        right:0;
        bottom:0;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        &::-webkit-scrollbar{
            width:10px;
        }
        &::-webkit-scrollbar-track-piece{
            background-color:#fff;
        }
        &::-webkit-scrollbar-thumb{
            border:2px solid #fff;
            background-color:@color-primary;
            background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            border-radius:5px;
        }
        & >div{
            margin:80px 20px;
            & > p{
                display: flex;
                justify-content:flex-start;
                align-items: center;
                &>span:first-child {
                    width:100%;
                    margin-right: 10px;
                }
            }
        }
        .input{
            display: block;
            font-size: 1rem;
            padding:12px 16px;
            width:100%;
            box-sizing: border-box;
            border:none;
            background-color: lighten(@color-primary,40%);
            border:1px solid transparent;
            border-left:2px solid lighten(@color-primary,15%);
            border-radius: 5px;
            -webkit-appearance: none;
            color:darken(@color-text,15%);
        }
        .input:focus{
            // border-top:1px solid lighten(@color-primary,15%);
            // border-bottom:1px solid lighten(@color-primary,15%);
            // border-right:1px solid lighten(@color-primary,15%);
            // border-left:2px solid lighten(@color-primary,0%);
            background-color: lighten(@color-primary,35%);
            color:@color-text;
        }
        input::-webkit-input-placeholder,textarea::-webkit-input-placeholder {
            color:#999;
        }
        textarea.input{
            height: 300px;
            resize:vertical;
            &::-webkit-scrollbar{
                width:10px;
            }
            &::-webkit-scrollbar-track-piece{
                background-color:#fff;
            }
            &::-webkit-scrollbar-thumb{
                border:2px solid #fff;
                background-color:@color-primary;
                background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                border-radius:5px;
            }
        }
        .photos{
            padding: 10px 0;
            .photo{
                position: relative;
                margin:10px 0;
                height:60px;
                background-color:#fff;
                border-radius: 2px;
                color:#fff;
                .image{
                    position: absolute;
                    left:0;
                    top:0;
                    width:56px;
                    height:56px;
                    border-radius: 2px;
                    background-size: cover;
                    background-repeat: no-repeat;
                    border:2px solid @color-primary;
                    .mask{
                        background-color: @color-primary;
                        background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                        opacity: .85;
                        position: absolute;
                        top: 0;
                        left:0;
                        right:0;
                        height:100%;
                    }
                    .progress{
                        position: absolute;
                        bottom: 0;
                        left:0;
                        right:0;
                        top:0;
                        line-height: 56px;
                        text-align: center;
                        font-family: din;
                        font-size: .876rem;
                        color:#fff;
                        text-shadow:0 0 1px rgba(0,0,0,.6); 
                    }
                }
                .description{
                    position: absolute;
                    left:70px;
                    top:0;
                    bottom:0;
                    right:50px;
                    padding:10px;
                    background-color:@color-primary;
                    background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                    border-radius: 2px;
                    textarea{
                        display: block;
                        background-color: transparent;
                        width:100%;
                        font-size: 1rem;
                        color:#fff;
                    }  
                }
                .move{
                    position: absolute;
                    right:0px;
                    top:0;
                    bottom:0;
                    width:45px;
                    div{
                        background-color:@color-primary;
                        background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                        border-radius: 2px;
                        color:#fff;
                        height:28px;
                        font-size:.876rem;
                        line-height: 28px;
                        text-align: center;
                        &:first-child{
                            margin-bottom: 4px;
                        }
                    }
                }
                .remove{
                    display: block;
                    position: absolute;
                    top:-8px;
                    left:-8px; 
                    cursor: pointer;
                    background-color: #fff;
                    border-radius: 50%;
                    width:16px;
                    height:16px;
                    transition: all .3s ease;
                    border:1px solid #fff;
                    &:hover{
                        transform: scale(1.2)
                    }
                }
            }
            .upload{
                position: relative;
                text-align: center;
                width:60px;
                height:60px;
                background-color:@color-primary;
                background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
                border-radius: 2px;
                &:before{
                    content:"";
                    position: absolute;
                    left:50%;
                    top:50%;
                    height: 2px;
                    width:20px;
                    background-color: #fff;
                    transform:translate(-50%,-50%);
                    border-radius: 2px;
                }
                &:after{
                    content:"";
                    position: absolute;
                    left:50%;
                    top:50%;
                    height: 20px;
                    width:2px;
                    background-color: #fff;
                    transform:translate(-50%,-50%);
                    border-radius: 2px;
                }
            }
        }
         
        .toolbar{
            margin:10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            button{
                text-align: center;
                width:10%;
                height:26px;
                background-color:@color-primary;
                background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
                color:#fff;
                cursor: pointer;
                border-radius: 2px;
                font-family: din;
                font-size: .678rem;
                text-transform: uppercase;
                transition:all 400ms ease-in-out;
                &:hover{
                    background-image: linear-gradient(136deg, darken(@color-primary,5%) 0%, darken(@color-primary,25%) 100%);
                }
                img{
                    width:12px;
                }
            }
        }
 
    }

    


 

 
}
</style>
 
<template>
<div>
    <div class="post" :class="{'show':show}">
        <section :class="{'active':ready}">
            <div class="content" v-html="markdown(post.content)"></div>
            <div class="photos">
                <div v-for="(photo,index) in post.photos">
                    <img :src="$root.server+photo.path" class="zoom" @click="$root.openPreview(post.photos,index)">
                    <p v-if="photo.description!=''">{{photo.description}}</p>
                </div>
            </div>
            <div class="action text center" v-if="$root.isAdmin">
                <span class="delete" @click="remove">DELETE</span>
                &nbsp;
                <span @click="edit">EDIT</span>
            </div>
            <div class="qrcode text center">
                <img src="./images/donate.png" width="250">
            </div>
        </section>
        <div class="title" :class="{'active':ready}">
            <div class="inner">
                <p v-html="post.title" :class="{'overflow':!scrollDown}"></p>
                <span>{{post.time}} / 已浏览 {{post.view}} 次</span>
                <div @click="close" class="close">
                    <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20">
                    <path d="M929.68 873.872l-361.898667-362.106667 361.3792-361.592533c11.909333-11.92 11.909333-31.237333 0-43.157333l-12.680533-12.6944c-11.909333-11.92-31.217067-11.92-43.124267 0L511.9648 455.9168 150.576 94.321067c-11.909333-11.92-31.217067-11.92-43.1264 0l-12.6816 12.6944c-11.909333 11.92-11.909333 31.237333 0 43.157333l361.3792 361.5936-361.899733 362.1056c-11.909333 11.92-11.909333 31.250133 0 43.170133l12.680533 12.6944c11.909333 11.9072 31.217067 11.9072 43.1264 0l361.908267-362.1216 361.908267 362.1216c11.909333 11.9072 31.220267 11.9072 43.1264 0l12.6816-12.6944C941.589333 905.122133 941.589333 885.792 929.68 873.872z" fill="#111"></path></svg>
                </div> 
            </div>
        </div>
        <div class="loading" :class="{'hide':ready}"> 
            <div class="loading-beat loading-beat-odd"></div>
            <div class="loading-beat loading-beat-even"></div>
            <div class="loading-beat loading-beat-odd"></div>
        </div>  
    </div>
    <div class="post-admin" :class="{'show':admin.show}">
        <div class="form">
            <div>
                <p>
                    <span><input class="input" type="text" placeholder="标题" v-model="admin.post.title"></span>
                    <span><switchbox :checked="admin.post.status" @change="switchUpdateStatus"></switchbox></span>
                </p> 
                <div class="toolbar">
                    <button @click.stop='markdownInsert("**Bold**")'><img src="./images/editor/bold.svg"></button>
                    <button @click.stop='markdownInsert("*Italic*")'><img src="./images/editor/italic.svg"></button>
                    <button @click.stop='markdownInsert("[Link](http://example.com/)")'><img src="./images/editor/link.svg"></button>
                    <button @click.stop='markdownInsert("![Img](http://example.com/)")'><img src="./images/editor/image.svg"></button>
                    <button @click.stop='markdownInsert("\n> ")'><img src="./images/editor/quote.svg"></button>
                    <button @click.stop='markdownInsert("\n- ")'><img src="./images/editor/list.svg"></button>
                    <button @click.stop='markdownInsert("\n# ")'><img src="./images/editor/header.svg"></button>
                    <button @click.stop='markdownInsert("\n| title | title | title |\n| --- | --- | --- |\n| item | item | item |")'><img src="./images/editor/table.svg"></button>
                    <button @click.stop='markdownInsert("```\ncode\n```")'><img src="./images/editor/code.svg"></button>
                </div>
                <textarea class="input" id="content" placeholder="内容" v-model="admin.post.content"></textarea>

               

               
     
                 <div class="photos">
                    <div class="photo" v-for="(photo,index) in admin.post.photos">
                        <div class="image" :style="{'background-image':'url('+(photo.data||($root.server+photo.path))+')'}">
                            <div class="mask"  v-if="photo.progress<100" :style="{'height':(100-photo.progress)+'%'}"></div>
                            <div class="progress" v-if="photo.progress<100">{{photo.progress}}%</div>
                        </div>
                        <div class="description"><textarea v-model="photo.description"></textarea></div>
                        <div class="move">
                            <div @click="moveUp(index)">上移</div>
                            <div @click="moveDown(index)">下移</div>
                        </div>
                        <span class="remove" @click="removePhoto(index)">
                            <svg viewBox="0 0 1024 1024" width="16" height="16"><path d="M512.943488 62.201667c-247.52549 0-448.185602 200.661136-448.185602 448.185602S265.419022 958.573895 512.943488 958.573895 961.130114 757.912759 961.130114 510.387269 760.468978 62.201667 512.943488 62.201667zM711.769836 652.288117c15.717983 15.717983 15.717983 41.204447 0 56.927547-15.721053 15.717983-41.206494 15.717983-56.927547 0L512.524956 566.896284 370.207623 709.215664c-15.721053 15.717983-41.206494 15.717983-56.924477 0-15.7231-15.7231-15.7231-41.209564 0-56.927547l142.314263-142.317333L313.282123 367.651404c-15.7231-15.717983-15.7231-41.204447 0-56.927547 15.717983-15.717983 41.204447-15.717983 56.924477 0l142.317333 142.320403 142.317333-142.320403c15.721053-15.717983 41.206494-15.717983 56.927547 0 15.717983 15.7231 15.717983 41.209564 0 56.927547L569.452503 509.970784 711.769836 652.288117z" fill="#d81e06"></path></svg>
                        </span>
                    </div>

  

                    <div class="upload">
                        <upload  
                            :action="admin.upload.action"
                            :data="admin.upload.data" 
                            :limit="admin.upload.limit"
                            :multiple="admin.upload.multiple"
                            :queue="admin.upload.queue"
                            @change="admin.upload.change" 
                            @progress="admin.upload.progress" 
                            @success="admin.upload.success"
                            @error="admin.upload.error" >
                        </upload>
                    </div>
        
                </div>

                
            </div>
        </div>
        <div class="title">
            <div @click="back" class="back">
                <svg viewBox="0 0 1024 1024" width="20" height="20">
                    <path d="M156.608 487.8592c1.156267-1.137067 2.648533-1.6224 3.918933-2.558933l306.666667-301.569067c13.195733-12.970667 34.586667-12.970667 47.781333 0 13.194667 12.978133 13.194667 34.013867 0 46.987733L263.3024 478.210133l579.2032 0c18.978133 0 34.362667 15.128533 34.362667 33.789867 0 18.6624-15.384533 33.793067-34.362667 33.793067L263.3024 545.793067l251.671467 247.486933c13.194667 12.971733 13.194667 34.010667 0 46.984533-13.194667 12.974933-34.586667 12.974933-47.781333 0l-306.666667-301.5616c-1.269333-0.939733-2.762667-1.421867-3.918933-2.562133-6.334933-6.2304-9.240533-14.340267-9.477333-22.5024C147.367467 502.200533 150.273067 494.090667 156.608 487.8592L156.608 487.8592zM156.608 487.8592" fill="#ffffff"></path>
                </svg>
            </div>
            <div @click="save" class="save">
                <svg  viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20">
                    <path d="M937.31522 216.763882c-13.066597-13.066597-34.251082-13.066597-47.301306 0L370.5315 736.230947 133.992225 499.69065c-13.06762-13.050224-34.234709-13.050224-47.301306 0-13.06762 13.06762-13.06762 34.252105 0 47.302329l260.174578 260.2063c13.066597 13.050224 34.251082 13.050224 47.317679 0l543.133068-543.133068C950.365444 250.998591 950.365444 229.830479 937.31522 216.763882" fill="#ffffff"></path>
                </svg>
            </div> 
        </div>
         <toast :show="admin.toast.show" :content="admin.toast.content" @hide="admin.toast.hide">{{admin.toast.message}}</toast>
    </div>
</div>
</template>

<script>
import passive from './scripts/passive'
import toast from './components/toast'
import switchbox from './components/switchbox'
import upload from './components/upload'
const highlight = require('highlight.js')
const marked = require('marked')

marked.setOptions({
    gfm: true,
    tables: true,
    breaks: true,
    pedantic: false,
    sanitize: false,
    smartLists: true,
    smartypants: false,

    highlight: function (code) {
        return highlight.highlightAuto(code).value
    }
});



export default {
    name:'post',
    components:{
        upload,toast,switchbox
    },
    data() {
        return {
            show:false,
            ready:false,
            post:{
                title:"",
                content:"",
                photos:[],
            },
            scrollDown:false,
            admin:{
                show:false,
                edit:true,
                post:{
                    title:"",
                    content:"",
                    image:"",
                    photos:[],
                    status:true,
                },
                toast:{
                    show:false,
                    content:"",
                    hide:()=>{
                        this.admin.toast.show=false
                    }
                },
                upload:{
                    action:this.$root.api("/post/upload/"),
                    data:{
                        token:window.localStorage.token
                    },
                    limit:10, //10MB
                    multiple:true,
                    queue:true,
                    change:(files)=>{
                        // console.log(files)
                        files.forEach(file=>{
                            this.admin.post.photos.push({
                                key:file.key,
                                progress:0,
                                data:URL.createObjectURL(file),
                                description:"",
                            })
                        })
                        
  
                    },
                    progress:(key,percent)=>{
                        let photo=this.admin.post.photos.find(function(v){
                            return v.key==key
                        })
                        photo.progress=percent
                        console.log(key,percent)
                    },
                    success:(key,data)=>{
                        console.log(data)
                        try{
                            data=JSON.parse(data);
                        }catch(e){
                            console.error(data)
                            return false;
                        }                 
                        if(data.code==200){
                            let photo=this.admin.post.photos.find(function(v){
                                return v.key==key
                            })
                            photo.id=data.result.id+""
                            photo.path=data.result.path
                            delete photo.data
                            console.log("success",data,photo)
                            
                        }else{
                            this.admin.post.photos=this.admin.post.photos.filter(function(v){
                                return v.key!=key
                            })
                        }
       
                    },
                    // 错误
                    error:(type,result)=>{
                        // 文件太大
                        if(type=="limit"){
                            for (let file of result) {
                              console.log("超过上传上限",file["type"],file["name"],(file["size"]/1024/1024).toFixed(2)+"MB")  
                            }
                        }
                        // 没有选择文件
                        if(type=="empty"){
                            alert("请选择文件")
                        }
                        // 没有选择文件
                        if(type=="action"){
                            alert("没有指定上传接口api")
                        }
                        // 服务器报错
                        if(type=="server"){
                             alert("服务器繁忙")
                        }
                    }
                },
            },
            
        }
    },
    watch:{
        "admin.post.title":function(val){
            this.post.title=val
        },
        "admin.post.content":function(val){
            this.post.content=val
        },
        "admin.post.photos":function(val){
            this.post.photos=val
        }
    },
    mounted(){
        bus.$on("showPost",(id,title)=>{
            if(this.show&&id==this.$route.params.id&&this.content!=""){
                return false;
            }
            this.post.title=title||""
            this.post.content=""
            this.show=true
            this.ready=false;
            setTimeout(_=>{
                this.getItem(id)
            },300)
        })

        bus.$on("addPost",()=>{
            this.show=false
            this.admin.post.id=""
            this.admin.post.title=""
            this.admin.post.content=""
            this.admin.post.photos=[]
            this.admin.post.status=true
            this.admin.show=true
            this.admin.edit=false
        })
        bus.$on("login",()=>{
            this.items=[]
            this.getItems()
        })
        bus.$on("hidePost",_=>{
            this.show=false;
            this.ready=false;
        })
        // pc 上滚动条固定导航
        document.querySelector("section").addEventListener("scroll",e=>{
            let top=e.target.scrollTop
            if(top>50){
                this.scrollDown=true
            }else{
                this.scrollDown=false
            }
        },passive)
    },
    methods:{
        markdown(value){
            return marked(value.toString())
        },
        back(){
            this.admin.show=false;
        },
        close(){
            bus.$emit("hidePost")
            this.ready=false;
            this.admin.show=false;
            this.$router.push({
                path:'/',
            })
        },
        getItem(id){
            // 提交字段
            this.ready=false;
            let url=this.$root.api("/post/info/")
            let params={}
            params.id=id
            if(window.localStorage.token!=undefined){
                params.token=window.localStorage.token  
            }
            this.$http.post(url,params).then(response=> {
                this.ready=true;
                let data=response.data
                if(data.code==200){
                    this.admin.post.title=data.result.title
                    this.admin.post.content=data.result.content
                    this.admin.post.photos=data.photos
                    this.admin.post.status=data.result.status=="1"?true:false

                    this.post=data.result
                    this.post.time = new Date(this.post.time*1000).format("yyyy/mm/dd W")
                    this.post.view=this.$root.formatDecimal(this.post.view,0)
                    this.post.photos=data.photos
                    // this.post.photos.forEach(v=>{
                    //     v.path=this.$root.server+v.path
                    // })
                }else{
                    this.close()
                    this.$root.toast.show=true
                    this.$root.toast.content="该内容可能已删除"
                }
            })
        },
        
        open(url){
            window.open(url)
        },
        edit(){
            this.admin.show=true
            this.admin.edit=true
            this.admin.post.id=this.post.id
            this.admin.post.title=this.post.title
            this.admin.post.content=this.post.content
            this.admin.post.photos=this.post.photos
            this.admin.post.status=this.post.status=="1"?true:false
            this.$forceUpdate()
        },
        save(){
            if(this.admin.post.title==""){
                this.admin.toast.show=true  
                this.admin.toast.content="标题为啥不写"
                return false
            }
            if(this.admin.post.content==""){
                this.admin.toast.show=true  
                this.admin.toast.content="内容为啥不写"
                return false
            }
            let url=this.$root.api("/post/save/")
            let params={}
            if(this.admin.post.id!=undefined){
                params.id=this.admin.post.id
            }
            params.title=this.admin.post.title
            params.content=this.admin.post.content
            params.image=this.admin.post.image
            // 删除无用的字段节省带宽
            // this.admin.post.photos.forEach(v=>{
            //     delete v.data
            //     delete v.key
            //     delete v.progress
            // })
            params.photos=JSON.stringify(this.admin.post.photos)
            params.status=Number(this.admin.post.status)
            params.token=window.localStorage.token
            this.$http.post(url,params).then(response=>{
                let data=response.data
                if(data.code==200){
                    this.post.title=this.admin.post.title=params.title.toString()
                    this.post.content=this.admin.post.content=params.content.toString()
                    // this.post.photos=this.admin.post.photos=params.photos
                    this.admin.show=false
                    this.admin.toast.show=true 
                    if(this.admin.edit){
                        this.admin.toast.content="更新成功"
                    }else{
                        this.admin.toast.content="发布成功"  
                    }
                    
                    bus.$emit("reload",true);
                }else{
                    this.admin.toast.show=true  
                    if(data.code==403){
                        this.admin.toast.content="权限不足"
                    }else{
                        if(this.admin.edit){
                            this.admin.toast.content="更新失败"
                        }else{
                            this.admin.toast.content="发布失败"  
                        }
                    }
                    
                }

            },response=>{
                this.admin.toast.show=true  
                this.admin.toast.content="网络异常"
            })
        },
        remove(){
            this.$root.confirm.show=true
            this.$root.confirm.content="确定要删除吗？"

            this.$root.confirm.ok=()=>{
                let url=this.$root.api("/post/delete/")
                let params={
                    id:this.post.id,
                }
                params.token=window.localStorage.token
                this.$http.post(url,params).then(response=>{
                    if(response.data.code==200){
                        bus.$emit("reload",true);
                        this.show=false
                        this.admin.show=false
                        this.$root.toast.show=true  
                        this.$root.toast.content="删除成功"
                    }else{
                        this.$root.toast.show=true  
                        this.$root.toast.content="删除失败"
                    }

                },response=>{
                    this.$root.toast.show=true  
                    this.$root.toast.content="网络异常"
                })
            }
        },
        moveUp(index){
            let photo=this.admin.post.photos
            let max=Math.max(0,index-1)
            let tmp=photo[index]
            this.admin.post.photos[index]=photo[max]
            this.admin.post.photos[max]=tmp
            
            this.$forceUpdate()
        },
        moveDown(index){
            let photos=this.admin.post.photos
            let min=Math.min(photos.length-1,index+1)
            let tmp=photos[index]
            this.admin.post.photos[index]=photos[min]
            this.admin.post.photos[min]=tmp
            
            this.$forceUpdate()
        },
        removePhoto(index){
            // this.admin.post.photos.splice(index,1)
            let photo=this.admin.post.photos[index]
            let url=this.$root.api("/post/delete-photo/")
                let params={
                    id:photo.id,
                }
                params.token=window.localStorage.token
                this.$http.post(url,params).then(response=>{
                    if(response.data.code==200){
                        this.admin.post.photos.splice(index,1)
                    }else{
                        this.admin.toast.show=true  
                        this.admin.toast.content="删除失败"
                    }
                },response=>{
                    this.admin.toast.show=true  
                    this.admin.toast.content="网络异常"
                })
        },
        switchUpdateStatus(value){
            this.admin.post.status=value
        },
        markdownAppend(inputer, oldContent, newContent, content, endPosition, start, end){
            newContent = oldContent.substring(0, endPosition) + content + oldContent.substring(endPosition, oldContent.length)
            inputer.value = newContent
            inputer.setSelectionRange(endPosition + start, endPosition + content.length - end)
            return newContent
        },
        markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, symbol1, symbol2){
            newContent = oldContent.substring(0, startPosition) + symbol1 + oldContent.substring(startPosition, endPosition) + symbol2 + oldContent.substring(endPosition, oldContent.length)
            inputer.value = newContent
            let len = newContent.length
            inputer.setSelectionRange(len, len)
            return newContent
        },
        // 输入
        markdownInsert(content) {
            let inputer = document.querySelector('#content')
            let startPosition = inputer.selectionStart
            let endPosition = inputer.selectionEnd
            let oldContent = inputer.value
            inputer.focus()
            let newContent = ''
            if (startPosition === endPosition) {
                switch (content) {
                    case '**Bold**':
                        newContent = this.markdownAppend(inputer, oldContent, newContent, content, endPosition, 2, 2)
                        break
                    case '*Italic*':
                        newContent = this.markdownAppend(inputer, oldContent, newContent, content, endPosition, 1, 1)
                        break
                    case '[Link](http://example.com/)':
                        newContent = this.markdownAppend(inputer, oldContent, newContent, content, endPosition, 7, 1)
                        break
                    case '`code`':
                        newContent = this.markdownAppend(inputer, oldContent, newContent, content, endPosition, 1, 1)
                        break
                    case '![Img](http://example.com/)':
                        newContent = this.markdownAppend(inputer, oldContent, newContent, content, endPosition, 7, 1)
                        break
                    default:
                        newContent = oldContent.substring(0, endPosition) + content + oldContent.substring(endPosition, oldContent.length)
                        inputer.value = newContent
                        break
                }
            } else {
                switch (content) {
                    case '**Bold**':
                        newContent = this.markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, '**', '**')
                        break
                    case '*Italic*':
                        newContent = this.markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, '*', '*')
                        break
                    case '`code`':
                        newContent = this.markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, '`', '`')
                        break
                    case '[Link](http://example.com/)':
                        newContent = this.markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, '[', '](http://example.com/)')
                        break
                    case '![Img](http://example.com/)':
                        newContent = this.markdownUpdate(inputer, oldContent, newContent, startPosition, endPosition, '![', '](http://example.com/)')
                        break
                    default:
                        return false
                        break
                }
            }
 
        }
    }
} 
</script>
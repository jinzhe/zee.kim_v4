<style lang="less">
@import url(../styles/config.less);
.about{
    padding-top:1px;
    padding-bottom: 50px;
    .box{
        box-sizing: border-box;
        margin: 20px;
        padding:18px 15px;
        position: relative;
        border-radius: 8px;
        &.info{
            padding:0px;
            text-align: center;
        }
        &.link{
            .title{
                margin-bottom: 10px;
            }
            a:hover{
                text-decoration: underline;
                color:@color-primary;
            }
        }
        .header{
            border-radius: 8px 8px 0 0;
            height:120px;
        }
        .avatar{
            position: absolute;
            left:50%;
            top:65px;
            transform: translateX(-50%);
            width:80px;
            border-radius:50%;
            border:5px solid lighten(@color-primary,30%);
            transition: all .4s ease-out;
            cursor: pointer;
            &:hover{
               border-color:lighten(@color-primary,10%); 
            }
        }
        .body{
            padding-top: 50px;
            padding-bottom: 20px;
        }

        .wechat{
            padding:10px;
            font-size: 12px;
        }

        .nickname{
            text-transform: uppercase;
            font:bold 1rem DIN;
            line-height: 1.8;
            color:@color-primary;
        }
        .job{
            text-transform: uppercase;
            font-family:DIN;
            font-size:.7rem;
            line-height: 1;
            color:@color-text;
        }
        .description{
            margin-top: 10px;
            font-size:.876rem;
            line-height: 1.5;
            color:@color-text;
        }
    }

    
    .contact{
        box-sizing: border-box;
        margin:auto;
        margin: 20px;
        @media (min-width:992px) {
            margin:auto;
            width:400px;
        }
        input,textarea{
            display: block;
            font-size: 1rem;
            padding:12px 16px;
            width:100%;
            box-sizing: border-box;
            border:none;
            background-color: lighten(@color-primary,40%);
            border-left:2px solid lighten(@color-primary,15%);
            border-radius: 5px;
            margin-bottom: 20px;
            -webkit-appearance: none;
            color:darken(@color-primary,15%);
        }
        input:focus,textarea:focus{
            background-color: lighten(@color-primary,35%);
            color:@color-text;
        }
        input::-webkit-input-placeholder,textarea::-webkit-input-placeholder {
            color:#999;
        }
        textarea{
            height: 150px;
        }
        .button{
            margin:0;
            padding:8px 20px;
            background-color: @color-primary;
            background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
            color:#fff;
            text-align: center;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            &.disabled{
                background-color: #efefef;
                color:#ccc;
            }
        }
    }
}

@media (max-width:768px) {
    .info{
        width:auto;
    }
}



</style>

<template>
<div class="about">
     <div class="box info">
        <img src="../images/avatar.jpg" alt="Zee Kim" class="avatar" @click="github">
        <div class="header"></div>
        <div class="body">
            <p class="nickname">zee</p>
            <p class="job">Web Developer</p>
            <p class="description">一位合格的前端开发者，应对视觉设计感兴趣。</p> 
        </div>

    </div>
 
     <form class="contact">
        <div class="input">
            <input tyoe="text" v-model="name" id="name" placeholder="姓名" maxlength="20">
        </div>
        <div class="input">
            <input tyoe="text" v-model="email" id="email" placeholder="邮箱" maxlength="100">
        </div>
        <div class="input">
            <textarea v-model="content" id="content"  placeholder="想说些什么？" maxlength="500"></textarea>
        </div>
        <div class="button" role="button" @click="submit" :class="{'disabled':sendTimeout>0}">
            {{sendButton}}
            <template v-if="sendTimeout>0">
                ({{sendTimeout}})
            </template>
        </div>
    </form>
    <div class="box info">
        <br><br><br>
         <img src="../images/qrcode.jpg" width="150">
         <div class="wechat">加我微信(请注明理由)</div>
    </div>
    <div class="box info link">
        <div class="title">LINK</div>
        <a href="http://fuxiaohei.me/" target="blank">傅小黑</a>
        <a href="http://shagua.name" target="blank">傻瓜博客</a>
        <a href="http://cakiss.com/" target="blank">别俊</a>
        <a href="http://imku.net" target="blank">小林</a>
        <a href="http://fj126.net" target="blank">蓝雨</a>
        <a href="http://fulicat.com" target="blank">福利猫</a>
        <a href="http://im.jetiben.com/" target="blank">Jetiben</a>
        
    </div>

     <toast :show="toast.show" :content="toast.content" @hide="toast.hide">{{toast.message}}</toast>
</div>
</template>

<script>
import toast from '../components/toast'
export default {
    name:'about',
    components:{
        toast 
    },
    beforeRouteEnter (to, from, next) {
        next(vm => {
            console.log(1)
           vm.$root.toTop()
        })
    },
    data() {
        return {
        	name:"",
            email:"",
            content:"",
            sendButton:"给我留言",
            sendTimeout:0,
             toast:{
                show:false,
                content:"",
                hide:()=>{
                    this.toast.show=false
                }
            },
        }
    },
    mounted(){
            this.name=window.localStorage.name||""
            this.email=window.localStorage.email||""
            if(window.localStorage.sendTimeout!=undefined){
                this.sendTimeout=parseInt(window.localStorage.sendTimeout)
                this.interval()
            }
    },
    methods:{
        github(){
            window.open('https://github.com/jinzhe')
        },
        submit(){
            if(this.sendTimeout>0){
                return false;
            }
            if(this.name.trim()==""){
                this.toast.show=true
                this.toast.content="何方妖孽，还不赶紧报上名来"
                document.querySelector("#name").focus()
                return false
            }
            if(this.email.trim()==""){
                this.toast.show=true
                this.toast.content="别告诉我你没有邮箱"
                document.querySelector("#email").focus()
                return false
            }
            if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(this.email.trim())){
                this.toast.show=true
                this.toast.content="邮箱格式不正确"
                document.querySelector("#email").focus()
                return false
            }
            if(this.content.trim()==""){
                this.toast.show=true
                this.toast.content="内容不能为空"
                document.querySelector("#content").focus()
                return false
            }
            window.localStorage.name=this.name.trim()
            window.localStorage.email=this.email.trim()
            this.sendButton="努力发送中..."
            // 提交
            let url=this.$root.api("/sendmail/");
            this.$http.post(url,{
                name:this.name,
                content:this.content,
                email:this.email,
            }).then(response=> {
                let data=response.data
                if (data.code==200) {
                    this.toast.show=true
                    this.toast.content="发送成功"
                    this.content=""
                }else{
                    this.toast.show=true
                    this.toast.content="发送失败"
                }
                this.sendButton="给我留言"
                this.sendTimeout=90;
                window.localStorage.sendTimeout=90
                this.interval()
            },response=>{
                this.toast.show=true
                this.toast.content="网络无法连接"
                this.sendButton="重新尝试"
            })
        },
        interval(){
            window.clearInterval(window.sendInterval)
            window.sendInterval=window.setInterval(()=>{
                if(this.sendTimeout==0){
                    window.clearInterval(window.sendInterval)
                    return false;
                }
                this.sendTimeout--;
                window.localStorage.sendTimeout=this.sendTimeout
            },1000)
        }
    }
} 
</script>


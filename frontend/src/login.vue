<style lang="less" scoped>
@import url(./styles/config.less);
.login{
    position: absolute;
    left:0;
    top:0;
    right:0;
    bottom:0;
    background: rgba(0,0,0,.8);
    z-index:999;
    opacity: 0;
    pointer-events: none;
    transition: all 1s cubic-bezier(0.075, 0.82, 0.165, 1);
    @supports (-webkit-backdrop-filter: none) {
        background: rgba(0,0,0,.8);
        -webkit-backdrop-filter: blur(10px);
    }
    &.show{
        opacity: 1;
        pointer-events: auto;
    }
    input{
        position: absolute;
        left:50%;
        bottom:200px;
        transform:translate(-50%,0);
        width:250px;
        padding:14px 50px 15px 50px;
        font-size:1.2rem;
        border-radius: 50px;
        box-shadow: 0 0 20px 0 rgba(0,0,0,.4);
        background-repeat: no-repeat;
        background-image:url(./images/password.svg);
        background-position: 20px 16px;
        background-size: 20px; 
    }
    .close{
        position: absolute;
        left:50%;
        bottom:50px;
        transform:translate(-50%,0);
    }
}
</style>
 
<template>
<div class="login" :class="{'show':show}">
    <input type="text" v-model="password" maxlength="16" @keyup.enter="submit" placeholder="请输入暗号 ^.^"  autocomplete="none">
 
    <div class="close" @click="show=false">
        <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50" height="50">
            <path d="M519.168 926.72C283.648 926.72 92.16 735.232 92.16 499.712 92.16 263.168 283.648 71.68 519.168 71.68c235.52 0 428.032 191.488 428.032 428.032 0 235.52-192.512 427.008-428.032 427.008z m0-824.32C300.032 102.4 122.88 280.576 122.88 499.712s178.176 397.312 397.312 397.312 397.312-178.176 397.312-397.312C916.48 280.576 738.304 102.4 519.168 102.4z" p-id="3330" fill="#ffffff"></path>
            <path d="M351.232 688.128c-5.12 0-10.24-2.048-14.336-6.144-8.192-8.192-8.192-20.48 0-28.672l329.728-338.944c8.192-8.192 20.48-8.192 28.672 0 8.192 8.192 8.192 20.48 0 28.672L366.592 681.984c-4.096 4.096-9.216 6.144-15.36 6.144z" p-id="3331" fill="#ffffff"></path>
            <path d="M686.08 687.104c-5.12 0-10.24-2.048-14.336-6.144L342.016 342.016c-8.192-8.192-8.192-21.504 0-28.672s21.504-8.192 28.672 0l329.728 338.944c8.192 8.192 8.192 21.504 0 28.672-4.096 4.096-9.216 6.144-14.336 6.144z" p-id="3332" fill="#ffffff"></path>
        </svg>
    </div>
</div>
</template>

<script>
export default {
    name:'login',
    data() {
        return {
            show:false,
            password:"",
        }
    },
    mounted(){
        const that=this
        document.addEventListener("keydown",function(e){
            if(e.target.nodeName=="TEXTAREA"||e.target.nodeName=="INPUT"){
                return false
            }
            e.preventDefault()
            let code=e.keyCode
            if(code==27){
               that.show=!that.show 
            }
        },false)
    },
    methods:{
        submit(){
            if(this.password==""){
                this.$root.toast.show=true
                this.$root.toast.content="密码都没有写"
                return false
            }
            let url=this.$root.api("/login/")
            this.$http.post(url,{password:this.password}).then(response=> {
                let data=response.data
                if(data.code==200){
                    this.$root.toast.show=true
                    this.$root.toast.content="登录成功"
                    window.localStorage.token=data.token
                    this.show=false
                    bus.$emit("login",true);
                }else{
                    this.$root.toast.show=true
                    this.$root.toast.content="登录失败"
                }
            })
        },
    }
} 
</script>
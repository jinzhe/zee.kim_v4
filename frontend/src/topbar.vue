<style lang="less">
@import url(./styles/config.less);
.topbar{
    display: flex;
    justify-content: flex-start;
    align-items: center;
    opacity:1;
    pointer-events: auto;
    position: fixed;
    left:0;
    top:0;
    right:0;
    background-color:@color-primary;
    background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
    height:50px;
    color:#fff;
    box-sizing: border-box;
    z-index: 999;
    @media (min-width:992px) {//手机
        opacity: 0;
        pointer-events: none;
    }
    .left{
        width:80px;
    }
    .center{
        width:100%;
        img{
            width: 80px;
        }
    }
    .right{
        width:80px;
    }
    span{
        font-size:1.4rem;
        text-align: center;
    }
}
 
</style>

<template>
<div class="topbar">
    <span class="left" @click="closePost" v-if="$route.path!='/'">
        <img src="./images/back.svg" width="20">
    </span>
    <span class="left" @click="closePost" v-else>

    </span>
    <router-link to="/" tag="span" class="center">
        <img src="./images/logo.svg">
    </router-link>
     <span class="right" @click="side">
        <img src="./images/menu.svg" width="20">
    </span>
</div>
</template>

<script>
export default {
    name:'topbar',
    methods:{
        side(){
            bus.$emit('side')
        },
        closePost(){
            bus.$emit("hidePost")
            this.ready=false;
            this.$router.push({
                path:'/',
            })  
        }
    }
} 
</script>


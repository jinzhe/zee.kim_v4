<style lang="less" scoped>
@import url(../styles/config.less);
.toast{
    position: absolute;
    left:50%;
    top:50%;
    opacity:0;
    pointer-events: none;
    transform:translate3d(-40%,-50%,0);
    background-color:#F6416C;
    background-image: linear-gradient(135deg, #D46051 0%, #F6416C 100%);
    border-radius:4px;
    transition: all .5s ease-in-out;
    padding:7px 20px 8px 35px;
    text-align:center;
    font-size: .876rem;
    color:#fff;
    z-index:9999;
    svg{
        position: absolute;
        left:15px;
        top:9px;
        width:15px;
        height:15px;
    }
}
.toast.in{
    opacity: 1;
    transform:translate3d(-50%,-50%,0);
}
.toast.out{
    opacity: 0;
    transform:translate3d(-60%,-50%,0);
}
 
</style>

<template>
<div class="toast" :class="{'in':show,'out':out}">
<svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5981" xmlns:xlink="http://www.w3.org/1999/xlink" width="150" height="150">
    <path d="M512 0C230.4 0 0 230.4 0 512s230.4 512 512 512 512-230.4 512-512-230.4-512-512-512z m0 960c-249.6 0-448-198.4-448-448s198.4-448 448-448 448 198.4 448 448-198.4 448-448 448z" fill="#ffffff"></path>
    <path d="M448 448h128v320H448z" fill="#ffffff"></path>
    <path d="M512 288m-64 0a64 64 0 1 0 128 0 64 64 0 1 0-128 0Z" fill="#ffffff"></path></svg>
{{content}}
</div>
</template>

<script>
export default {
    name:'toast',
    props:{
        show:{
            type:Boolean,
            default:false,
        },
        content:{
            type:String,
            default:"",
        },
    },
    data(){
        return {
            out:false,
        }
    },
    watch:{
        show(){
            if(this.show){
                this.out=false;
                window.clearTimeout(window.toastTimeout)
                window.toastTimeout=setTimeout(()=>{
                   this.$emit("hide")
                   this.out=true;
                   setTimeout(()=>{
                       this.out=false;
                    },500)
                },2000)
            }
        }
    }
}
</script>
<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        content: {
            type: String,
            default:''
        },
        ok: {
            type: String,
            default: 'OK'
        },
    },
    data(){
        return {
            display:false
        }
    },
    watch:{
        show(){
            if(this.show){
               this.display=true 
            }
            
        }
    },
    methods:{
        OK(){
            this.display=false
            this.$emit('close')
        },
    }
}
</script>
<style>
.alert{
    position: absolute;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.5);
    transition: opacity .7s cubic-bezier(0.19, 1, 0.22, 1);
    -webkit-backdrop-filter: blur(5px);
    opacity: 0;
    pointer-events: none;
}
.alert.active{
    opacity: 1;
    pointer-events: auto;
}
.alert>div {
    position: fixed;
    left:50%;
    top:50%;
    width: 30%;
    background-color:#FAFAFC;
    border-radius:3px;
    transform:translate(-50%,-70%);
    transition: transform .6s cubic-bezier(0.19, 1, 0.22, 1);
}
.alert.active>div{
    transform:translate(-50%,-50%);
}
.alert>div section{
    display:block;
    text-align:center;
    padding:1.6rem 0 2rem 0;
    color:#111;
}
.alert>div .iconfont{
    margin-bottom: 10px;
    font-size: 4rem;
    line-height:5rem;
    color:#373D49;
}
.alert>div footer{
    display:block;
    border-top:1px solid #E8E8EA;
    padding:1rem;
    font-size: 1rem;
    font-weight:600;
    text-align:center;
    color:#373D49;
    cursor: pointer;
}
</style>

<template>
<div class="alert" :class="{'active':display}">
    <div>
        <section>
            <div class="iconfont icon-jinggao"></div>
            {{content}}
        </section>
        <footer @click="OK">{{ok}}</footer>
    </div>
</div>
 
</template>

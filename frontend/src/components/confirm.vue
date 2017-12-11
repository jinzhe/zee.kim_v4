<style lang="less" scoped>
@import url(../styles/config.less);
.confirm{
    position: fixed;
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
    &.active{
        opacity: 1;
        pointer-events: auto;
    }
    &>div {
        position: absolute;
        left:50%;
        top:50%;
        width: 350px;
        background-color:#FAFAFC;
        border-radius:8px;
        transform:translate(-50%,-70%);
        transition: transform .6s cubic-bezier(0.19, 1, 0.22, 1);
        box-shadow: 0 0 10px 0 rgba(0,0,0,.2);
        section{
            display:block;
            text-align:center;
            padding:1.6rem 0;
            color:@color-text;
            svg{
                path{
                    color:@color-primary;
                }
            }
            div{
                font-size: 1rem;
                padding-top:1rem;
            }
        }
        footer{
            display:block;
            button{
                float:left;
                width:50%;
                height:100%;
                padding:1rem 0;
                font-weight:600;
                text-align:center;
                color:@color-primary;
                cursor: pointer;
                font-size: .876rem;
                background-color: transparent;
                &:nth-child(1){
                    color:@color-text;
                }
            }
        }
    }
    &.active>div{
        transform:translate(-50%,-50%);
    }
}
 

</style>

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
            default: '确定'
        },
        cancel: {
            type: String,
            default: '取消'
        }
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
        confirmOK(){
            this.display=false
            this.$emit('ok')
            this.$emit('close')
        },
        confirmCancel(){
            this.display=false
            this.$emit('cancel')
            this.$emit('close')
        }
    }
}
</script>


<template>
<div class="confirm" :class="{'active':display}">
    <div>
        <section>
            <svg  viewBox="0 0 1187 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8452" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="50">
                <path d="M36.864 618.496c16.384 0 409.6 405.504 409.6 405.504L1187.84 0 450.56 696.32l-262.144-249.856-151.552 172.032z" fill="#00AAFF"></path>
            </svg>
            <div>{{content}}</div>
        </section>
        <footer>
            <button @click="confirmCancel">{{cancel}}</button>
            <button @click="confirmOK">{{ok}}</button>
        </footer>
    </div>
</div>
</template>


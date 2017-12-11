<style lang="less" scoped>
@import url(../styles/config.less);
.preview{
    position: absolute;
    left:0;
    top:0;
    right:0;
    bottom:0;
    background: #fff;
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
    .photo{
        width:100%;
        height:100%;
        background-repeat: no-repeat;
        background-position: center;
        background-size:contain;
    }
    .description{
        position: absolute;
        bottom: 0;
        left:0;
        right:0;
        padding:20px 20px 20px 20px;
        background-color:rgba(255,255,255,.5);
        color:@color-text;
        transition: all 1s cubic-bezier(0.075, 0.82, 0.165, 1);
        text-align: center;
        // box-shadow: 0 1px 10px 0 rgba(0,0,0,.2);
    }
    .prev,.next{
        position: absolute;
        top:50%;
        background-color: #eee;
        width:60px;
        height:40px;
        font-size: .876rem;
        line-height: 40px;
        text-align: center;
        cursor: pointer;
        color:@color-text;
        transition: all .3s cubic-bezier(0.075, 0.82, 0.165, 1);
        user-select:none;
        &:hover{
            width:80px;
            background-color: @color-primary;
            color:#fff;
        }
    }
    .prev{
        left:0px;
        border-radius:0 20px 20px 0;
    }
    .next{
        right:0px;
        border-radius:20px 0 0  20px;
    }
}
</style>
 
<template>
<div class="preview" :class="{'show':showInner}">
    <div class="photo" :style="{'background-image':'url('+photo+')'}" @click="showInner=false"></div>
    <div class="description" v-if="description!=''">{{description}}</div>
    <div class="prev" v-if="items.length>0" @click.stop="prev">PREV</div>
    <div class="next" v-if="items.length>0" @click.stop="next">NEXT</div>
</div>
</template>

<script>
export default {
    name:'preview',
    props:{
        show:{
            type:Boolean,
            default:false,
        },
        items:{
            type:Array,
            default:function(){
                return []
            }
        },
        index:{
            type:Number,
            default:0,
        },
        rand:{
            type:String,
            default:"",
        }
    },
    watch:{
        rand(){
            this.showInner=this.show
            this.indexInner=this.index
            this.set(this.index);
        }
    },
    data() {
        return {
            showInner:false,
            indexInner:0,
            photo:"",
            description:"",
        }
    },
    mounted(){
        this.showInner=this.show
        this.indexInner=this.index
    },
    methods:{
        set(index){
            this.showInner=this.show
            this.photo=this.$root.server+this.items[index].path
            this.description=this.items[index].description
        },
        prev(){
            this.indexInner--
            if(this.indexInner<0)this.indexInner=this.items.length-1
            this.set(this.indexInner);
        },
        next(){
            this.indexInner++
            if(this.indexInner>this.items.length-1)this.indexInner=0
            this.set(this.indexInner);
        }
    }
} 
</script>
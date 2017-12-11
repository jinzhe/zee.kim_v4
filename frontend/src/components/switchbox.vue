<style lang="less" scoped>
@import url(../styles/config.less);
.switchbox{
    display: inline-block;
    position: relative;
    width: 52px;
    height: 32px;
    border: 1px solid #dfdfdf;
    border-radius: 16px;
    box-sizing: border-box;
    background-color:@color-primary;
    cursor: pointer;
    &:after,
    &:before {
        content: " ";
        position: absolute;
        top: 0;
        left: 0;
        height: 30px;
        border-radius: 15px;
        -webkit-transition: -webkit-transform .3s;
        transition: -webkit-transform .3s;
        transition: transform .3s;
        transition: transform .3s,-webkit-transform .3s;
    }
    &:before {
        width: 50px;
        background-color: #fdfdfd;
    }
    &:after {
        width: 30px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.4);
    }
    &.checked{
        border-color:@color-primary;
        background-color:@color-primary;
    }
    &.checked:before {
        -webkit-transform: scale(0);
        transform: scale(0);
    }
    &.checked:after {
        -webkit-transform: translateX(20px);
        transform: translateX(20px);
    }
    &.disabled{
        opacity: .4;
    }
}


</style>

<template>
<span class="switchbox" :class="{'checked':display,'disabled':disabled}" @click="change"></span>
</template>


<script>
export default {
    name:'switchbox',
    props: {
        checked: {
            type: Boolean,
            default: false,
        },
        disabled:{
            type: Boolean,
            default: false,
        },
        timestamp:{
            type:Number,
            default:function(){
                return Date.now()
            }
        }
    },
    data(){
        return {
            status:false,
            count:0,
        }
    },
    computed:{
        display(){
            return this.count==0?this.checked:this.status
        }
    },
    watch:{
        timestamp(){
            this.count=0
        }
    },
    methods:{
        change(){
            if(this.disabled){
                return false
            }
            this.count++
            this.status=!this.status
            this.$emit("change",this.status)
        },
    }
}
</script>

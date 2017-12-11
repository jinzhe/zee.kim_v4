<style lang="less" scoped>
@import url(../styles/config.less);
.title{
    padding:20px 20px 0 20px;
    font-size:1rem;
    font-weight: bold;
    margin-left: 8px;
    color:@color-primary;
    p{
        font-size: 12px;
        line-height: 30px;
        color:@color-gray;
    }
}

.playlist{
    padding:0 15px;
    & > div{
        position: relative;
        display: block;
        font-size: .876rem;
        color: #000;
        text-indent: 15px;
        height:32px;
        line-height:32px;
        margin-bottom: 5px;
        &.active{
            background-color: @color-primary;
            background-image: linear-gradient(90deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
            border-radius: 25px;
            color:#fff;
            height:42px;
            line-height:42px;
            margin-bottom: 10px;
            .name{
               em{
                color:#fff;
               }
            }

            .download,
            .edit,
            .delete{
                path{
                    fill:#fff;
                }
            }
        }
        .name{
            display: block;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 90%;
            height: 30px;
           cursor: pointer;
           em{
            color:darken(@color-gray,15%);
           }
        }
        .delete{
            display:none;
            position: absolute;
            top: 6px;
            right:100px;
            @media (min-width:992px) {//ipad 横版
                display: inline-block;
            }
            svg{
                width:20px;
                height:20px;
                path{
                    fill:@color-primary;
                }      
            }

        }
        .edit{
            display:none;
            position: absolute;
            top: 6px;
            right:60px;
            @media (min-width:992px) {//ipad 横版
                display: inline-block;
            }
            svg{
                width:20px;
                height:20px;
                path{
                    fill:@color-primary;
                }      
            }

        }
        .download{
            display:none;
            position: absolute;
            top: 6px;
            right:20px;
            @media (min-width:992px) {//ipad 横版
                display: inline-block;
            }
            svg{
                width:20px;
                height:20px;
                path{
                    fill:@color-primary;
                }      
            }

        }
    }
}
.form{
    position: absolute;
    left:50%;
    top:50%;
    width: 80%;
    background-color:#fff;
    border-radius:8px;
    transform:translate(-50%,-70%);
    transition: transform .6s cubic-bezier(0.19, 1, 0.22, 1);
    box-shadow: 0 0 10px 0 rgba(0,0,0,.2);
    padding:20px;
    .input{
        display: block;
        font-size: 1rem;
        margin-bottom: 10px;
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
    .buttons{
        display: flex;
        justify-content: center; /*水平对齐*/
        align-items: center; /*垂直对齐*/
        span{
            width:80px;
            height:40px;
            line-height: 40px;
            text-align: center;
            font-weight: bold;
            border-radius: 20px;
            &:nth-child(1){
                color:lighten(@color-text,50%);
            }
            &:nth-child(2){
                color:@color-primary;
            }
            &:hover{
                background-color: lighten(@color-gray,15%);
            }
        }
    }
}

</style>

<template> 
<div>
    <div class="title">
        播放列表
        <p>上下方向键可以控制曲目，左右方向键可以控制进度。</p>
    </div>
    <div class="playlist">
        <div :class="{'active':index==current}" v-for="(item,index) in items">
            <span class="name"  @click.stop="select(index)"><em>{{showNumber(index+1)}}.</em> {{item.name}}</span>
            <a class="delete" v-if="$root.isAdmin" @click="remove(index)">
                <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="7182" xmlns:xlink="http://www.w3.org/1999/xlink" width="150" height="150">
                <path d="M878.784 261.952c-62.4-0.256-121.472-0.192-183.872-0.192l-15.744 0c0-5.888 0-11.264 0-16.64-0.128-17.92 0.704-32.64-0.576-50.432-2.304-30.528-24.064-56.064-53.952-64.64C622.848 129.6 621.12 128.704 619.456 128L393.152 128c-9.6 4.352-19.968 7.552-28.544 13.312C344.576 154.88 333.44 179.2 333.44 202.56c-0.064 20.352 0 37.568 0 58.624C329.6 261.44 327.04 261.76 324.416 261.76c-64.64 0-126.08 0-190.784 0C131.776 261.696 129.92 261.376 128 261.184l0 40 86.784 0c1.024 14.272 2.112 24.384 2.944 37.632C223.36 428.224 228.992 517.568 234.624 606.912c4.736 74.816 9.28 149.632 14.272 224.448C251.2 866.432 275.2 886.656 308.544 896L704 896c15.744-4.864 33.792-11.264 44.48-24.512 9.536-11.84 14.336-25.28 15.296-40.192 5.568-89.024 11.136-178.048 16.768-267.072 5.44-86.016 10.88-168.832 16.384-254.912 0.128-2.176 0.704-4.352 1.28-8l36.48 0c16 0 32 0.32 48-0.192C887.104 301.056 891.52 298.304 896 296.704L896 265.664C890.24 264.384 884.48 261.952 878.784 261.952L878.784 261.952zM372.352 191.04c1.216-15.36 15.424-25.216 31.616-25.28 68.16-0.256 136.32-0.256 204.48 0 18.24 0.064 32.32 12.672 33.152 30.528 1.024 22.272 0.256 41.408 0.256 65.024L371.84 261.312C371.84 236.608 370.432 215.296 372.352 191.04L372.352 191.04zM755.776 374.08c-5.056 80.064-10.24 160.128-15.296 240.128-4.032 63.68-7.744 127.36-12.032 191.04-2.304 34.432-12.096 57.28-58.496 57.28-106.624 0.192-220.352 0.192-326.976 0-47.872 0-56.448-22.336-58.88-56.896-4.48-64.768-8.128-129.6-12.224-194.368-5.76-90.88-11.584-181.696-17.344-272.576C253.76 325.824 253.12 315.968 252.352 302.016l507.84 0C758.72 327.616 757.312 349.248 755.776 374.08L755.776 374.08zM755.776 374.08" p-id="7183" fill="#ffffff"></path><path d="M611.968 777.472c7.872 1.856 18.816 2.688 26.496 0.704 3.072-0.768 5.44-9.792 5.504-15.04 0.32-121.536 0.256-243.072 0.192-364.672 0-16-3.52-17.344-19.52-17.344C609.088 381.184 608 382.272 608 397.888l0 183.424c0 61.12-0.064 122.304 0.128 183.424C608.192 769.216 609.792 776.96 611.968 777.472L611.968 777.472zM611.968 777.472" fill="#ffffff"></path><path d="M388.352 779.712c15.296-0.064 18.304-0.896 18.304-15.872 0-60.8 0-121.536 0-182.336 0-61.888 0.064-123.776-0.128-185.664 0-4.16-1.28-11.392-3.264-11.904C395.456 382.08 382.4 380.864 374.784 382.656 371.648 383.36 368.64 392.128 368.64 397.248c-0.32 122.304-0.256 244.544-0.256 366.848C368.384 778.624 371.968 779.776 388.352 779.712L388.352 779.712zM388.352 779.712" p-id="7185" fill="#ffffff"></path><path d="M505.472 779.712c16.704 0.064 20.032-0.896 20.032-15.488 0-122.304 0-244.608 0-366.912 0-14.912-3.584-16.192-19.52-16.128C490.688 381.184 487.104 382.656 487.104 397.952c0 60.8 0 121.536 0 182.336 0 61.12 0 122.304 0 183.424C487.104 778.688 490.24 779.648 505.472 779.712L505.472 779.712zM505.472 779.712" fill="#ffffff"></path>
                </svg>
            </a>
            <a class="edit" v-if="$root.isAdmin" @click="edit(item)">
                <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"  xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24">
                    <path d="M512 1024C229.236364 1024 0 794.763636 0 512 0 229.236364 229.236364 0 512 0 794.763636 0 1024 229.236364 1024 512 1024 794.763636 794.763636 1024 512 1024ZM512 46.545455C255.348364 46.545455 46.545455 255.348364 46.545455 512 46.545455 768.651636 255.348364 977.454545 512 977.454545 768.651636 977.454545 977.454545 768.651636 977.454545 512 977.454545 255.348364 768.651636 46.545455 512 46.545455ZM724.805818 707.677091 305.896727 707.677091C293.003636 707.677091 282.624 697.297455 282.624 684.404364L282.624 335.313455C282.624 322.466909 293.003636 312.040727 305.896727 312.040727L571.624727 312.040727C584.471273 312.040727 594.897455 322.466909 594.897455 335.313455 594.897455 348.206545 584.471273 358.586182 571.624727 358.586182L329.169455 358.586182 329.169455 661.131636 701.533091 661.131636 701.533091 441.995636C701.533091 429.149091 711.912727 418.722909 724.805818 418.722909 737.652364 418.722909 748.078545 429.149091 748.078545 441.995636L748.078545 684.404364C748.078545 697.297455 737.652364 707.677091 724.805818 707.677091ZM531.781818 526.987636C527.266909 531.549091 521.309091 533.829818 515.351273 533.829818 509.393455 533.829818 503.435636 531.549091 498.874182 526.987636 489.797818 517.911273 489.797818 503.202909 498.874182 494.08L698.833455 294.167273C707.909818 285.044364 722.664727 285.044364 731.741091 294.167273 740.817455 303.243636 740.817455 317.952 731.741091 327.074909L531.781818 526.987636Z" fill="#ffffff"></path>
                </svg>
            </a>
            <a class="download" :href="item.url" :download="item.name">
                <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24">
                <path d="M341.333333 825.566171 377.025977 789.873527 486.760332 899.607882 486.760332 451.83518 537.242101 451.83518 537.242101 899.607882 646.975239 789.873527 682.672748 825.566171 512.003649 996.235271ZM664.688375 715.836682l0-50.486634 114.205514 0c94.005022 0 170.483004-76.475549 170.483004-170.478138 0-89.351416-69.868548-163.951414-159.064277-169.833494l-50.216613-3.357018-6.120477-49.995244c-13.698094-111.962636-109.124983-196.394075-221.970661-196.394075-112.849327 0-208.278649 84.432655-221.976743 196.39894l-6.120477 49.994028-50.22391 3.353369c-89.183566 5.880863-159.052114 80.480861-159.052114 169.833494 0 94.002589 76.475549 170.478138 170.478138 170.478138l114.204297 0 0 50.486634L245.108544 715.836682c-121.840297 0-220.964772-99.124475-220.964772-220.963556 0-56.4125 21.283008-110.087085 59.926423-151.135146 38.454886-40.84737 90.396228-65.381577 146.257739-69.08646l8.543368-0.566801 1.03873-8.499581C256.68904 128.318369 373.664094 24.805445 512.003649 24.805445c138.337122 0 255.308527 103.512924 272.087536 240.778477l1.03873 8.499581 8.543368 0.566801c55.861512 3.704883 107.802853 28.240307 146.256523 69.08646 38.643414 41.049277 59.927639 94.722645 59.927639 151.135146 0 121.839081-99.124475 220.963556-220.964772 220.963556L664.688375 715.835465z" fill="#ffffff"></path>
                </svg>
            </a>
        </div>
    </div>
    <div class="form" v-if="form.show" :style="{top:form.top}">
        <input v-model="form.name" class="input" placeholder="音乐名称">
        <input v-model="form.url" class="input" placeholder="播放地址">
        <div class="buttons">
            <span class="button" @click="form.show=false">取消</span>
            <span class="button" @click="save">保存</span>
        </div>
    </div>
    <toast :show="toast.show" :content="toast.content" @hide="toast.hide">{{toast.message}}</toast>
    <confirm :show="confirm.show" :content="confirm.content" @close="confirm.close" @ok="confirm.ok" @cancel="confirm.cancel"></confirm>
</div>
</template>

<script>
import toast from '../components/toast'
import confirm from '../components/confirm'
export default {
    name:'playlist',
    components:{
        toast,confirm
    },
    data() {
        return {
            current:0,
            items:[],
            confirm:{
                show:false,
                content:"",
                ok:new Function(),
                cancel:new Function(),
                close:()=>{
                    this.confirm.show=false
                }
            },
            toast:{
                show:false,
                content:"",
                hide:()=>{
                    this.toast.show=false
                }
            },
            form:{
                top:0,
                show:false,
                id:"",
                name:"",
                url:"",
            }
        }
    },
    computed:{
        itemCount(){
            return this.items.length;
        }
    },
    mounted(){
        bus.$on("addMusic",()=>{
            this.add()
        })
        bus.$on("playlist:current",(index)=>{
            document.querySelector("#main").scrollTop=index*36
            this.current=index
        })

        bus.$on("playlist:reload",(data)=>{
            this.items=data
        })
        this.$nextTick(()=>{
            this.current=window.playlistCurrent
            this.items=window.playlistData
        })
    },
    methods:{
 
        select(index){
            document.querySelector("#main").scrollTop=index*36
            bus.$emit("select",index)
            this.current=index
        },
        showNumber(value){
            if(this.itemCount<100){
                if(value<10){
                    return "0"+value
                }else{
                    return value;
                }
            }else  if(this.itemCount>99){
                if(value<10){
                    return "00"+value
                }else  if(this.itemCount>99){
                    return "0"+value 
                }else{
                    return value;
                }
            }else{
                return value;
            }
        },
        
        add(){
            this.form.top=document.querySelector("#main").scrollTop+(window.innerHeight/2)+"px"
            this.form.show=true
            this.form.id=""
            this.form.name=""
            this.form.url=""
            delete this.form.item
        },
        edit(item){
            this.form.top=document.querySelector("#main").scrollTop+(window.innerHeight/2)+"px"
            this.form.show=true
            this.form.name=item.name
            this.form.url=item.url
            this.form.id=item.id
            this.form.item=item
        },
        save(){
            if(this.form.name==""){
                this.toast.show=true  
                this.toast.content="名称不能为空"
                return false
            }
            if(this.form.url==""){
                this.toast.show=true  
                this.toast.content="地址不能为空"
                return false
            }
            let url=this.$root.server+"/music/save/"
            let params={
                name:this.form.name,
                url:this.form.url,
                token:window.localStorage.token
            }
            if(this.form.id!=""){
                params.id=this.form.id
            }
            this.$http.post(url,params).then(response=>{
                if(response.data.success){
                    this.form.show=false
                    if(this.form.item!=undefined){
                        this.form.item.name=this.form.name
                        this.form.item.url=this.form.url
                    }else{
                        this.items.unshift({
                            id:response.data.id,
                            name:this.form.name,
                            url:this.form.url,
                        })
                    }
                    this.form.id=""
                    this.form.name=""
                    this.form.url=""
                    this.toast.show=true  
                    this.toast.content="保存成功"
                }else{
                    this.toast.show=true  
                    this.toast.content="数据异常"
                }
            })
        },
        remove(index){
            this.$root.confirm.show=true
            this.$root.confirm.content="确定要删除吗？"

            this.$root.confirm.ok=()=>{
                let url=this.$root.server+"/music/delete/"
                let params={
                    id:this.items[index].id,
                    token:localStorage.token,
                }
                this.$http.post(url,params).then(response=>{
                    if(response.data.success){
                        this.items.splice(index,1)
                        this.toast.show=true  
                        this.toast.content="删除成功"
                    }else{
                        this.toast.show=true  
                        this.toast.content="删除失败"
                    }

                },response=>{
                    this.toast.show=true  
                    this.toast.content="网络异常"
                })
            }
        },
    }
} 
</script>


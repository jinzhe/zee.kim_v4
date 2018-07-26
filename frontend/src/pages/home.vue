<style lang="less">
@import url(../styles/config.less);

.home{
    position: relative;
    padding-bottom: 20px;
    &.active:before{
        content:"";
        position: absolute;
        left:28px;
        top:30px;
        width:2px;
        bottom:0%;
        background-color: lighten(@color-primary,35%);
    }
    .timeline{
        min-width: 300px;
        position: relative;
        margin:0 20px;
        padding-left:30px;

        .date{
            padding-top:15px;
            padding-bottom:0px;
            position: relative;
            text-transform: uppercase;
            font-family: 'DIN';
            font-size: 24px;
            color: @color-gray;
            &:after{
                content:"";
                width:8px;
                height:8px;
                background:@color-primary;
                border:4px solid lighten(@color-primary,35%);
                border-radius: 50%;
                position: absolute;
                left:-29px;
                top:23px;
            }
            &>span{
               color:lighten(@color-primary,10%);;
            }
        }

        .content{
            &:hover .views{
                transform:translate(0%,-2px);
                opacity: 1;
            }
            .views{
                position: relative;
                display: none;
                padding: 6px 10px 5px 10px;
                border-radius:10px;
                font-size:.7rem;
                font-family: DIN;
                line-height: 1;
                text-transform: uppercase;
                color:lighten(@color-text,30%);
                opacity: 0;
                transform:translate(10%,-2px);
                transition:all .3s ease-out;
                background-color: @color-primary;
                background-image: linear-gradient(90deg, @color-primary 0%, darken(@color-primary,15%) 100%);
                color:#fff;
                @media (min-width:992px) {//ipad 横版
                    display: inline-block;
                }
                &:after{
                    content:"";
                    position: absolute;
                    top:6px;
                    left:-6px;
                    border:4px solid transparent;
                    border-right-color:@color-primary;
                }
            }
            a{
                display: block;
                text-transform: uppercase;
                font-size:1.1rem;
                line-height: 1.8;
                color:lighten(@color-text,25%);
                &:hover,
                &.active{
                    color:@color-primary;
                }
            }
        }
    }
    .more{
        padding:20px 0;
        & > div{
            margin:0px auto;
            text-align: center;
            border-radius:50%;
            width:40px;
            height:40px;
            background:@color-primary;
            background-image: linear-gradient(136deg, @color-primary 0%, darken(@color-primary,15%) 100%);
            box-shadow: 0 2px 6px 0 rgba(red(@color-primary), green(@color-primary), blue(@color-primary), 0.5);
            cursor:pointer;
        }
        img{
            margin-top: 15px;
            transform:rotate(180deg);
        }
    }

}
 
.empty{
    padding-top: 50px;
    text-align: center;
    color:#ccc;
    img{
        margin:10px;
    }
}
</style>
 
<template>
<div>
    <div class="home" :class="{'active':loaded}">
        <div class="timeline" v-for="(item,index) in items">
            <div class="space" v-if="item.month!=''&&index>0"></div>
            <div class="date" v-if="item.month!=''">
                {{item.month.split(" ")[0]}}
                <span>{{item.month.split(" ")[1]}}</span>
            </div>
            
            <div class="content">
                <a href="" @click.prevent="post(item)" :class="{'active':activeId==item.id}">
                    {{item.title}}
                    <div class="views">{{item.view}}</div>
                </a>
            </div>
        </div>
        <div class="more" @click="more" v-if="!isEnd&&loaded">
            <div><img src="../images/top.svg" width="12"></div>
        </div>
    </div>

    <div class="loading" v-if="!loaded"> 
        <div class="loading-beat loading-beat-odd"></div>
        <div class="loading-beat loading-beat-even"></div>
        <div class="loading-beat loading-beat-odd"></div>
    </div>
    <div class="empty" v-if="loaded && items.length==0">
        <img src="../images/404.svg"><br>
        主人很懒没有写任何内容
    </div>
</div>
</template>

<script>
export default {
    name:'home',
    beforeRouteEnter (to, from, next) {
 
        next(vm => {
            // console.log('home')
            if(to.path.substr(0,5)=="/post"){
                bus.$emit("showPost",to.params.id)
                vm.activeId=to.params.id
            }
             if(to.path.substr(0,5)!="/post"&&from.path.substr(0,5)!="/post"){
               vm.$root.toTop() 
            }
        })
    },
    data() {
        return {
        	items:[],
            activeId:null,
            loaded:false,
            lastMonth:'',
            params:{
                pagesize:100,
                page:1,
                count:0,
            },
            isEnd:false,
        }
    },
    mounted(){
        bus.$on("reload",()=>{
            this.items=[]
            this.lastMonth='';
            this.params.page=1;
            this.getItems()
        })
        bus.$on("login",()=>{
            this.items=[]
            this.lastMonth='';
            this.params.page=1;
            this.getItems()
        })
        bus.$on("hidePost",()=>{
            this.activeId=null
        })
        this.$nextTick(_=>{
          this.getItems()   
        })
    },
    methods:{
        getItems(){
            // 提交字段
            let url=this.$root.api("/post/list/");
            let query={};
            query.pagesize=this.params.pagesize;
            query.page=this.params.page;
            if(localStorage.token!=undefined && localStorage.token!=""){
                 query.token=localStorage.token;
            }
            this.$http.post(url,query).then(response=> {
                let data=response.data
                if (data.code==200) {
                    this.params.count=data.result.count
                    if(Math.ceil(data.result.count/this.params.pagesize)<=this.params.page){
                        this.isEnd=true
                    }
                    let items=data.result.data
 
                    items.forEach((v,k)=>{
                        let dateFormat = new Date(v.time*1000).format("mm/dd W")
                        let timeFormat = new Date(v.time*1000).format("yyyy M")
                        if(this.lastMonth != timeFormat){
                            this.lastMonth=timeFormat
                            items[k].month=timeFormat
                        }else{
                            items[k].month=''
                        }
                        items[k].md=dateFormat
                        items[k].image=this.$root.server+v.image
                        items[k].view=this.$root.formatDecimal(v.view)
                    })
                    this.items=this.items.concat(items)
                    this.loaded=true
                }else{
                    this.loaded=false
                    this.$root.toast.show=true
                    this.$root.toast.content="数据获取失败"
                }
            },response=> {
                this.loaded=false
                this.$root.toast.show=true
                this.$root.toast.content="网络无法连接"
            })
        },
        more(){
            this.loaded=false
            this.params.page++
            this.getItems()
        },
        post(item){
            bus.$emit("showPost",item.id,item.title)
            this.activeId=item.id
            this.$router.push({
                path:'/post/'+item.id+'/',
            })
        }
    }
} 
</script>


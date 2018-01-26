<style lang="less">
@import url(./styles/config.less);
.side{ 
    left:0;
    top:0;
    width:100%;
    height:100%;
    opacity: 0;
    pointer-events: none;
    transform: translateY(100%);
    z-index:9999;
    background-image: url(./images/side1.jpg);
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
    transition: all .5s cubic-bezier(0.075, 0.82, 0.165, 1),background 2s ease-in-out;
 
    &.active{
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }
    @media (min-width:768px) {//手机 、ipad 竖版
        
    }
    @media (min-width:992px) {//ipad 横版
        width:50%;
        transform: translateY(0);
        pointer-events: auto;
        opacity: 1;
        z-index:888;
    }
    @media (min-width:1200px) {// 12～14寸笔记本 1333～1920
    
    }
    .mask{
        position: absolute;
        left:0;
        top:0;
        right:0;
        bottom: 0;
        width:100%;
        height:100%;
        background-image: url(./images/bg_mesh.png);
        background-color: rgba(0,0,0,.2);
    }
    .logo{
        margin-top: 150px;
        text-align: center;
        &>img{
            width:180px;
        }
        .nav{
            margin:10px 0;
            width: 100%;
            display: flex;
            justify-content:center; /*水平对齐*/
            align-items: center; /*垂直对齐*/
            color:#fff;
            span{
                display: block;
                padding:5px 10px;
                font-size:1rem;
                color:#fff;
                cursor:pointer;
            }
            i{
                font-size: .5rem;
            }
        }
    }
    
    .tip{
        position: absolute;
        width: 80%;
        left: 50%;
        top: 240px;
        -webkit-transform: translate(-50%,0%);
        transform: translate(-50%,0%);
        color:#fff;
        text-align: center;
        font-size: 1.2rem;
        line-height: 1.5;
        transition: all .6s ease;
        &.change{
            opacity: 0;
            transform:translate(-50%,0%) translateY(10px);
        }
    }
     
     
    .close{
        position: absolute;
        left:20px;
        top:20px;
        width:20px;
        height:20px;
        @media (min-width:992px) {//ipad 横版
            display: none;
        }
    }
}

</style>

<template>
<div class="side pa" :class="{'active':active,'actived':!active}">
    <div class="mask">
        <div class="logo text center">
            <img src="./images/logo.svg"  @click="go('/')">
            <div class="nav" role="navigation">
                <span @click.stop="go('/')" role="button">记录</span>
                <i>／</i>
                <span @click.stop="go('/playlist/')" role="button">听歌</span>
                <i>／</i>
                <span @click.stop="go('/about/')" role="button">关于</span>
            </div>
        </div>

        <div class="tip" :class="{'change':tipStatus}">{{tip}}</div>
        <player></player>
    </div>
    <img v-if="isMobile && !isIpad" src="./images/back.svg" class="close" @click="active=false">
</div>
</template>

<script>
import player from "./player.vue"
import passive from './scripts/passive'
export default {
    name:'side',
    components:{
        player
    },
    data() {
        return {
             active:false,
             tips:`
任何时候，不要推卸责任，不要将责任归咎于他人。无论发生什么事，都与自己有关，都应从自己入手，首先解决自身的问题。
忍耐是一种教养，是对自己自尊的维护。没有什么事值得自己大动肝火，保持平心静气，若觉得无法忍耐别人，请注意，自己的失控是否也会让别人无法忍耐呢。
即使是好的追求，也不要太贪心，什么都想做，结果就是什么也做不成。
坦然地面对过去的自己！无论对与错！无论好与坏！然后，向前看，重新塑造自己！
钱是有用的，把它当朋友一样善待，不要滥用。
最重要的工作，是规律生活和健康管理。
无论做什么事，都要想到下一个人。比如，离开洗手间的时候，把地上的水擦干。
不打无准备之仗，凡事有备而为，就是这样吧。比如，买花之前先把盆和土备好。
沟通，就是为了传达爱愿。有时候，的确需要多说点话，让对方明白自己，避免误解。
幸福，是人与人之间深刻的联结，和深深的羁绊。
确定要做的事，坚持不放弃。
任何关系想要长久，都需要用心维护。
以小见大，不因恶小而为之。万事不可随意。
目光放得远一点，不局限，不受限。
学习固然重要，更重要的是思考。 
胜败乃兵家常事，不因惧怕失败而放弃。
表达意见、下结论需谨慎而为，先看清楚，想清楚，弄明白。
这真是一种能力，不是人人都具备，是需要学习的。看到别人的优点并说出来是促进交流的开始。
任何事情都需要投入，学习寻找工作的乐趣，找到投入工作的动力。
过多操心自己无法干预的事，不如省出时间想想自己能做什么。
有时候，过分地“为对方着想”会给人一种误解，被体贴关心的人也许很难相处。
丰富自己的内心，提升自己，积累资本。
如果能做到时常主动和身边每个认识的人打招呼，真是了不起的事。在单位的走廊里，也常常与缺少交往的同事擦肩而过，彼此面无表情。
做到了诚实、亲切、笑容，就会是处处受欢迎的人。
无论工作还是生活，认真努力地做事只是为了无愧于自己，而非为了打败别人。
常对自己投资。为体验花钱。不用贫穷的方法学习。
吃是一种生活态度，用心做的食物带给人的除了美味，还有愉悦的心情。
公共场合保持安静，不大声喧哗，不引人注目，是一种修养。
重要的事写在信上。勤于动笔。
培养上等的修养。手不插入口袋。
想要伙伴，先制造敌人。
常保指尖和手的清洁。
一个人成长的表现是：在与他人相处的时候，忘掉自己。
小心地使用，用心地维护，不喜新厌旧，长久也是一种美好。
不只关注中间，也看周围。并去思考。 睁大眼睛看世界，感受就有美好。    
与其读一百本书，不如把一本好书读一百遍。
在自己擅长的领域深入下去不断磨炼、挑战。总该有一件事，是自己真正专长的，而不是只知皮毛。
不为自己设限。努力，不给自己找借口。
确定要做的事执着不放弃。
不忘初心，保持学习与成长的心吧。
关键时刻不怯场的勇气。
该接受的坦然接受，也是一种礼貌。
礼貌与尊敬永远是没有错。
学会倾听，这是一种美德。  
学习榜样并懂得感恩。
不要根据价格评判“贵”或“便宜”。价格只是价格。
一周买一次花。
不靠近免费的东西。免费的往往是对自己无用的。
任何时候，坚持不放弃，总会有收获的吧。
无论多忙，给自己点时间放空、思考、调整，才能更好地继续忙。
推进“自我项目”。帮助自我成长的一种方式。
思考的事情、想法，都写在纸上。
不使用“绝对”“普遍”这样的词。不偏激，凡事都有两面性。
走路时端正姿势，挥动双手，抬头挺胸。这会让自己充满自信，也会把这种感觉展示给他人。
想要得到别人的帮助，请先学会付出。人人都需要心情愉快，想要得到先付出，给予与收获永远是对等的。
不交抱双臂或翘二郎腿。留意坐姿。时刻关注自己的仪容。  
这是自己的问题，需要自己想办法解决。
工作也是为了生活，在生活中才能找到真正的自我。
`,
            tip:"",
            tipsSplit:[],
            tipsLength:0,
            tipStatus:true,
            isMobile:/android|iphone|ipad|ipod/i.test(navigator.userAgent.toLowerCase()),
            isIpad:/ipad/i.test(navigator.userAgent.toLowerCase()),

        }
    },
    directives: {
      swipedown: {
        inserted: function (el) {

        }
      }
    },
    mounted(){
        bus.$on("side",_=>{
            this.active=true
        })
        this.$nextTick(_=>{
            this.tipsSplit=this.tips.trim().split("\n")
            this.tipsLength=this.tipsSplit.length
            this.change()
            window.setInterval(()=>{
                this.tipStatus=true
                this.change()
            },10000)

            // 移动端下滑关闭
            
            let [sx,ex,sy,ey]=[null,null,null,null]
            document.querySelector(".side").addEventListener("touchstart",function(e){
                // e.preventDefault()
                sy=e.changedTouches[0].pageY
                sx=e.changedTouches[0].pageX
            },passive)

            let that=this
            document.querySelector(".side").addEventListener("touchend",function(e){
                // e.preventDefault()
                ey=e.changedTouches[0].pageY
                ex=e.changedTouches[0].pageX
                if(Math.abs(ex - sx) > 20){
                    return false
                }
                if(Math.abs(ey - sy) > 20){
                    that.active=false 
                }
            },passive)

        })
    },
    methods:{
        change(){
            window.setTimeout(()=>{
                this.tip="“"+this.tipsSplit[Math.random()*this.tipsLength>>0]+"”"
                this.tipStatus=false
            },1500)
        },
        go(path){
            this.active=false
            this.$router.push({
                path:path
            })
        }
    }
} 
</script>


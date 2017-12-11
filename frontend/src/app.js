import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueRoot from './root'
import FastClick from 'fastclick'

FastClick.attach(document.body)

// require('offline-plugin/runtime').install();

Vue.use(VueRouter)
Vue.use(VueResource)
// Vue.config.silent = true
// Vue.config.productionTip = false
// Vue.config.devtools = false
Vue.http.options.headers = {'Content-Type':'application/x-www-form-urlencoded; charset=utf-8'}
Vue.http.options.emulateJSON = true

Date.prototype.format = function (fmt) {
	var month=['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];
	var week=['\u0053\u0075\u006e','\u004d\u006f\u006e','\u0054\u0075\u0065','\u0057\u0065\u0064','\u0054\u0068\u0075','\u0046\u0072\u0069','\u0053\u0061\u0074'];
    var options = {
    	"y+": this.getFullYear(),
        "m+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "i+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "n": this.getMilliseconds(), //毫秒 
        "M": month[this.getMonth()], //月
        "W": week[this.getDay()], //星期
    };
    for (var k in options){
    	if (new RegExp("(" + k + ")").test(fmt)){
	    	if(k=='y+'){
	    		fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	    	}else{
	    		fmt = fmt.replace(
		    		RegExp.$1,
		    		(RegExp.$1.length == 1) ?options[k]: (("00" + options[k]).substr(("" + options[k]).length))
		    	);
	    	}

	    } 
    }
    return fmt;
}

const router = new VueRouter({
	mode: 'history',
	linkActiveClass: 'active',
	routes: [
		{path: '/',component: r => require.ensure([], () => r(require('./pages/home.vue')), 'part')},
		{path: '/post/:id/',component: r => require.ensure([], () => r(require('./pages/home.vue')), 'part')},
		{path: '/playlist/',component: r => require.ensure([], () => r(require('./pages/playlist.vue')), 'part')}, 
 		{path: '/about/',component: r => require.ensure([], () => r(require('./pages/about.vue')), 'part')},
	],
})
router.beforeEach((to, from, next) => {
	if(to.path.substr(0,5)!="/post"){
		bus.$emit("hidePost")
	}
	next()
})
window.bus=new Vue()
bus.$on("playlist",function(){
	router.push({
		path:'/playlist/'
	})
})
new Vue({router,...VueRoot}).$mount('#app');

// (function() {
//   if('serviceWorker' in navigator) {
//     navigator.serviceWorker.register('/sw.js');
//   }
// })();
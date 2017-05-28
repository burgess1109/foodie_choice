
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component('modal', {template: '#modal-template'});

const app = new Vue({
    el: '#app',
    data: {
        message: 'Hello 吃貨 !', //title
        enabled_posts:[],
        disabled_posts:[],
        posts:[],
        errors: [],
        greeting:true,  //或false
        timer: 0,
        showModal: false,
        id:0,
        name:'',
        city:'',
        detail:'',
        tel:'',
        opentime:'',
        status:'',
    },
    created() {
        axios.get('/foodie').then(
            response => {
                // JSON responses are automatically parsed.
                var i=0;
                var j=0;
                for(var key in response.data){
                    var address = response.data[key].address;
                    address =  JSON.parse(address);
                    response.data[key].address = address[0];

                    //id轉換
                    if(typeof response.data[key]._id != 'undefined' && response.data[key]._id ){
                        response.data[key].id = response.data[key]._id;
                    }

                    if(response.data[key].status == 'enabled'){
                        this.enabled_posts[i] = response.data[key];
                        i++;
                    }else{
                        this.disabled_posts[j] = response.data[key];
                        j++;
                    }
                }
                this.posts=response.data;
            }).catch(e => {
                this.errors.push(e)
        })
    },
    methods: {
        start: function () {
            clearInterval(this.timer);
            this.timer = setInterval(this.get_restaurant,100);

            setTimeout(this.clear,5000);
        },
        clear:function(){
            clearInterval(this.timer);
        },
        get_restaurant:function(){
            var min = 0;
            var max = this.enabled_posts.length - 1;
            var rand_index =  parseInt(Math.random()*(max-min+1));

            for (var i=min;i<=max;i++)
            {
                if(i == rand_index){
                    document.getElementById("list_"+i).style.borderColor = '#ff9800';
                    document.getElementById("list_"+i).style.backgroundColor = '#FFDDAA';
                }else{
                    document.getElementById("list_"+i).style.borderColor = '#e3e3e3';
                    document.getElementById("list_"+i).style.backgroundColor = '#f5f5f5';
                }
            }
        },
        show:function (id) {
            if(id){
                axios.get('/foodie/'+id).then(
                    response => {
                        // JSON responses are automatically parsed.
                        var address = response.data.address;
                        address =  JSON.parse(address);
                        response.data.address = address[0];

                        this.id = id;
                        this.name = response.data.name;
                        this.city = response.data.address.city;
                        this.detail = response.data.address.detail;
                        this.tel =
                            response.data.tel;
                        this.opentime = response.data.opentime;
                        this.status = response.data.status;
                    }).catch(e => {
                        this.errors.push(e)
                })
            }else{
                this.id = 0;
                this.name = '';
                this.city = '';
                this.detail = '';
                this.tel = '';
                this.opentime = '';
                this.status = '';
            }
            this.showModal = true;
        },
        send:function (id) {
            if(this.name == ''){
                alert('名稱未填');
                return false;
            }

            if(id == 0){
                var method = 'post';
                var url = '/foodie';
                var type_str = '新增';
            }else{
                var method = 'put';
                var url = '/foodie/'+id;
                var type_str = '修改';
            }

            axios({
                method:method,
                url:url,
                data: {
                    name: this.name,
                    city: this.city,
                    detail: this.detail,
                    tel: this.tel,
                    opentime: this.opentime,
                    status: this.status
                }
            })
                .then(function(response) {
                    if(response.data == true){
                        alert(type_str+'成功!');
                    }else{
                        alert(type_str+'失敗!');
                    }
                    location.reload();
                })
                .catch(function (error) {
                    alert(type_str+'失敗!');
                    console.log(error);
                    location.reload();
                });

        },
        del:function (id) {
            if(id == '' && id ==0){
                alert('參數錯誤');
                return false;
            }else {
                axios({
                    method:'delete',
                    url:'/foodie/'+id
                })
                    .then(function(response) {
                        if(response.data == true){
                            alert('刪除成功!');
                        }else{
                            alert('刪除失敗!');
                        }
                        location.reload();
                    })
                    .catch(function (error) {
                        alert('刪除失敗!');
                        console.log(error);
                        location.reload();
                    });
            }
        }
    }
});
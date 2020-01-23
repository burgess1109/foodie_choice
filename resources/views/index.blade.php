<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>吃貨 Foodie</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header">
                        <slot name="header">
                            編輯餐廳
                        </slot>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>餐廳名稱</label>
                            <slot name="name"></slot>
                        </div>
                        <div class="form-group">
                            <label class="control-label">城市</label>
                            <slot name="city"></slot>
                        </div>
                        <div class="form-group">
                            <label>詳細住址</label>
                            <slot name="detail"></slot>
                        </div>
                        <div class="form-group">
                            <label>電話</label>
                            <slot name="tel"></slot>
                        </div>
                        <div class="form-group">
                            <label>營業時間</label>
                            <slot name="opentime"></slot>
                        </div>
                        <div class="form-group">
                            <label>是否加入選擇名單</label>
                            <slot name="status"></slot>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="btn btn-default" v-on:click="$emit('close')">
                                離開
                            </button>
                        </slot>


                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>

<div id="app">
    <div class="col-md-12">
        <h2>@{{ message }}</h2>
    </div>
    <div class="col-md-12">
        <h3>餐廳列表 : </h3>
    </div>
    <span style="display: none">@{{posts.length}}</span>

    <div class="main_div" v-if="enabled_posts && enabled_posts.length">
        <div class="col-md-3 block_div" v-for="(enabled_post,key) of enabled_posts"
             v-bind:id="'list_'+key" v-on:click="show(enabled_post.id)">
            <p><strong>@{{enabled_post.name}}</strong></p>
            <p>@{{enabled_post.tel}}</p>
            <p>@{{enabled_post.address.city}}
                <span v-if="enabled_post.address.detail">
                        - @{{enabled_post.address.detail}}
                        </span>
            </p>
        </div>
    </div>
    <div v-if="errors && errors.length">
        <div class="col-md-12" v-for="error of errors">
            @{{error.message}}
        </div>
    </div>

    <div class="col-md-12">
        <button v-on:click="start" class="btn btn-primary">開始</button>
        <a v-on:click="show(0)" class="btn btn-warning">新增餐廳</a>
    </div>
    <div class="col-md-12">
        <h3>未加入選擇名單 : </h3>
    </div>
    <div class="main_div" v-if="disabled_posts && disabled_posts.length">
        <div class="col-md-3 disabled_block_div" v-for="(disabled_post,key) of disabled_posts"
             v-on:click="show(disabled_post.id)">
            <p><strong>@{{disabled_post.name}}</strong></p>
            <p>@{{disabled_post.tel}}</p>
            <p>@{{disabled_post.address.city}}
                <span v-if="disabled_post.address.detail">
                        - @{{disabled_post.address.detail}}
                        </span>
            </p>
        </div>
    </div>

    <!-- use the modal component, pass in the prop -->
    <modal v-if="showModal" @close="showModal = false">
        <!--
      you can use custom content here to overwrite
      default content
        -->
        <h3 slot="header">編輯餐廳</h3>
        <input type="text" class="form-control" slot="name" name="name" v-model="name">

        <select class="form-control" name="city" slot="city" v-model="city">
            <option value=""></option>
            <option value="Taipei" v-if="city == 'Taipei'" selected>台北</option>
            <option value="Taipei">台北</option>
            <option value="NewTaipei" v-if="city == 'NewTaipei'" selected>新北</option>
            <option value="NewTaipei">新北</option>
            <option value="Taoyuan" v-if="city == 'Taoyuan'" selected>桃園</option>
            <option value="Taoyuan">桃園</option>
            <option value="Taichung" v-if="city == 'Taichung'" selected>台中</option>
            <option value="Taichung">台中</option>
            <option value="Tainan" v-if="city == 'Tainan'" selected>台南</option>
            <option value="Tainan">台南</option>
            <option value="Kaohsiung" v-if="city == 'Kaohsiung'" selected>高雄</option>
            <option value="Kaohsiung" v-else>高雄</option>
        </select>

        <input type="text" class="form-control" slot="detail" name="detail" v-model="detail">

        <input type="text" class="form-control" slot="tel" name="tel" v-model="tel">

        <input type="text" class="form-control" slot="opentime" name="opentime" v-model="opentime">

        <div slot="status">
            <label class="radio-inline">
                <input type="radio" name="status" value="enabled" v-if="status == 'enabled'"
                       v-model="status" checked>
                <input type="radio" name="status" value="enabled" v-model="status" v-else>
                是
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="disabled" v-if="status == 'disabled'"
                       v-model="status" checked>
                <input type="radio" name="status" value="disabled" v-model="status" v-else>
                否
            </label>
        </div>

        <div slot="footer">
            <button class="btn btn-primary" v-on:click="send(0)" v-if="id == 0">
                送出
            </button>
            <button class="btn btn-primary" v-on:click="send(id)" v-else>
                送出
            </button>
            <button class="btn btn-danger" v-on:click="del(id)" v-if="id != 0">
                刪除
            </button>
            <button class="btn btn-default" v-on:click="showModal = false">
                離開
            </button>
        </div>

    </modal>
</div>

<!-- Scripts -->
<script src="/js/app.js" data-path="{{$path}}"></script>
</body>
</html>

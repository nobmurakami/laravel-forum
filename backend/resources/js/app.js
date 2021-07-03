/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

// トースト通知
const showToastr = $(function () {
    $('[data-toastr]').each(function () {
        console.log($(this).data('toastr'));
        const design = $(this).data('toastr');

        if (design === 'error') {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "timeOut": 0,
                "extendedTimeOut": 0,
                "tapToDismiss": false
            }
        }

        toastr[design]($(this).val());
    });
});

// 画像プレビュー
const previewImage = $(function () {
    $('#image').on('change', function () {
        const file = this.files[0];
        const imgTagId = $(this).data('imgTagId');

        const reader = new FileReader();

        reader.onload = function () {
            $(imgTagId).attr('src', reader.result);
        };

        reader.onerror = function () {
            console.log(reader.error);
        };

        reader.readAsDataURL(file);
    });
});

// カスタムファイル入力
const customFileInput = $(function ()  {
    bsCustomFileInput.init();
});

// ポップオーバー
const popover = $(function ()  {
    let button = $('[data-toggle="popover"]');
    button.data('state', 'notPinned');

    // ポップオーバーの初期化とオプションの設定
    button.popover({
        html : true,
        placement : 'auto',
        trigger : 'manual',
        template : '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-header"></div><div class="popover-body"></div></div>',
    });

    // ボタンをホバーまたはクリックした時の処理
    button.on('mouseenter', function () {
        // ボタンの上にマウスカーソルが入った時
        // ポップオーバーがピン留めされていなければポップオーバーを表示する
        if (button.data('state') === 'notPinned') {
            $(this).popover('show');

            // ポップオーバーからマウスカーソルが外れた時の処理
            $('.popover').on('mouseleave', function () {
                if (button.data('state') === 'notPinned') {
                    $(this).popover('hide');
                }
            });
        }
    }).on('mouseleave', function () {
        // ボタンからマウスカーソルが外れた時
        // ポップオーバーがピン留めされておらず、
        // ポップオーバーにマウスカーソルが乗っていなければポップオーバーを消す
        if (!($(".popover:hover").length) && button.data('state') === 'notPinned')  {
            $(this).popover("hide");
        }
    }).on('click', function () {
        // ボタンをクリックした時
        if (button.data('state') === 'notPinned') {
            // ポップオーバーがピン留めされていなければピン留めする
            button.data('state', 'pinned');
            // ホバーがないスマホ/タブレット用の表示処理
            $(this).popover('show');
        } else if (button.data('state') === 'pinned') {
            // ポップオーバーがピン留めされていたらピン留めを解除する
            button.data('state', 'notPinned');
            // 押したボタンのポップオーバーの表示を切り替える
            $(this).popover('toggle');
            // 押したボタン以外のポップオーバーを消す
            button.not(this).popover('hide');
        }
    });

    // 画面のどこかをクリックした時の処理
    $(document).on('click', function (e) {
        // 押した要素がボタンおよびポップオーバー以外ならポップオーバーを消す
        if (e.target.closest('[data-toggle="popover"]') === null && e.target.closest('.popover') === null) {
            button.popover('hide');
            button.data('state', 'notPinned');
        }
    });
});

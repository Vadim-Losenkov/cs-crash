import Vue from 'vue';
import router from './router';
import VueCookie from 'vue-cookie';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VueSocketIOExt from 'vue-socket.io-extended';
import io from "socket.io-client";
import VueI18n from 'vue-i18n';
import Messages from './messages.js';

Vue.use(VueAxios, axios);
Vue.use(VueCookie);
Vue.use(VueI18n);

let lang = 'RU';
if (VueCookie.get('lang')) {
    lang = VueCookie.get('lang');
}

const i18n = new VueI18n({
    locale: lang,
    fallbackLocale: lang,
    messages: Messages
});

let port = '8081';
if (window.location.protocol === 'https:') port = '8443';

const socket = io(`${window.location.protocol}//${window.location.hostname}:${port}`);

Vue.use(VueSocketIOExt, socket);

axios.defaults.baseURL = '/api';

import Layout from "./components/Layout";

const app = new Vue({
    el: '#app',
    i18n,
    data: {
        user: null,
        config: {
            dollar: '',
            site_name: '',
            vk_group: '',
            percent_referral: '',
            hide_giveaway: 0
        },
        isLoading: false,
        globalLoading: true,
        isMobile: false,
        mobileSelectedItems: {
            items: {},
            price: 0.00
        },
        lang: 'RU'
    },
    mounted() {
        if (this.$cookie.get('token')) {
            this.getUser();
        } else {
            this.globalLoading = false;
        }

        if (this.$cookie.get('lang')) {
            this.lang = this.$cookie.get('lang');
        }

        this.getConfig();
    },
    methods: {
        getUser() {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

            axios.post('user/get')
                .then(res => {
                    if (typeof res.data.banned !== "undefined") {
                        this.showNotify('error', this.$t('user.blocked'));

                        this.$cookie.delete('token');

                        this.globalLoading = false;
                    } else {
                        this.user = res.data;

                        this.$emit('showInventories');

                        this.globalLoading = false;
                    }
                })
                .catch(error => {
                    this.$cookie.delete('token');

                    this.globalLoading = false;
                });

            this.$forceUpdate();
        },
        getConfig() {
            axios.post('getConfig')
                .then(res => {
                    this.config = res.data;
                })
        },
        showNotify(type, message) {
            new Noty({
                type: type,
                theme: 'nest',
                layout: 'topRight',
                text: message,
                timeout: 5000
            }).show();

            if (this.$cookie.get('sound') == 1) {
                this.playSound('notification');
            }
        },
        getRarityColor(rarity) {
            if (rarity === 'Contraband') {
                return '#ffcc00';
            } else if (rarity === 'Covert' || rarity === 'Extraordinary') {
                return '#ff0000';
            } else if (rarity === 'Classified') {
                return '#ff00ff';
            } else if (rarity === 'Restricted') {
                return '#800080';
            } else if (rarity === 'Mil-Spec Grade') {
                return '#0000ff';
            } else if (rarity === 'Industrial Grade') {
                return '#99ccff';
            } else {
                return '#c0c0c0';
            }
        },
        getRarityType(rarity) {
            if (rarity === 'Contraband') {
                return 'yellow';
            } else if (rarity === 'Covert' || rarity === 'Extraordinary') {
                return 'red';
            } else if (rarity === 'Classified') {
                return 'pink';
            } else if (rarity === 'Restricted') {
                return 'purple';
            } else if (rarity === 'Mil-Spec Grade') {
                return 'blue';
            } else {
                return 'light-blue';
            }
        },
        playSound(sound) {
            const  a = new Audio("".concat("/sounds/").concat(sound, ".mp3"));
            try {
                a.volume = .2, a.play();
            } catch (n) {}
        }
    },
    router,
    components: {
        Layout
    }
});

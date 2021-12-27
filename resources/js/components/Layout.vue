<template>
    <div v-if="!$root.globalLoading" style="height: 100%;">
        <div class="main-wrapper" :class="[$route.name === 'index' ? 'show-left-panel' : '']">
            <Header/>
            <div class="primary-content">
                <div id="page-content" v-show="$root.isLoading && !$root.isMobile">
                    <div class="loading show"></div>
                    <div class="loading show"></div>
                </div>
                <div class="panel-loading" v-show="$root.isLoading && $root.isMobile">
                    <div class="circle-loader"></div>
                </div>
                <router-view v-show="!$root.isLoading"/>
                <div class="footer">
                    <ul class="footer-links">
                        <li>
                            <router-link tag="a" :to="{name: 'terms'}">{{ $t('layout.terms') }}</router-link>
                        </li>
                    </ul>
                </div>
                <ChatModal/>
                <PaymentModal v-if="$root.user !== null"/>
            </div>
        </div>
        <nav v-if="$root.isMobile" class="mob-menu">
            <router-link tag="a" aria-current="page" class="mob-menu__btn" :class="[$route.name === 'index' ? 'active' : '']" :to="{name: 'index'}">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-controller"></use>
                </svg>
                <span>{{ $t('layout.game') }}</span>
            </router-link>
            <router-link tag="a" aria-current="page" class="mob-menu__btn" :class="[$route.name === 'inventory' ? 'active' : '']" :to="{name: 'inventory'}">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#machine-gun"></use>
                </svg>
                <span>{{ $t('layout.inventory') }}</span>
            </router-link>
            <router-link tag="a" :to="{name: 'bets'}" class="mob-menu__btn" :class="[$route.name === 'bets' ? 'active' : '']">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#money-bag"></use>
                </svg>
                <span>{{ $t('layout.bets') }}</span>
            </router-link>
            <router-link tag="a" :to="{name: 'chat'}" class="mob-menu__btn" :class="[$route.name === 'chat' ? 'active' : '']">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-chat"></use>
                </svg>
                <span>{{ $t('layout.chat') }}</span>
            </router-link>
            <button type="button" class="mob-menu__btn" :class="[activeMobileTab ? 'active' : '']" @click="setActiveMobileTab">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-dots"></use>
                </svg>
                <span>{{ $t('layout.still') }}</span>
            </button>
        </nav>
        <div v-if="$root.isMobile" class="mob-menu-panel" :class="[activeMobileTab ? 'active' : '']">
            <router-link class="mob-menu-panel__link" :to="{name: 'profile'}" v-if="$root.user !== null">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-male-user"></use>
                </svg>
                {{ $t('layout.profile') }}
            </router-link>
            <router-link class="mob-menu-panel__link" :to="{name: 'faq'}">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-question"></use>
                </svg>
                F.A.Q
            </router-link>
            <router-link class="mob-menu-panel__link" :to="{name: 'support'}">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#icon-support"></use>
                </svg>
                {{ $t('layout.support') }}
            </router-link>
            <div class="mob-menu-panel__controls">
                <div class="header-control">
                    <button type="button" class="flat-btn-icon btn-sound nots-btn" :class="[$cookie.get('sound') == 1 ? 'active' : '']" @click="setSound" style="margin-right: 0.6rem;">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#speaker"></use>
                        </svg>
                    </button>
                    <ul class="header-soc-links">
                        <li>
                            <a :href="$root.config.vk_group" class="soc-link" aria-label="Группа Вконтакте"
                                   title="Группа Вконтакте">
                                <svg aria-hidden="true" style="width: 3.6em; height: 2.1em;">
                                    <use xlink:href="/svg/svg.svg#vk"></use>
                                </svg>
                            </a>
                        </li>
                    </ul>
                    <div class="online-stats"><b>{{ online }}</b><span>Online</span></div>
                </div>
            </div>
        </div>
        <Chat v-if="!$root.isMobile"/>
    </div>
    <div v-else>
        <div id="page-content" v-show="!$root.isMobile">
            <div class="loading show" style="right: 0;"></div>
            <div class="loading show" style="right: 0;"></div>
        </div>
        <div class="panel-loading" v-show="$root.isMobile">
            <div class="circle-loader"></div>
        </div>
    </div>
</template>

<script>
    import Header from "./header/Header";
    import Chat from "./chat/Chat";
    import ChatModal from "./modals/chat/Chat";
    import PaymentModal from "./modals/payment/Payment";

    export default {
        components: {
            Header,
            Chat,
            ChatModal,
            PaymentModal
        },
        data() {
            return {
                activeMobileTab: false,
                online: 0
            }
        },
        mounted() {
            const app = this;

            if (document.documentElement.clientWidth <= 1025) {
                app.$root.$emit('hideMobileChat');
                this.$root.isMobile = true;
                this.$root.$forceUpdate();
            } else {
                this.$root.isMobile = false;
                this.$root.$forceUpdate();
            }

            $(window).resize(function () {
                if (document.documentElement.clientWidth <= 1025) {
                    app.$root.$emit('hideMobileChat');
                    app.isMobile = true;
                    app.$root.$forceUpdate();
                } else {
                    app.$root.isMobile = false;
                    app.$root.$forceUpdate();
                }
            });
        },
        methods: {
            setActiveMobileTab() {
                if (this.activeMobileTab) {
                    this.activeMobileTab = false;
                } else {
                    this.activeMobileTab = true;
                }
            },
            setSound() {
                if (this.$cookie.get('sound') == 1) {
                    this.$cookie.set('sound', 0);
                } else {
                    this.$cookie.set('sound', 1);
                }
                this.$forceUpdate();
            }
        },
        sockets: {
            online(online) {
                this.online = online;
            },
            notify(data) {
                if (this.$root.user !== null && this.$root.user.id === data.user_id) {
                    if (data.success) {
                        if (typeof data.refreshInventory !== "undefined" && data.refreshInventory) {
                            this.$root.$emit('showInventories');
                        }
                        this.$root.showNotify('success', this.$t(`payment.${data.message}`));
                    } else {
                        this.$root.showNotify('error', this.$t(`payment.${data.message}`));
                    }
                }
            }
        }
    }
</script>

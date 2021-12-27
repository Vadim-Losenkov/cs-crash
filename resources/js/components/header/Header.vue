<template>
    <div>
        <header class="header">
            <div class="cur-u-header">
                <router-link tag="a" :to="{name: 'index'}" class="logo"></router-link>
                <button type="button" class="flat-btn-icon btn-sound"
                        :class="[$cookie.get('sound') == 1 ? 'active' : '']" @click="setSound">
                    <svg aria-hidden="true">
                        <use xlink:href="/svg/svg.svg#speaker"></use>
                    </svg>
                </button>
                <div class="header-langs" v-if="!$root.isMobile">
                    <button v-if="$root.lang === 'RU'" type="button" class="header-langs__current" :class="[activeLangTab ? 'active' : '']" @click="setLangActiveTab" data-toggle="header-langs-list"
                            aria-label="Русский" title="RU"><span>RU</span>
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <button v-if="$root.lang === 'ENG'" type="button" class="header-langs__current" :class="[activeLangTab ? 'active' : '']" @click="setLangActiveTab" data-toggle="header-langs-list"
                            aria-label="English" title="ENG"><span>ENG</span>
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <ul class="header-langs__list" :class="[activeLangTab ? 'active' : '']">
                        <li>
                            <button @click="setLang('RU')" type="button" class="header-langs__lang" aria-label="Русский" title="Русский"><span>RU</span>
                            </button>
                        </li>
                        <li>
                            <button @click="setLang('ENG')" type="button" class="header-langs__lang" aria-label="English" title="English"><span>ENG</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-main">
                <router-link tag="a" :to="{name: 'index'}" class="logo"></router-link>
                <nav class="nav">
                    <router-link tag="a" :to="{name: 'index'}" class="nav__link nav__link--home"
                                 :class="[$route.name === 'index' ? 'active' : '']">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#home"></use>
                        </svg>
                        <span>{{ $t('header.main') }}</span>
                    </router-link>
                    <router-link tag="a" :to="{name: 'faq'}" class="nav__link nav__link--faq"
                                 :class="[$route.name === 'faq' ? 'active' : '']">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#faq"></use>
                        </svg>
                        <span>F.A.Q</span>
                    </router-link>
                    <router-link tag="a" :to="{name: 'support'}" class="nav__link nav__link--support"
                                 :class="[$route.name === 'support' ? 'active' : '']">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#support"></use>
                        </svg>
                        <span>{{ $t('header.support') }}</span>
                    </router-link>
                </nav>
                <button v-if="$root.user === null" type="button" class="btn btn--green steam-login"
                        @click="openAuth">
                    <svg aria-hidden="true">
                        <use xlink:href="/svg/svg.svg#steam"></use>
                    </svg>
                    <div class="hide-above-l">{{ $t('header.sign') }}</div>
                    <div class="hide-below-m">{{ $t('header.sign_steam') }}</div>
                </button>
                <div v-else class="header-user">
                    <router-link tag="a" :to="{name: 'profile'}" class="header-user__about">
                        <span class="header-user__name">{{ $root.user.username }}</span>
                        <div class="header-user__photo">
                            <img :src="$root.user.avatar" :alt="$root.user.username">
                        </div>
                    </router-link>
                    <div @click="$root.$emit('showPayment')" class="header-user__balance">{{
                        $root.user.balance.toFixed(2) }} $
                    </div>
                    <button type="button" class="btn btn--small btn--purple" @click="$root.$emit('showPayment')">
                        {{ $t('header.deposit') }}
                    </button>
                    <button type="button" class="btn-base btn-exit" @click="logOut">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#logout"></use>
                        </svg>
                    </button>
                </div>
                <div class="header-langs" v-if="$root.isMobile">
                    <button v-if="$root.lang === 'RU'" type="button" class="header-langs__current" :class="[activeLangTab ? 'active' : '']" @click="setLangActiveTab" data-toggle="header-langs-list"
                            aria-label="Русский" title="RU"><span>RU</span>
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <button v-if="$root.lang === 'ENG'" type="button" class="header-langs__current" :class="[activeLangTab ? 'active' : '']" @click="setLangActiveTab" data-toggle="header-langs-list"
                            aria-label="English" title="ENG"><span>ENG</span>
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <ul class="header-langs__list" :class="[activeLangTab ? 'active' : '']">
                        <li>
                            <button @click="setLang('RU')" type="button" class="header-langs__lang" aria-label="Русский" title="Русский"><span>RU</span>
                            </button>
                        </li>
                        <li>
                            <button @click="setLang('ENG')" type="button" class="header-langs__lang" aria-label="English" title="English"><span>ENG</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-controls">
                <div class="header-control">
                    <ul class="header-soc-links">
                        <li><a :href="$root.config.vk_group" class="soc-link" :aria-label="$t('header.vk_group')"
                               :title="$t('header.vk_group')">
                            <svg aria-hidden="true" style="width: 3.6em; height: 2.1em;">
                                <use xlink:href="/svg/svg.svg#vk"></use>
                            </svg>
                        </a></li>
                    </ul>
                    <div class="online-stats"><b>{{ online }}</b><span>Online</span></div>
                </div>
            </div>
        </header>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                online: 0,
                activeLangTab: false
            }
        },
        mounted() {
            if (this.$cookie.get('sound') === null) {
                this.$cookie.set('sound', 1);
            }
            this.$root.$on('openAuth', () => {
                this.openAuth();
            });
        },
        methods: {
            openAuth() {
                let width = 860;
                let height = 500;
                let left = (screen.width / 2) - (width / 2);
                let top = (screen.height / 2) - (height / 2);
                let windowOptions = `menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=${width}, height=${height}, top=${top}, left=${left}`;
                let type = 'auth';

                window.open('/api/auth/steam', type, windowOptions);

                window.addEventListener("message", this.initToken, false);
            },
            initToken(event) {
                if (event.data.length > 0) {
                    const token = event.data.slice(7);
                    this.$cookie.set('token', token);
                    this.$root.getUser();
                }
            },
            logOut() {
                this.$cookie.delete('token');
                window.location.href = '/';
            },
            setSound() {
                if (this.$cookie.get('sound') == 1) {
                    this.$cookie.set('sound', 0);
                } else {
                    this.$cookie.set('sound', 1);
                }
                this.$forceUpdate();
            },
            setLangActiveTab() {
                if (this.activeLangTab) {
                    this.activeLangTab = false;
                } else {
                    this.activeLangTab = true;
                }
            },
            setLang(lang) {
                this.$root.lang = lang;
                this.$cookie.set('lang', lang);
                this.activeLangTab = false;
                this.$i18n.locale = lang;
            }
        },
        sockets: {
            online: function (online) {
                this.online = online;
            }
        }
    }
</script>

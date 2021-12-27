<template>
    <div class="inner-page">
        <div class="account" v-if="user && !$root.isMobile">
            <div class="page-header">
                <h1 class="page-title">{{ $t('user.profile') }}
                    <span class="orange">{{ user.username }}</span>
                </h1>
            </div>
            <div class="form-panel trade-link">
                <div class="form-panel-header">
                    <div class="form-panel-title form-panel-title--offset">Trade url</div>
                    <a :href="'https://steamcommunity.com/profiles/' + user.steamid + '/tradeoffers/privacy'"
                       target="_blank"
                       class="link green"><span class="hide-below-m">{{ $t('profile.trade_url') }}</span><span
                        class="hide-above-l">{{ $t('profile.trade_url') }}</span></a></div>
                <div class="form-panel-controls"><label for="profile-trade-url" class="field-wrapper"><input type="text"
                                                                                                             id="profile-trade-url"
                                                                                                             class="field-input green"
                                                                                                             v-model="user.trade_link"></label>
                    <button type="button" class="btn btn--green" @click="saveUrl"><span
                        class="hide-below-m">{{ $t('profile.save') }}</span><span
                        class="hide-above-l">{{ $t('profile.save_mobile') }}</span></button>
                </div>
            </div>
            <div class="form-panels hide-below-m">
                <div class="form-panel ref-system">
                    <div class="form-panel-header">
                        <div class="form-panel-title">{{ $t('profile.referral') }}</div>
                        <router-link class="link orange" tag="a" :to="{name: 'faq'}"><span>{{ $t('profile.how') }}</span></router-link>
                    </div>
                    <div class="form-panel-controls"><label for="referral-url" class="field-wrapper"><input type="text"
                                                                                                            id="referral-url"
                                                                                                            class="field-input orange"
                                                                                                            readonly=""
                                                                                                            :value="domain + '?ref=' + user.referral_code"></label>
                    </div>
                </div>
                <div class="account-stats">
                    <div class="account-stats__item">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#add-user"></use>
                        </svg>
                        <div class="account-stats__value">{{ user.referrals }}</div>
                        <div class="account-stats__desc">{{ $t('profile.referrals') }}</div>
                    </div>
                    <div class="account-stats__item">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#sale"></use>
                        </svg>
                        <div class="account-stats__value">{{ $root.config.percent_referral }}%</div>
                        <div class="account-stats__desc">{{ $t('profile.referral_procent') }}</div>
                    </div>
                    <div class="account-stats__item">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#coin"></use>
                        </svg>
                        <div class="account-stats__value">{{ user.referral_sum.toFixed(2) }}$</div>
                        <div class="account-stats__desc">{{ $t('profile.referral_sum') }}</div>
                    </div>
                </div>
                <div class="form-panel promo-link">
                    <div class="form-panel-header">
                        <div class="form-panel-title">{{ $t('profile.promo') }}</div>
                        <router-link tag="a" :to="{name: 'faq'}" class="link light-blue"><span
                            class="hide-below-m">{{ $t('profile.how_promo') }}</span><span class="hide-above-l">{{ $t('profile.how_promo') }}</span>
                        </router-link>
                    </div>
                    <div class="form-panel-controls"><label for="profile-promo-input" class="field-wrapper"><span
                        class="sr-only">{{ $t('profile.enter_promo') }}</span><input type="text" id="profile-promo-input"
                                                                 class="field-input light-blue"
                                                                 v-model="promocode"
                                                                 :placeholder="$t('profile.send')" value=""></label>
                        <button type="button" :disabled="promocode.length === 0" @click="usePromocode"
                                class="btn btn--blue">{{ $t('profile.send') }}
                        </button>
                    </div>
                </div>
            </div>
            <ul class="tabs hide-below-m">
                <li>
                    <button type="button" class="tab-btn" :class="[tab === 'bets' ? 'active' : '']"
                            @click="tab = 'bets'">{{ $t('profile.stats') }}
                    </button>
                </li>
                <li>
                    <button type="button" class="tab-btn" :class="[tab === 'withdraws' ? 'active' : '']"
                            @click="tab = 'withdraws'">{{ $t('profile.history') }}
                    </button>
                </li>
            </ul>
            <div class="table-wrapper" v-if="tab === 'bets'">
                <table>
                    <thead>
                    <tr>
                        <th>{{ $t('user.id_round') }}:</th>
                        <th>{{ $t('user.items') }}:</th>
                        <th>{{ $t('user.status') }}:</th>
                        <th>{{ $t('user.win') }}:</th>
                        <th>{{ $t('user.multiplier') }}:</th>
                        <th>{{ $t('user.bet') }}:</th>
                        <th>{{ $t('user.date') }}:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="bet in bets">
                        <td><router-link tag="a" :to="{name: 'game', params: {id: bet.game.id}}">#{{ bet.game.id }}</router-link></td>
                        <td>
                            <ul class="round-item-cases">
                                <li v-for="item in bet.items">
                                    <img :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'" alt="">
                                </li>
                            </ul>
                        </td>
                        <td>
                            <span v-if="bet.is_win === 2" class="red">{{ $t('user.crash') }}</span>
                            <span v-if="bet.is_win === 1" class="green">{{ $t('user.withdraw') }}</span>
                            <span v-if="bet.is_win === 0" class="light-blue">{{ $t('user.wait') }}</span>
                        </td>
                        <td>
                            <span v-if="bet.is_win === 2" class="red">-{{ bet.bank.toFixed(2) }}$</span>
                            <span v-if="bet.is_win === 1" class="green">{{ (bet.win.toFixed(2) - bet.bank.toFixed(2)).toFixed(2) }}$</span>
                        </td>
                        <td>
                            <span v-if="bet.is_win > 0">{{ bet.multiplier.toFixed(2) }}x</span>
                        </td>
                        <td>
                            <div class="orange">{{ bet.bank.toFixed(2) }} $</div>
                        </td>
                        <td>{{ bet.date }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="history-list" v-if="tab === 'withdraws'">
                <ul class="history-list__list">
                    <li v-for="withdraw in withdraws">
                        <button class="btn-base drop-preview"
                                :style="{color: $root.getRarityColor(withdraw.item.rarity)}">
                            <div v-if="withdraw.status === 1"
                                 class="drop-preview__status drop-preview__status--success">
                                <svg aria-hidden="true">
                                    <use xlink:href="/svg/svg.svg#tick"></use>
                                </svg>
                            </div>
                            <div v-if="withdraw.status === 2" class="drop-preview__status drop-preview__status--error">
                                <svg aria-hidden="true">
                                    <use xlink:href="/svg/svg.svg#close"></use>
                                </svg>
                            </div>
                            <div v-if="withdraw.status === 0"
                                 class="drop-preview__status drop-preview__status--waiting">
                                <svg aria-hidden="true">
                                    <use xlink:href="/svg/svg.svg#timer"></use>
                                </svg>
                            </div>
                            <div class="drop-preview__photo">
                                <img
                                    :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + withdraw.item.image + '/128fx128f/image.png'">
                            </div>
                            <div class="drop-preview__title">
                                {{ withdraw.item.market_hash_name.split('|')[0] }}
                            </div>
                            <div class="drop-preview__subtitle">
                                {{ withdraw.item.market_hash_name.split('|')[1] }}
                            </div>
                            <div class="drop-preview__desc">
                                {{ withdraw.item.exterior }}
                            </div>
                            <div class="drop-preview__price">
                                {{ withdraw.item.price.toFixed(2) }} $
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="account" v-if="user && $root.isMobile">
            <div class="page-header">
                <h1 class="page-title">{{ $t('user.profile') }}
                    <span class="orange">{{ user.username }}</span>
                </h1>
            </div>
            <div class="account-btns">
                <button type="button" class="btn btn--purple" @click="$root.$emit('showPayment')">{{ $t('header.deposit') }}</button>
                <button type="button" class="btn btn--blue" @click="$root.$emit('showPromo')">{{ $t('profile.promo') }}</button>
            </div>
            <div class="form-panel trade-link">
                <div class="form-panel-header">
                    <div class="form-panel-title form-panel-title--offset">Trade url</div>
                    <a :href="'https://steamcommunity.com/profiles/' + user.steamid + '/tradeoffers/privacy'"
                       target="_blank"
                       class="link green"><span class="hide-below-m">{{ $t('profile.trade_url') }}</span><span
                        class="hide-above-l">{{ $t('profile.trade_url') }}</span></a></div>
                <div class="form-panel-controls"><label for="profile-trade-url" class="field-wrapper"><input type="text"
                                                                                                             id="profile-trade-url"
                                                                                                             class="field-input green"
                                                                                                             v-model="user.trade_link"></label>
                    <button type="button" class="btn btn--green" @click="saveUrl"><span
                        class="hide-below-m">{{ $t('profile.save') }}</span><span
                        class="hide-above-l">{{ $t('profile.save_mobile') }}</span></button>
                </div>
            </div>
            <ul class="tabs">
                <li><a class="tab-btn" @click="$root.$emit('showReferral')">{{ $t('profile.referral_system') }}</a></li>
                <li><a class="tab-btn" @click="$root.$emit('showStats')">{{ $t('profile.stats') }}</a></li>
                <li><a class="tab-btn" @click="$root.$emit('showWithdraws')">{{ $t('profile.history') }}</a></li>
                <li>
                    <button type="button" class="tab-btn tab-btn--exit" @click="logOut">
                        <div class="sr-only">{{ $t('profile.logout') }}</div>
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#logout"></use>
                        </svg>
                    </button>
                </li>
            </ul>
        </div>
        <Promo v-if="user" />
        <Referral v-if="user" :user="user" />
        <Stats v-if="user" :user="user" :bets="bets" />
        <Withdraws v-if="user" :user="user" :withdraws="withdraws" />
    </div>
</template>

<script>
    import Promo from "../../components/modals/promo/Promo";
    import Referral from "../../components/modals/referral/Referral";
    import Stats from "../../components/modals/stats/Stats";
    import Withdraws from "../../components/modals/withdraws/Withdraws";

    export default {
        components: {
            Promo,
            Referral,
            Stats,
            Withdraws
        },
        data() {
            return {
                loaded: false,
                user: null,
                domain: '',
                bets: {},
                tab: 'bets',
                promocode: '',
                withdraws: {}
            }
        },
        mounted() {
            this.$root.isLoading = true;

            if (!this.$cookie.get('token')) {
                this.$root.isLoading = false;
                this.$router.go(-1);
            }

            this.domain = window.location.protocol + '//' + window.location.hostname;

            setTimeout(() => {
                this.getUser();
            }, 100);
        },
        methods: {
            getUser() {
                this.$root.axios.post('/user/getProfile')
                    .then(res => {
                        const data = res.data;

                        this.loaded = true;
                        this.user = data.user;
                        this.bets = data.bets;
                        this.withdraws = data.withdraws;
                        this.$root.isLoading = false;
                    })
                    .catch(err => {
                        this.$root.isLoading = false;
                        this.$router.go(-1);
                    })
            },
            saveUrl() {
                this.$root.axios.post('/user/saveUrl', {
                    trade_link: this.user.trade_link
                })
                    .then(res => {
                        const data = res.data;

                        this.$root.showNotify(data.type, this.$t(`index.${data.message}`));
                    })
            },
            usePromocode() {
                this.$root.axios.post('/user/usePromo', {
                    promocode: this.promocode
                })
                    .then(res => {
                        const data = res.data;

                        if (data.type === 'success') {
                            this.$root.user.balance = data.newBalance;
                        }

                        this.$root.showNotify(data.type, this.$t(`index.${data.message}`));
                    })
            },
            logOut() {
                this.$cookie.delete('token');
                window.location.href = '/';
            }
        }
    }
</script>

<template>
    <div class="inner-page">
        <div class="account" v-if="loaded">
            <div class="page-header">
                <h1 class="page-title">{{ $t('user.profile') }} <span class="orange">{{ user.username }}</span></h1>
            </div>
            <div class="table-wrapper" v-if="!$root.isMobile">
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
            <div class="game-stats">
                <div class="game-stats-list-wrapper">
                    <ul class="game-stats-list shadow has-scroll has-shadow">
                        <div>
                            <li v-for="bet in bets" class="round-item"
                                :class="[[bet.is_win === 0 ? 'animate-item-enter-done' : ''],
                                 [bet.is_win === 1 ? 'round-item--green' : ''],
                                 [bet.is_win === 2 ? 'round-item--red' : '']]">
                                <router-link tag="a"
                                             :to="{name: 'user', params: {steamid: bet.user.steamid}}"
                                             class="round-item-photo">
                                    <img
                                        :src="bet.user.avatar"
                                        :alt="bet.user.username">
                                </router-link>
                                <ul class="round-item-cases">
                                    <li class="round-item-tooltip" v-for="item in bet.items">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ item.market_hash_name }}</span>
                                        </div>
                                    </li>
                                    <div v-if="bet.itemsMore > 0" class="round-item-cases-more">
                                        +{{bet.itemsMore }}
                                    </div>
                                </ul>
                                <div class="round-item-price price">{{ bet.bank.toFixed(2) }} $</div>
                                <div class="status-label" v-if="bet.is_win === 0">{{ $t('index.in_game') }}</div>
                                <div class="status-label status-label--red" v-if="bet.is_win === 2">{{
                                    parseFloat(bet.multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="status-label status-label--green" v-if="bet.is_win === 1">{{
                                    parseFloat(bet.multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="round-item-selected" v-if="bet.is_win === 1">
                                    <div class="round-item-selected-price price">{{ bet.win.toFixed(2) }} $
                                    </div>
                                    <div class="round-item-selected-photo round-item-tooltip round-item-tooltip__right">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + bet.winItem.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ bet.winItem.market_hash_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loaded: false,
                user: {},
                bets: {}
            }
        },
        mounted() {
            this.$root.isLoading = true;

            this.getUser();
        },
        methods: {
            getUser() {
                this.$root.axios.post('/user/getUser', {
                    steamid: this.$route.params.steamid
                })
                    .then(res => {
                        const data = res.data;

                        if (data.success) {
                            this.loaded = true;
                            this.user = data.user;
                            this.bets = data.bets;
                            this.$root.isLoading = false;
                        } else {
                            this.$root.isLoading = false;
                            this.$router.go(-1);
                        }
                    })
                    .catch(err => {
                        this.$root.isLoading = false;
                        this.$router.go(-1);
                    })
            }
        },
        watch: {
            '$route.params.steamid' (value) {
                this.$root.isLoading = true;

                this.getUser();
            }
        }
    }
</script>

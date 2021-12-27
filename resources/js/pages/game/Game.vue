<template>
    <div class="inner-page" v-if="loaded && game">
        <div class="page-header">
            <div class="page-title">
                <span class="hide-above-l">{{ $t('game.stats') }} #{{ game.id }}</span>
                <span class="hide-below-m">{{ $t('game.stats') }} #{{ game.id }}</span>
            </div>
        </div>
        <div class="game-stats-page">
            <div class="game-stats-header-wrapper">
                <div class="game-stats-header">
                    <div class="game-stats-header__title">
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#icon-bar-chart"></use>
                        </svg>
                        <span>{{ $t('game.stats') }}</span></div>
                    <ul class="game-stats-header__list">
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#money-bag"></use>
                            </svg>
                            <span>{{ $t('index.bank') }}</span><b>{{ game.bank.toFixed(2) }}</b></li>
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#avatar"></use>
                            </svg>
                            <span>{{ $t('index.members') }}</span><b>{{ game.members }}</b></li>
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#machine-gun"></use>
                            </svg>
                            <span>{{ $t('index.skins') }}</span><b>{{ game.skins }}</b></li>
                    </ul>
                </div>
            </div>
            <div class="game-stats">
                <div class="game-stats-list-wrapper">
                    <ul class="game-stats-list shadow has-scroll has-shadow">
                        <div>
                            <li v-for="bet in Object.keys(this.game.bets).sort(( (e, t) => {
                                    if (this.$root.user !== null) {
                                        if (this.game.bets[e].user.steamid === this.$root.user.steamid) {
                                            return -1;
                                        } else {
                                            return 0;
                                        }
                                    } else {
                                        return 0;
                                    }
                                }))" class="round-item"
                                :class="[[game.bets[bet].is_win === 0 ? 'animate-item-enter-done' : ''],
                                 [game.bets[bet].is_win === 1 ? 'round-item--green' : ''],
                                 [game.bets[bet].is_win === 2 ? 'round-item--red' : '']]">
                                <router-link tag="a"
                                             :to="{name: 'user', params: {steamid: game.bets[bet].user.steamid}}"
                                             class="round-item-photo">
                                    <img
                                        :src="game.bets[bet].user.avatar"
                                        :alt="game.bets[bet].user.username">
                                </router-link>
                                <ul class="round-item-cases">
                                    <li class="round-item-tooltip" v-for="item in game.bets[bet].items">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ item.market_hash_name }}</span>
                                        </div>
                                    </li>
                                    <div v-if="game.bets[bet].itemsMore > 0" class="round-item-cases-more">
                                        +{{game.bets[bet].itemsMore }}
                                    </div>
                                </ul>
                                <div class="round-item-price price">{{ game.bets[bet].bank.toFixed(2) }} $</div>
                                <div class="status-label" v-if="game.bets[bet].is_win === 0">{{ $t('index.in_game') }}</div>
                                <div class="status-label status-label--red" v-if="game.bets[bet].is_win === 2">{{
                                    parseFloat(game.bets[bet].multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="status-label status-label--green" v-if="game.bets[bet].is_win === 1">{{
                                    parseFloat(game.bets[bet].multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="round-item-selected" v-if="game.bets[bet].is_win === 1">
                                    <div class="round-item-selected-price price">{{ game.bets[bet].win.toFixed(2) }} $
                                    </div>
                                    <div class="round-item-selected-photo round-item-tooltip round-item-tooltip__right">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + game.bets[bet].winItem.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ game.bets[bet].winItem.market_hash_name }}</span>
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
                game: null
            }
        },
        mounted() {
            this.$root.isLoading = true;

            this.getGame();
        },
        methods: {
            getGame() {
                this.$root.axios.post('/crash/getGame', {
                    id: this.$route.params.id
                })
                    .then(res => {
                        const data = res.data;

                        if (data.success) {
                            this.loaded = true;
                            this.game = data.game;
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
        }
    }
</script>


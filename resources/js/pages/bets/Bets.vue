<template>
    <div style="height: 100%;">
        <div class="page-header">
            <div class="page-title">{{ $t('index.stats') }}</div>
        </div>
        <div class="mob-stats">
            <div class="mob-stats__item"><span>{{ $t('index.members') }}: </span><b>{{ game.stats.members }}</b></div>
            <div class="mob-stats__item"><span>{{ $t('index.skins') }}: </span><b>{{ game.stats.skins }}</b></div>
            <div class="mob-stats__item"><b>{{ game.stats.bank.toFixed(2) }} $</b></div>
        </div>
        <div class="game-stats">
            <div class="game-stats-list-wrapper">
                <ul class="game-stats-list shadow has-scroll has-shadow">
                    <div v-if="Object.keys(this.game.bets).length > 0">
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
                    <div class="panel-loading" v-else>
                        <div class="circle-loader"></div>
                        <div class="panel-loading__title">
                            {{ $t('index.wait') }}
                        </div>
                        <div class="panel-loading__desc">
                            {{ $t('index.wait_bets') }}
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                game: {
                    stats: {
                        bank: 0.00,
                        members: 0,
                        skins: 0
                    },
                    bets: {}
                }
            }
        },
        mounted() {
            this.$root.isLoading = true;

            this.$root.$socket.client.emit('getGame');
        },
        methods: {
            drawBets(data) {
                this.game.bets = data;
            }
        },
        sockets: {
            clearBets() {
                this.game.bets = [];
                this.game.stats = {
                    members: 0,
                    bank: 0.00,
                    skins: 0
                };
                if (this.$cookie.get('sound') == 1) {
                    this.$root.playSound('push');
                }
            },
            setStats(data) {
                this.game.stats = {
                    members: data.members,
                    bank: data.bank,
                    skins: data.skins
                }
            },
            setGame(data) {
                this.drawBets(data.bets);

                this.$root.isLoading = false;
            },
            newBets(data) {
                this.drawBets(data);
            }
        }
    }
</script>

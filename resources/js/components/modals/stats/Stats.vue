<template>
    <div class="modal promo-modal" :class="[active ? 'active' : '']" role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content" style="max-width: 1000px;">
            <div class="modal-title hide-above-l">{{ $t('profile.stats') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
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
    </div>
</template>

<script>
    export default {
        props: ['user', 'bets'],
        data() {
            return {
                active: false
            }
        },
        mounted() {
            this.$root.$on('showStats', () => {
                this.active = true;
            });
        }
    }
</script>

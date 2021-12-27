<template>
    <div class="modal promo-modal" :class="[active ? 'active' : '']" role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content" style="max-width: 1000px;">
            <div class="modal-title hide-above-l">{{ $t('profile.history') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
                <ul class="history-list__list">
                    <li v-for="withdraw in withdraws" style="padding: 5px 5px;">
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
                            <div class="drop-preview__price" style="left: 10px;">
                                {{ withdraw.item.price.toFixed(2) }} $
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user', 'withdraws'],
        data() {
            return {
                active: false
            }
        },
        mounted() {
            this.$root.$on('showWithdraws', () => {
                this.active = true;
            });
        }
    }
</script>

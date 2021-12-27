<template>
    <div class="modal promo-modal" :class="[active ? 'active' : '']" role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content">
            <div class="modal-title hide-above-l">{{ $t('profile.activation_promo') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
                <div class="promo-form"><label for="enter-promo-input" class="field-wrapper"><span
                    class="field-wrapper__title sr-only">{{ $t('profile.promo_code') }}</span><input type="text" id="enter-promo-input"
                                                                                class="field-input field-input--big"
                                                                                v-model="promocode"
                                                                                :placeholder="$t('profile.promo_code')"
                                                                                value=""></label>
                    <button type="button" class="btn btn--blue" :disabled="promocode.length === 0" @click="usePromocode">{{ $t('profile.activate_promo') }}</button>
                    <div class="promo-form-desc">{{ $t('profile.bonus') }}</div>
                    <router-link class="btn btn--purple" tag="a" :to="{name: 'faq'}">{{ $t('profile.how_promo') }}</router-link></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                active: false,
                promocode: ''
            }
        },
        mounted() {
            this.$root.$on('showPromo', () => {
                this.active = true;
            });
        },
        methods: {
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
            }
        }
    }
</script>

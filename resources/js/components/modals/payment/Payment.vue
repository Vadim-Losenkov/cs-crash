<template>
    <div class="modal balance-modal" :class="[active ? 'active' : '']" role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content">
            <div class="modal-title hide-above-l">{{ $t('payment.select_method') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
                <div class="modal-title hide-below-m" id="balance-modal-title">{{ $t('payment.sum_ruble') }}</div>
                <form class="balance-form">
                    <div class="from-to" v-if="$root.lang === 'RU'">
                        <label for="payment-dollar" class="from-to__from">
                            <span>Сумма в $</span>
                            <input id="payment-dollar" type="number" class="input-base from-to__input"
                                   @input="changeDollar" v-model="sumDollar">
                        </label>
                        <label for="payment-rub" class="from-to__to">
                            <span>Сумма в руб.</span>
                            <input id="payment-rub" type="number" class="input-base from-to__input" @input="changeRuble"
                                   v-model="sumRuble">
                        </label>
                    </div>
                    <div class="from-to payment-input-oneline" v-else>
                        <label for="payment-dollar" class="from-to__from">
                            <span>The amount in $</span>
                            <input @input="changeDollar" v-model="sumDollar" id="payment-dollar" type="number" class="input-base from-to__input" value="0">
                        </label>
                    </div>
                    <div class="balance-form-btns">
                        <button type="button" style="width: 100%" class="btn btn--green" @click="paymentSum">{{ $t('payment.payment') }}</button>
<!--                        <button type="button" class="btn btn&#45;&#45;purple" @click="paymentSkins">{{ $t('payment.skins') }}</button>-->
                    </div>
                    <div class="balance-form-desc" v-if="$root.lang === 'RU'">
                        Поставь в никнейм <span class="orange">“{{ $root.config.site_name.toLowerCase() }}”</span>
                        и получи
                        бонус<br><span class="orange">10%</span> к пополнению
                    </div>
                    <div class="balance-form-desc" v-else>
                        Add to nickname <span class="orange">“{{ $root.config.site_name.toLowerCase() }}”</span>
                        and get bonus<br><span class="orange">10%</span> to deposit
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                active: false,
                sumDollar: 0,
                sumRuble: 0
            }
        },
        mounted() {
            this.$root.$on('showPayment', () => {
                this.active = true;
            });
        },
        methods: {
            changeDollar() {
                this.sumRuble = (this.sumDollar * this.$root.config.dollar).toFixed(2);
            },
            changeRuble() {
                this.sumDollar = (this.sumRuble / this.$root.config.dollar).toFixed(2);
            },
            paymentSum() {
                this.$root.axios.post('/payment/sum/create', {
                    sum: this.sumDollar
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        window.location.href = data.url;
                    } else {
                        if (data.message === 'min_payment') {
                            this.$root.showNotify('error', this.$t(`payment.min_payment`, {sum: data.sum}));
                        } else {
                            this.$root.showNotify('error', this.$t(`payment.${data.message}`));
                        }
                    }
                });
            },
            paymentSkins() {
                this.$root.axios.post('/payment/skins/create').then(res => {
                    const data = res.data;

                    if (data.success) {
                        window.location.href = data.url;
                    } else {
                        this.$root.showNotify('error', this.$t(`payment.${data.message}`));
                    }
                });
            }
        }
    }
</script>

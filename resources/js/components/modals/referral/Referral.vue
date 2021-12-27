<template>
    <div class="modal promo-modal" :class="[active ? 'active' : '']" role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content" style="max-width: 1000px;">
            <div class="modal-title hide-above-l">{{ $t('profile.referral_system') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
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
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                active: false,
                domain: ''
            }
        },
        mounted() {
            this.$root.$on('showReferral', () => {
                this.active = true;
            });

            this.domain = window.location.protocol + '//' + window.location.hostname;
        }
    }
</script>

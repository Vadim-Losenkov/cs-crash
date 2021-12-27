<template>
    <div class="modal text-modal " :class="[active ? 'active' : '']"  role="dialog" aria-modal="true">
        <div class="modal__overlay" @click="active = false"></div>
        <div class="modal__content">
            <div class="modal-title hide-above-l">{{ $t('chat.rules') }}</div>
            <button type="button" class="modal-close-btn" @click="active = false">
                <svg focusable="false" aria-hidden="true">
                    <use xlink:href="/svg/svg.svg#close"></use>
                </svg>
            </button>
            <div class="modal__inner">
                <div class="modal-title orange">{{ $t('chat.rules') }}:</div>
                <div class="formatted-text"><p>— {{ $t('chat.rules_1') }}/</p>
                    <p>— {{ $t('chat.rules_2') }}</p>
                    <p>— {{ $t('chat.rules_3') }}</p>
                    <p>— {{ $t('chat.rules_4') }}</p>
                    <p>
                        — {{ $t('chat.rules_5') }}
                    </p>
                    <p>— {{ $t('chat.rules_6') }}</p>
                    <br>
                    <p>
                        {{ $t('chat.rules_7') }}
                    </p></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                active: false
            }
        },
        mounted() {
            this.$root.$on('showChat', () => {
                this.active = true;
            });
        }
    }
</script>

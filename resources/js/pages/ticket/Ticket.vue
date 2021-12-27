<template>
    <div class="inner-page" v-show="loaded">
        <div class="page-header">
            <h1 class="page-title hide-above-l">
                <i v-if="ticket.status === 0" class="green"></i>
                <i v-if="ticket.status === 1" class="red"></i>
                {{ ticket.title }}
            </h1>
            <h1 class="page-title hide-below-m">
                <i v-if="ticket.status === 0" class="green"></i>
                <i v-if="ticket.status === 1" class="red"></i>
                {{ ticket.title }}
            </h1>
            <ul class="pages-nav">
                <li v-if="ticket.status == 0" style="cursor: pointer;"><a @click="closeTicket">{{ $t('support.close') }}</a></li>
                <li><router-link tag="a" :to="{name: 'support'}" class="active"><i class="green"></i>{{ $t('support.my_requests') }}</router-link></li>
                <li><a :href="$root.config.vk_group" target="_blank">
                    <div class="hide-above-l">VK</div>
                    <div class="hide-below-m">{{ $t('support.vk') }}</div>
                </a></li>
            </ul>
        </div>
        <div class="support">
            <ul class="open-ticket-list shadow">
                <li class="open-ticket-item" v-for="item in ticket.messages">
                    <div class="open-ticket-item-header">
                        <div class="open-ticket-item-name ">{{ item.username }}</div>
                        <div class="open-ticket-item-time">{{ item.date }}</div>
                    </div>
                    <div class="open-ticket-item-desc">{{ item.message }}</div>
                </li>
            </ul>
            <div class="support-form">
                <div class="page-title orange">{{ $t('support.answer') }}</div>
                <label for="tickets-reply-message" class="field-wrapper">
                    <span class="field-wrapper__title sr-only">{{ $t('support.enter_problem') }}</span>
                    <textarea v-model="message" id="tickets-reply-message" class="field-textarea" :placeholder="$t('support.enter_problem')"></textarea>
                </label>
                <button @click="sendMessage" class="btn btn--green">{{ $t('support.send_message') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loaded: false,
                ticket: {},
                message: ''
            }
        },
        mounted() {
            this.$root.isLoading = true;

            setTimeout( () => {
                this.getTicket();
            }, 100);
        },
        methods: {
            getTicket() {
                this.$root.axios.post('/tickets/getById', {
                    id: this.$route.params.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.ticket = data.ticket;
                        this.loaded = true;
                        this.$root.isLoading = false;
                        this.scrollTop();
                    } else {
                        this.$root.isLoading = false;
                        this.$router.go(-1);
                    }
                }).catch(error => {
                    this.$root.isLoading = false;
                    this.$router.go(-1);
                });
            },
            closeTicket() {
                this.$root.axios.post('/tickets/close', {
                    id: this.ticket.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.ticket.status = 1;
                        this.$root.showNotify('success', this.$t('support.ticket_closed'));
                    } else {
                        this.$root.showNotify('error', this.$t(`support.${data.message}`));
                    }
                });
            },
            sendMessage() {
                this.$root.axios.post('/tickets/sendMessage', {
                    id: this.ticket.id,
                    message: this.message
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.ticket.messages = data.messages;
                        this.message = '';
                        this.scrollTop();
                        this.$root.showNotify('success', this.$t('support.message_sended'));
                    } else {
                        this.$root.showNotify('error', this.$t(`support.${data.message}`));
                    }
                });
            },
            scrollTop() {
                setTimeout(() => {
                    $('.open-ticket-list').scrollTop(999999);
                }, 100);
            }
        }
    }
</script>

<template>
    <div class="inner-page">
        <div class="page-header"><h1 class="page-title hide-above-l">{{ $t('support.support') }}</h1>
            <h1 class="page-title hide-below-m">{{ $t('support.tech') }}</h1>
            <ul class="pages-nav">
                <li>
                    <router-link tag="a" :to="{name: 'support'}" class="active"><i class="green"></i>{{ $t('support.my_requests') }}</router-link>
                </li>
                <li>
                    <a :href="$root.config.vk_group" target="_blank">
                        <div class="hide-above-l">VK</div>
                        <div class="hide-below-m">{{ $t('support.vk') }}</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="support">
            <ul class="support-tickets shadow">
                <li v-for="ticket in tickets">
                    <router-link tag="a" :to="{name: 'ticket', params: {id: ticket.id}}" class="support-ticket" v-if="ticket.status === 0">
                        <i class="green"></i> {{ ticket.title }}
                        <div v-if="!ticket.last_admin" class="support-label support-label--gray">{{ $t('support.wait_user') }}</div>
                        <div v-else class="support-label support-label--green">{{ $t('support.wait_admin') }}</div>
                    </router-link>
                    <router-link tag="a" :to="{name: 'ticket', params: {id: ticket.id}}" class="support-ticket" v-if="ticket.status === 1">
                        <i class="red"></i> {{ ticket.title }}
                    </router-link>
                </li>
            </ul>
            <div class="support-form">
                <div class="page-title orange">
                    <span class="hide-above-l">{{ $t('support.create') }}</span>
                    <span class="hide-below-m">{{ $t('support.create_mobile') }}</span>
                </div>
                <label for="tickets-create-subject" class="field-wrapper">
                    <span class="field-wrapper__title sr-only">{{ $t('support.enter_title') }}</span>
                    <input v-model="newTicket.title" id="tickets-create-subject" type="text" class="field-input" :placeholder="$t('support.enter_title')" value="">
                </label>
                <label for="tickets-create-message" class="field-wrapper">
                    <span class="field-wrapper__title sr-only">{{ $t('support.enter_problem') }} </span>
                    <textarea v-model="newTicket.message" id="tickets-create-message" class="field-textarea" :placeholder="$t('support.enter_problem')"></textarea>
                </label>
                <button type="button" @click="createTicket" class="btn btn--green">{{ $t('support.create') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tickets: {},
                newTicket: {
                    title: '',
                    message: ''
                }
            }
        },
        mounted() {
            setTimeout(() => {
                this.getTickets();
            }, 100);
        },
        methods: {
            createTicket() {
                if (!this.$cookie.get('token')) {
                    return this.$root.showNotify('error', this.$t('support.auth'));
                }

                this.$root.axios.post('/tickets/create', {
                    ticket: this.newTicket
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.newTicket = {
                            title: '',
                            message: ''
                        };
                        this.$root.showNotify('success', this.$t('support.created'));
                        this.getTickets();
                    } else {
                        return this.$root.showNotify('error', this.$t(`support.${data.message}`));
                    }
                });
            },
            getTickets() {
                if (!this.$cookie.get('token')) {
                    return;
                }

                this.$root.axios.post('/tickets/get').then(res => {
                     this.tickets = res.data;
                });
            }
        }
    }
</script>

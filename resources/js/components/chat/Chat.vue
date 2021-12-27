<template>
    <div class="chat">
        <button type="button" class="chat-trigger" @click="showOrHide">
            <svg focusable="false" aria-hidden="true" class="chat-trigger__arrow">
                <use xlink:href="/svg/svg.svg#arrow-down"></use>
            </svg>
        </button>
        <div class="chat__inner" :class="[showChat ? 'active' : '']">
            <div class="chat-header">
                <div class="chat-header__title">{{ $t('chat.online_chat') }}</div>
                <button type="button" class="btn-base link" @click="$root.$emit('showChat')">{{ $t('chat.rules') }}</button>
                <button type="button" class="chat-close" @click="showOrHide">
                    <svg focusable="false" aria-hidden="true">
                        <use xlink:href="/svg/svg.svg#arrow-down"></use>
                    </svg>
                </button>
            </div>
            <div class="chat-list-wrapper">
                <ul class="chat-list shadow">
                    <div>
                        <li class="chat-item" v-for="data in messages" :key="data.id">
                            <router-link tag="a" :to="{name: 'user', params: {steamid: data.user.steamid}}" class="chat-item-photo"><img
                            :src="data.user.avatar"
                            alt=""></router-link>
                            <div class="chat-item-header">
                                <div class="chat-item-name" @click="focusInput(data.user.username)">
                                    <svg v-if="data.user.is_admin" aria-hidden="true" class="icon-crown">
                                        <use xlink:href="/svg/svg.svg#crown"></use>
                                    </svg>
                                    <svg v-if="data.user.is_moderator" aria-hidden="true" class="icon-sword">
                                        <use xlink:href="/svg/svg.svg#sword"></use>
                                    </svg>
                                    {{ data.user.username }}
                                </div>
                                <div class="chat-item-time">{{ data.time }}</div>
                            </div>
                            <div class="chat-item-desc">{{ data.message }}</div>
                            <div class="admin-ban-controll" v-if="$root.user !== null && ($root.user.is_admin || $root.user.is_moderator)">
                                <button v-if="!data.user.is_ban_chat" class="admin-ban-controll__btn" @click="banUser(data.user.steamid)">{{ $t('chat.ban') }}</button>
                                <button v-else class="admin-ban-controll__btn" @click="unBanUser(data.user.steamid)">{{ $t('chat.unban') }}</button>
                                <button class="admin-ban-controll__btn" @click="clearMessage(data.id)">{{ $t('chat.delete') }}</button>
                            </div>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="chat-form">
                <div class="chat-form__inner">
                    <label for="chat-input" class="field-wrapper">
                        <span class="sr-only">{{ $t('chat.fill') }}</span>
                        <input v-model="message" v-on:keyup="keyUpEnter" type="text" id="chat-input" class="field-textarea" :placeholder="$t('chat.fill')" autocomplete="off" value="">
                    </label>
                    <div class="chat-form-stats">{{ 100 - message.length}}</div>
                    <button type="button" class="btn btn--blue" :disabled="$root.user === null || message.length === 0" @click="sendMessage">
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#send"></use>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showChat: true,
                message: '',
                messages: []
            }
        },
        mounted() {
            this.$root.$on('hideMobileChat', () => {
                this.showChat = false;
                $('body').removeClass('chat-active');
            });

            if (this.$root.isMobile) {
                this.$root.isLoading = true;
            }

            this.getMessages();
        },
        methods: {
            showOrHide() {
                if (this.showChat) {
                    this.showChat = false;
                    $('body').removeClass('chat-active');
                } else {
                    this.showChat = true;
                    $('body').addClass('chat-active');
                }
            },
            scrollChat() {
                setTimeout(() => {
                    $('.chat-list').scrollTop(9999999);
                }, 100);
            },
            sendMessage() {
                if (!this.$cookie.get('token')) {
                    return this.$root.showNotify('error', this.$t('chat.auth'));
                }

                this.$root.axios.post('/chat/sendMessage', {
                    message: this.message
                })
                    .then(res => {
                        const data = res.data;

                        if (data.success) {
                            this.message = '';
                        } else {
                            this.$root.showNotify('error', this.$t(`chat.${data.message}`));
                        }
                    });
            },
            getMessages() {
                this.$root.axios.post('/chat/getMessages')
                    .then(res => {
                        this.messages = res.data;
                        this.scrollChat();

                        if (this.$root.isMobile) {
                            this.$root.isLoading = false;
                        }
                    });
            },
            banUser(steamId) {
                this.axios.post('/chat/ban', {
                    steamId: steamId
                })
                    .then(res => {
                        const data = res.data;

                        this.$root.showNotify(data.type, this.$t(`chat.${data.message}`));
                    })
            },
            unBanUser(steamId) {
                this.axios.post('/chat/unBan', {
                    steamId: steamId
                })
                    .then(res => {
                        const data = res.data;

                        this.$root.showNotify(data.type, this.$t(`chat.${data.message}`));
                    })
            },
            clearMessage(id) {
                this.axios.post('/chat/delete', {
                    id: id
                })
                    .then(res => {
                        const data = res.data;

                        this.$root.showNotify(data.type, this.$t(`chat.${data.message}`));
                    })
            },
            focusInput(name) {
                this.message = `${name}, `;
                $('#chat-input').focus();
            },
            keyUpEnter: function (e) {
                if (e.keyCode === 13) {
                    this.sendMessage();
                }
            }
        },
        watch: {
            'message': function (newValue, oldValue) {
                if (newValue.length > 100) {
                    this.message = oldValue;
                }
            }
        },
        sockets: {
            newMessage: function (message) {
                this.messages.push(message);

                this.scrollChat();
            },
            loadChat: function (data) {
                this.messages = data;

                this.scrollChat();
            }
        }
    }
</script>

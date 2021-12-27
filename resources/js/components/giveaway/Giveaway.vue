<template>
    <div class="raffle " :class="[isActive ? 'active' : '']" v-if="!$root.config.hide_giveaway">
        <button type="button" class="btn-base raffle-header" @click="setActive">
            <svg aria-hidden="true" class="raffle-header__icon">
                <use xlink:href="svg/svg.svg#award"></use>
            </svg>
            <span class="raffle-header__title">Бесплатный розыгрыш</span>
            <span class="raffle-header__timer">
                        <div class="odometer-block">
                            <span>
                                <span class="hour">
                                    <span class="0">{{ timer.hour_1 }}</span>
                                    <span class="1">{{ timer.hour_2 }}</span>
                                </span>
                                :
                            </span>
                            <span>
                                <span class="min">
                                    <span class="0">{{ timer.min_1 }}</span>
                                    <span class="1">{{ timer.min_2 }}</span>
                                </span>
                                :
                            </span>
                            <span>
                                <span class="sec">
                                    <span class="0">{{ timer.sec_1 }}</span>
                                    <span class="1">{{ timer.sec_2 }}</span>
                                </span>
                            </span>
                        </div>
                    </span>
            <div class="raffle-header__toggler">
                <svg aria-hidden="true">
                    <use xlink:href="svg/svg.svg#arrow-down"></use>
                </svg>
            </div>
        </button>
        <div class="raffle-body">
            <div class="raffle-current" v-if="giveaway">
                <div class="raffle-current__photo">
                    <img :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + giveaway.item.image + '/128fx128f/image.png'" alt="">
                    <div :class="'drop-animation drop-animation--' + giveaway.item.rarity.toLowerCase()"></div>
                </div>
                <div class="raffle-current__name">{{ giveaway.item.market_hash_name.split('|')[0] }}</div>
                <div class="raffle-current__desc">{{ giveaway.item.market_hash_name.split('|')[1] }}</div>
                <button type="button" class="btn btn--orange" v-if="!myActive" @click="setMyActive">Участвовать</button>
            </div>
            <div style="margin-bottom: 3rem;" v-if="giveaway">
                <div class="raffle-place"><span>Всего участников</span> <span>{{ giveaway.members }}</span></div>
            </div>
            <div class="raffle-last-winners">
                <div class="raffle-last-winners__title">Последние победители</div>
                <ul class="shadow">
                    <li v-for="item in history">
                        <router-link class="raffle-last-winner" tag="a"
                           :to="{name: 'user', params: {steamid: item.user.steamid}}"
                           style="color: rgb(136, 71, 255);">
                            <div class="raffle-last-winner__photo"><img
                                :src="item.user.avatar"
                                alt=""></div>
                            <div class="raffle-last-winner__info">
                                <div class="raffle-last-winner__name">{{ item.user.username }}</div>
                                <div class="raffle-last-winner__name">Место: #{{ item.place }}</div>
                                <div class="raffle-last-winner__time">{{ item.created_at }}</div>
                            </div>
                            <div class="raffle-last-winner__drop"><img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.item.image + '/128fx128f/image.png'" alt=""></div>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isActive: false,
                giveaway: null,
                history: {},
                myActive: false,
                timer: {
                    hour_1: 0,
                    hour_2: 0,
                    min_1: 0,
                    min_2: 0,
                    sec_1: 0,
                    sec_2: 0
                },
                timerInterval: null
            }
        },
        mounted() {
            this.getActiveGiveaway();
        },
        methods: {
            setActive() {
                if (this.isActive) {
                    this.isActive = false;
                } else {
                    this.isActive = true;
                }
            },
            getActiveGiveaway() {
                this.$root.axios.post('/giveaways/getActive').then(res => {
                    const data = res.data;

                    this.giveaway = data.activeGiveaway;
                    this.history = data.historyGiveaways;

                    if (this.giveaway) {
                        this.startTimer();
                    }
                });
            },
            setMyActive() {
                this.$root.axios.post('/giveaways/setMyActive').then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.giveaway.members++;
                        this.$root.showNotify('success', this.$t(`raffle.${data.message}`));
                    } else {
                        this.$root.showNotify('error', this.$t(`raffle.${data.message}`));
                    }

                }).catch(err => {
                    this.$root.showNotify('error', this.$t('chat.auth'));
                });
            },
            startTimer() {
                this.timerInterval = setInterval(() => {
                    let timestamp = parseInt(this.giveaway.end_time) * 1000;
                    let target = new Date();
                    let now = new Date();

                    target.setTime(timestamp);
                    let totalMilliSeconds = target.getTime() - now.getTime();

                    if (totalMilliSeconds < 0) return;

                    let totalSeconds = parseInt(totalMilliSeconds / 1000);
                    let seconds = totalSeconds % 60;

                    let totalMinutes = parseInt(totalSeconds / 60);
                    let minutes = totalMinutes % 60;

                    let totalHours = parseInt(totalMinutes / 60);
                    let hours = totalHours % 24;

                    if (seconds < 10) {
                        seconds = `0${seconds}`;
                    }

                    if (minutes < 10) {
                        minutes = `0${minutes}`;
                    }

                    if (hours < 10) {
                        hours = `0${hours}`;
                    }

                    seconds = seconds.toString().split('');
                    minutes = minutes.toString().split('');
                    hours = hours.toString().split('');

                    this.timer = {
                        hour_1: hours[0],
                        hour_2: hours[1],
                        min_1: minutes[0],
                        min_2: minutes[1],
                        sec_1: seconds[0],
                        sec_2: seconds[1]
                    };
                }, 1000);
            }
        },
        sockets: {
            setRaffle(data) {
                this.giveaway = data.activeGiveaway;
                this.history = data.historyGiveaways;

                clearInterval(this.timerInterval);

                if (this.giveaway) {
                    this.startTimer();
                }
            }
        }
    }
</script>

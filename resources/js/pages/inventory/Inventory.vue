<template>
    <div style="height: 100%;">
        <div class="cur-u-drops">
            <div class="cur-u-drops-header-2">
                <div class="cur-u-drops-selected-2">
                    <div class="cur-u-drops-selected-2__total"><span>{{ $t('index.balance') }}</span>
                        <div class="price" v-if="$root.user === null">0.00 $</div>
                        <div class="price" v-else>{{ $root.user.balance.toFixed(2) }} $</div>
                    </div>
                    <div class="cur-u-drops-selected-2__total"><span>{{ $t('index.selected') }}</span>
                        <div class="price">{{ selectedMy.price }} $</div>
                    </div>
                </div>
                <div class="cur-u-drops-selected-2">
                    <select v-model="selectedGame">
                        <option value="730">CS:GO</option>
                        <option value="570">Dota 2</option>
                    </select>
                </div>
                <label class="checkbox-control"><input type="checkbox" v-model="selectedAllMyItems"
                                                       v-on:change="selectAllMyItems"
                                                       :disabled="myItems.length === 0 || $root.user === null">
                    <div class="checkbox-control__content"><i></i>{{ $t('index.selected_all') }}</div>
                </label></div>
            <div class="cur-u-drops-list-wrapper shadow has-scroll has-shadow">
                <div class="inventar-empty" v-if="$root.user === null">
                    <svg aria-hidden="true">
                        <use xlink:href="/svg/svg.svg#padlock"></use>
                    </svg>
                    <div class="inventar-empty__title">{{ $t('index.login') }}</div>
                    <div class="inventar-empty__subtitle">{{ $t('index.inventory') }}</div>
                    <div class="inventar-empty__desc">{{ $t('index.unvailable') }}</div>
                    <button type="button" class="btn btn--green steam-login" @click="$root.$emit('openAuth', 1)">
                        <svg aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#steam"></use>
                        </svg>
                        <div class="hide-above-l">{{ $t('index.sign') }}</div>
                        <div class="hide-below-m">{{ $t('index.sign_steam') }}</div>
                    </button>
                </div>
                <div class="cur-u-drops-list">
                    <button v-for="item in myItems"
                            :class="[typeof selectedMy.items[item.myId] !== 'undefined' ? 'checked' : '']"
                            @click="addMyItem(item)"
                            type="button" class="btn-base drop-preview"
                            :style="'color:' + $root.getRarityColor(item.rarity) + ';'">
                        <div class="drop-preview__photo"><img
                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                            alt=""></div>
                        <div class="drop-preview__title">{{ item.market_hash_name.split('|')[0] }}</div>
                        <div class="drop-preview__subtitle">{{ item.market_hash_name.split('|')[1] }}
                        </div>
                        <div class="drop-preview__desc">{{ item.exterior }}</div>
                        <div class="drop-preview__price">$ {{ item.price.toFixed(2) }}</div>
                    </button>
                </div>
            </div>
            <div class="cur-u-drops-btns" v-if="$root.user !== null">
                <button type="button" class="btn btn--has-icon btn--green" @click="openOrCloseWithdrawModal">
                    {{ $t('index.change') }}
                    <svg aria-hidden="true" style="width: 2.4em; height: 2.4em;">
                        <use xlink:href="/svg/svg.svg#refresh"></use>
                    </svg>
                </button>
                <button type="button" class="btn btn--has-icon btn--purple"
                        :disabled="$root.user === null || selectedMy.price === 0 || withdrawBtn"
                        @click="sendToWithdrawItems">{{ $t('index.take') }}
                    <svg aria-hidden="true" style="width: 1.4em; height: 1.9em;">
                        <use xlink:href="/svg/svg.svg#double-arrow-down"></use>
                    </svg>
                </button>
            </div>
        </div>
        <div class="modal withdraw-modal" :class="[activeWithdrawModal ? 'active' : '']" role="dialog"
             aria-modal="true">
            <div class="modal__overlay"></div>
            <div class="modal__content">
                <div class="modal__inner">
                    <div class="withdraw-header">
                        <div class="withdraw-header__title">{{ $t('index.select_skins') }}</div>
                        <button type="button" class="modal-close-btn" @click="activeWithdrawModal = false">
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#close"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="withdraw-form">
                        <div class="withdraw-form-top">
                            <div class="cur-u-drops-selected-2">
                                <div class="cur-u-drops-selected-2__total"><span>{{ $t('index.give') }}</span>
                                    <div class="price">{{ selectedMy.price.toFixed(2) }} $</div>
                                </div>
                                <div class="cur-u-drops-selected-2__total"><span>{{ $t('index.get') }}</span>
                                    <div class="price">{{ selectedWithdraw.price.toFixed(2) }} $</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn--green" :disabled="!withdraw.activeBtn"
                                    @click="buyWithdraw">
                                <span class="hide-above-l">{{ $t('index.confirm') }}</span>
                                <span class="hide-below-m">{{ $t('index.confirm') }}</span>
                                <svg aria-hidden="true">
                                    <use xlink:href="/svg/svg.svg#refresh"></use>
                                </svg>
                            </button>
                        </div>
                        <div class="withdraw-form-bottom">
                            <div class="text-select-wrapper">
                                <label for="exchange-filter-name-field" class="field-wrapper">
                                    <span class="sr-only">{{ $t('index.name') }}</span>
                                    <input v-model="withdraw.market_hash_name" id="exchange-filter-name-field"
                                           type="text" class="field-input" :placeholder="$t('index.name')">
                                </label>
                                <label for="exchange-filter-minPrice-field" class="field-wrapper">
                                    <span class="sr-only">{{ $t('index.min_price') }}</span>
                                    <input v-model="withdraw.minPrice" id="exchange-filter-minPrice-field"
                                           type="number" class="field-input" :placeholder="$t('index.min_price')">
                                </label>
                                <label for="exchange-filter-maxPrice-field" class="field-wrapper">
                                    <span class="sr-only">{{ $t('index.max_price') }}</span>
                                    <input v-model="withdraw.maxPrice" id="exchange-filter-maxPrice-field"
                                           type="number" class="field-input" :placeholder="$t('index.max_price')">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="withdraw-list-wrapper" v-if="$root.user !== null">
                        <div class="withdraw-list shadow has-scroll has-shadow">
                            <div class="withdraw-list__inner">
                                <button v-for="item in withdraw.items"
                                        :class="[typeof selectedWithdraw.items[item.id] !== 'undefined' ? 'checked' : '']"
                                        @click="addWithdrawItem(item)"
                                        :disabled="((item.price + selectedWithdraw.price) > ($root.user.balance + selectedMy.price)) && typeof selectedWithdraw.items[item.id] === 'undefined'"
                                        type="button" class="btn-base drop-preview"
                                        :style="'color:' + $root.getRarityColor(item.rarity) + ';'">
                                    <div class="drop-preview__photo"><img
                                        :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                        alt=""></div>
                                    <div class="drop-preview__title">{{ item.market_hash_name.split('|')[0] }}</div>
                                    <div class="drop-preview__subtitle">{{ item.market_hash_name.split('|')[1] }}
                                    </div>
                                    <div class="drop-preview__desc">{{ item.exterior }}</div>
                                    <div class="drop-preview__price">$ {{ item.price.toFixed(2) }}</div>
                                </button>
                            </div>
                        </div>
                        <div class="withdraw-list-more">
                            <button type="button" class="big-flat-btn" @click="loadMoreWithdrawsItems">{{
                                $t('index.more') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                withdrawBtn: false,
                activeWithdrawModal: false,
                selectedMy: {
                    items: {},
                    price: 0.00
                },
                selectedGame: 730,
                selectedAllMyItems: false,
                myItems: {},
                selectedWithdraw: {
                    items: {},
                    price: 0.00
                },
                withdraw: {
                    minPrice: 0,
                    maxPrice: 99999,
                    market_hash_name: '',
                    page: 1,
                    items: [],
                    activeBtn: true
                }
            }
        },
        mounted() {
            if (this.$cookie.get('token')) {
                setTimeout(() => {
                    this.loadMyItems();
                    this.loadWithdrawsItems();
                }, 100);
            }
        },
        beforeDestroy() {
            this.$root.mobileSelectedItems = this.selectedMy;
        },
        methods: {
            selectAllMyItems() {
                if (this.selectedAllMyItems) {
                    for (let id in this.myItems) {
                        const item = this.myItems[id];

                        if (typeof this.selectedMy.items[item.myId] === "undefined") {
                            this.selectedMy.items[item.myId] = item;
                            this.selectedMy.price = parseFloat((this.selectedMy.price + item.price).toFixed(2));
                        }
                    }
                } else {
                    for (let id in this.myItems) {
                        const item = this.myItems[id];

                        if (typeof this.selectedMy.items[item.myId] !== "undefined") {
                            delete this.selectedMy.items[item.myId];
                            this.selectedMy.price = parseFloat((this.selectedMy.price - item.price).toFixed(2));
                        }
                    }
                }
            },
            addMyItem(item) {
                if (typeof this.selectedMy.items[item.myId] !== "undefined") {
                    delete this.selectedMy.items[item.myId];
                    this.selectedMy.price = parseFloat((this.selectedMy.price - item.price).toFixed(2));
                } else {
                    this.selectedMy.items[item.myId] = item;
                    this.selectedMy.price = parseFloat((this.selectedMy.price + item.price).toFixed(2));
                }
            },
            openOrCloseWithdrawModal() {
                if (this.activeWithdrawModal) {
                    this.activeWithdrawModal = false;
                } else {
                    this.activeWithdrawModal = true;
                }
            },
            sendToWithdrawItems() {
                this.withdrawBtn = true;

                if (Object.keys(this.selectedMy.items).length > 1) {
                    this.withdrawBtn = false;
                    return this.$root.showNotify('error', this.$t('index.withdraw_min'));
                }

                this.$root.showNotify('info', this.$t('index.withdraw_send'));

                this.$root.axios.post('/withdraws/send', {
                    items: this.selectedMy.items
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.$root.showNotify('success', this.$t(`index.${data.message}`, {items: data.items}));
                    } else {
                        if (data.message === 'withdraw_not_found') {
                            this.$root.showNotify('error', this.$t(`index.${data.message}`, {item: data.item}));
                        } else if (data.message === 'all_items_not_withdraw') {
                            this.$root.showNotify('error', this.$t(`index.${data.message}`, {items: data.items}));
                        } else if (data.message === 'one_items_withdraw') {
                            this.$root.showNotify('error', this.$t(`index.${data.message}`, {items: data.items}));
                        } else if (data.message === 'max_withdraw') {
                            this.$root.showNotify('error', this.$t(`index.${data.message}`, {sum: data.sum}));
                        } else {
                            this.$root.showNotify('error', this.$t(`index.${data.message}`));
                        }
                    }

                    this.selectedMy = {
                        items: {},
                        price: 0
                    };

                    this.$root.mobileSelectedItems = {
                        items: {},
                        price: 0.00
                    };

                    this.loadMyItems();
                    this.withdrawBtn = false;
                });
            },
            loadMyItems() {
                this.$root.axios.post('/user/getInventory', {
                    appId: this.selectedGame
                })
                    .then(res => {
                        this.myItems = res.data;
                    });
            },
            buyWithdraw() {
                this.withdraw.activeBtn = false;

                this.$root.axios.post('/user/buy', {
                    ids: this.selectedWithdraw.items,
                    myIds: this.selectedMy.items
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.$root.user.balance = data.newBalance;
                        this.selectedMy = {
                            items: {},
                            price: 0.00
                        };
                        this.$root.mobileSelectedItems = {
                            items: {},
                            price: 0.00
                        };
                        this.selectedWithdraw = {
                            items: {},
                            price: 0.00
                        };
                        this.selectedAllMyItems = false;
                        this.loadMyItems();

                        this.$root.showNotify('success', this.$t('index.buy'));
                    } else {
                        this.$root.showNotify('error', this.$t(`index.${data.message}`));
                    }

                    this.withdraw.activeBtn = true;
                });
            },
            loadMoreWithdrawsItems() {
                this.withdraw.page++;
                this.loadWithdrawsItems();
            },
            loadWithdrawsItems() {
                this.$root.axios.post('/all-items/getList', {
                    minPrice: this.withdraw.minPrice,
                    maxPrice: this.withdraw.maxPrice,
                    market_hash_name: this.withdraw.market_hash_name,
                    page: this.withdraw.page,
                    appId: this.selectedGame
                }).then(res => {
                    const data = res.data;

                    const array = this.withdraw.items;
                    Array.prototype.push.apply(array, data.data);

                    this.withdraw.items = array;
                    this.$forceUpdate();
                })
            },
            addWithdrawItem(item) {
                if (typeof this.selectedWithdraw.items[item.id] !== "undefined") {
                    delete this.selectedWithdraw.items[item.id];
                    this.selectedWithdraw.price = parseFloat((this.selectedWithdraw.price - item.price).toFixed(2));
                } else {
                    this.selectedWithdraw.items[item.id] = item;
                    this.selectedWithdraw.price = parseFloat((this.selectedWithdraw.price + item.price).toFixed(2));
                }
            }
        },
        watch: {
            'withdraw.minPrice': function (newVal, oldVal) {
                this.withdraw.page = 1;
                this.withdraw.items = [];
                this.selectedWithdraw = {
                    items: {},
                    price: 0.00
                };
                this.loadWithdrawsItems();
            },
            'withdraw.maxPrice': function (newVal, oldVal) {
                this.withdraw.page = 1;
                this.withdraw.items = [];
                this.selectedWithdraw = {
                    items: {},
                    price: 0.00
                };
                this.loadWithdrawsItems();
            },
            'withdraw.market_hash_name': function (newVal, oldVal) {
                this.withdraw.page = 1;
                this.withdraw.items = [];
                this.selectedWithdraw = {
                    items: {},
                    price: 0.00
                };
                this.loadWithdrawsItems();
            },
            'selectedGame': function (newVal, oldVal) {
                this.withdraw.page = 1;
                this.withdraw.items = [];
                this.selectedWithdraw = {
                    items: {},
                    price: 0.00
                };
                this.selectedMy = {
                    items: {},
                    price: 0.00
                };
                this.myItems = {};
                this.loadWithdrawsItems();
                this.loadMyItems();
            }
        }
    }
</script>

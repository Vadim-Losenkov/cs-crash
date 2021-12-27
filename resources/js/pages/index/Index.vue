<template>
    <div style="height: 100%;">
        <div class="cur-u-panel">
            <div v-if="!$root.isMobile" class="cur-u-header-wrapper">
                <div class="graph-wrapper">
                    <div class="graph">
                        <div class="crash__bomb bomb bomb_crash">
                            <div class="bomb__wrapper">
                                <div class="bomb__board board "
                                     :class="[[game.type === 'timer' ? 'board_timer' : ''], [game.type === 'multiplier' ? 'board_multiplier' : ''], [game.type === 'explosion' ? 'board_explosion' : '']]">
                                    <div class="board__timer">
                                        <div class="board__subtitle">
                                            CRASH
                                        </div>
                                        <div class="board__count">
                                            <div class="board__number second">
                                                    <span>
                                                        {{ game.numbers.one.one }}
                                                    </span>
                                                <span>
                                                        {{ game.numbers.one.two }}
                                                    </span>
                                            </div>
                                            <div class="board__number dot">.</div>
                                            <div class="board__number msecond">
                                                    <span>
                                                        {{ game.numbers.two.one }}
                                                    </span>
                                                <span>
                                                        {{ game.numbers.two.two }}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="board__text" v-if="game.type === 'timer'">
                                            sec
                                        </div>
                                        <div class="board__text" v-else>
                                            x
                                        </div>
                                    </div>
                                    <div class="board__background"></div>
                                </div>
                                <div class="bomb__background"></div>
                                <div class="bomb__bulbs">
                                    <div class="bomb__bulb bomb__bulb_red"></div>
                                    <div class="bomb__bulb bomb__bulb_green"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!$root.isMobile" class="cur-u-drops">
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
                    </label>
                </div>
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
            <Giveaway />
        </div>
        <div class="game" v-if="!$root.isMobile">
            <div class="game-main">
                <div class="game-main-left">
                    <ul class="round-inform-bets" v-if="game.bet.bank > 0 && game.lastBet.bank === 0">
                        <li class="round-inform-bets-item" v-for="item in game.bet.items">
                            <div :class="'drop-animation drop-animation--' + item.rarity"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                    <ul class="round-inform-bets" v-else-if="game.lastBet.bank > 0">
                        <li class="round-inform-bets-item">
                            <div :class="'drop-animation drop-animation--' + game.lastBet.item.rarity"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + game.lastBet.item.image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                    <ul class="round-inform-bets" v-else>
                        <li class="round-inform-bets-item" v-if="selectedMy.price === 0">
                            <div class="drop-animation drop-animation--gray-load"></div>
                        </li>
                        <li class="round-inform-bets-item" v-else-if="selectedMy.price > 0" v-for="item in Object.keys(selectedMy.items).sort(( (e, t) => {
                            return selectedMy.items[t].price - selectedMy.items[e].price
                        })).slice(0, 3)">
                            <div
                                :class="'drop-animation drop-animation--' + $root.getRarityType(selectedMy.items[item].rarity)"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + selectedMy.items[item].image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                </div>
                <div class="game-info">
                    <div class="game-info-bet" v-if="game.lastBet.bank === 0">
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.bet.bank.toFixed(2) }}</div>
                        </div>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ parseFloat(game.bet.bank * game.multiplier).toFixed(2)
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="game-info-bet" v-else>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.lastBet.bank.toFixed(2) }}</div>
                        </div>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.lastBet.win.toFixed(2) }}</div>
                        </div>
                    </div>
                    <div class="game-info-form">
                        <label for="auto-upgrade-input" class="field-wrapper">
                            <span>{{ $t('index.auto_withdraw') }}</span>
                            <input type="text" id="auto-upgrade-input" class="field-input" autocomplete="off"
                                   v-model="game.bet.autoWithdraw">
                        </label>
                        <button
                            v-if="game.bet.bank > 0 && game.type === 'multiplier' && !takeBtn && game.lastBet.bank === 0"
                            @click="take" type="button" class="btn-base make-bet">{{ $t('index.take') }} <b>{{
                            parseFloat(game.multiplier).toFixed(2) }} X</b></button>
                        <button
                            v-else
                            :disabled="(selectedMy.price === 0 || $root.user === null) || !betBtn || game.type !== 'timer'"
                            @click="bet" type="button" class="btn-base make-bet">{{ $t('index.begin') }} <b>{{
                            selectedMy.price.toFixed(2) }} $</b></button>
                    </div>
                    <div class="koeff-labels">
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.10 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.10">X 1.1
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.20 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.20">X 1.2
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.50 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.50">X 1.5
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 2.00 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 2.00">X 2.0
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 3.00 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 3.00">X 3.0
                        </button>
                    </div>
                </div>
            </div>
            <div class="game-stats-header-wrapper">
                <div class="game-stats-header">
                    <div class="game-stats-header__title">
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#icon-bar-chart"></use>
                        </svg>
                        <span>{{ $t('index.stats') }}</span></div>
                    <ul class="game-stats-header__list">
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#money-bag"></use>
                            </svg>
                            <span>{{ $t('index.bank') }}</span><b>{{ game.stats.bank.toFixed(2) }}</b></li>
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#avatar"></use>
                            </svg>
                            <span>{{ $t('index.members') }}</span><b>{{ game.stats.members }}</b></li>
                        <li>
                            <svg focusable="false" aria-hidden="true">
                                <use xlink:href="/svg/svg.svg#machine-gun"></use>
                            </svg>
                            <span>{{ $t('index.skins') }}</span><b>{{ game.stats.skins }}</b>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="graph-labels">
                <router-link v-for="game in history" :key="game.id" class="graph-label" tag="a"
                             :to="{name: 'game', params: {id: game.id}}" :style="{'color': game.color}">
                    <span>{{ game.multiplier.toFixed(2) }}x</span>
                </router-link>
            </div>
            <div class="game-stats">
                <div class="game-stats-list-wrapper">
                    <ul class="game-stats-list shadow has-scroll has-shadow">
                        <div>
                            <li v-for="bet in Object.keys(this.game.bets).sort(( (e, t) => {
                                    if (this.$root.user !== null) {
                                        if (this.game.bets[e].user.steamid === this.$root.user.steamid) {
                                            return -1;
                                        } else {
                                            return 0;
                                        }
                                    } else {
                                        return 0;
                                    }
                                }))" class="round-item"
                                :class="[[game.bets[bet].is_win === 0 ? 'animate-item-enter-done' : ''],
                                 [game.bets[bet].is_win === 1 ? 'round-item--green' : ''],
                                 [game.bets[bet].is_win === 2 ? 'round-item--red' : '']]">
                                <router-link tag="a"
                                             :to="{name: 'user', params: {steamid: game.bets[bet].user.steamid}}"
                                             class="round-item-photo">
                                    <img
                                        :src="game.bets[bet].user.avatar"
                                        :alt="game.bets[bet].user.username">
                                </router-link>
                                <ul class="round-item-cases">
                                    <li class="round-item-tooltip" v-for="item in game.bets[bet].items">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ item.market_hash_name }}</span>
                                        </div>
                                    </li>
                                    <div v-if="game.bets[bet].itemsMore > 0" class="round-item-cases-more">
                                        +{{game.bets[bet].itemsMore }}
                                    </div>
                                </ul>
                                <div class="round-item-price price">{{ game.bets[bet].bank.toFixed(2) }} $</div>
                                <div class="status-label" v-if="game.bets[bet].is_win === 0">{{ $t('index.in_game') }}</div>
                                <div class="status-label status-label--red" v-if="game.bets[bet].is_win === 2">{{
                                    parseFloat(game.bets[bet].multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="status-label status-label--green" v-if="game.bets[bet].is_win === 1">{{
                                    parseFloat(game.bets[bet].multiplier).toFixed(2)
                                    }}x
                                </div>
                                <div class="round-item-selected" v-if="game.bets[bet].is_win === 1">
                                    <div class="round-item-selected-price price">{{ game.bets[bet].win.toFixed(2) }} $
                                    </div>
                                    <div class="round-item-selected-photo round-item-tooltip round-item-tooltip__right">
                                        <img
                                            :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + game.bets[bet].winItem.image + '/128fx128f/image.png'"
                                            alt="">
                                        <div class="drop-preview-noty">
                                            <span>{{ game.bets[bet].winItem.market_hash_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="game" v-else>
            <div class="graph-wrapper" style="height: 200px; z-index: 0;">
                <div class="graph">
                    <div class="crash__bomb bomb bomb_crash">
                        <div class="bomb__wrapper">
                            <div class="bomb__board board "
                                 :class="[[game.type === 'timer' ? 'board_timer' : ''], [game.type === 'multiplier' ? 'board_multiplier' : ''], [game.type === 'explosion' ? 'board_explosion' : '']]">
                                <div class="board__timer">
                                    <div class="board__subtitle">
                                        CRASH
                                    </div>
                                    <div class="board__count">
                                        <div class="board__number second">
                                                    <span>
                                                        {{ game.numbers.one.one }}
                                                    </span>
                                            <span>
                                                        {{ game.numbers.one.two }}
                                                    </span>
                                        </div>
                                        <div class="board__number dot">.</div>
                                        <div class="board__number msecond">
                                                    <span>
                                                        {{ game.numbers.two.one }}
                                                    </span>
                                            <span>
                                                        {{ game.numbers.two.two }}
                                                    </span>
                                        </div>
                                    </div>
                                    <div class="board__text" v-if="game.type === 'timer'">
                                        sec
                                    </div>
                                    <div class="board__text" v-else>
                                        x
                                    </div>
                                </div>
                                <div class="board__background"></div>
                            </div>
                            <div class="bomb__background"></div>
                            <div class="bomb__bulbs">
                                <div class="bomb__bulb bomb__bulb_red"></div>
                                <div class="bomb__bulb bomb__bulb_green"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="graph-labels">
                <router-link v-for="game in history" :key="game.id" class="graph-label" tag="a"
                             :to="{name: 'game', params: {id: game.id}}" :style="{'color': game.color}">
                    <span>{{ game.multiplier.toFixed(2) }}x</span>
                </router-link>
            </div>
            <div class="game-main">
                <div class="game-main-left">
                    <ul class="round-inform-bets" v-if="game.bet.bank > 0 && game.lastBet.bank === 0">
                        <li class="round-inform-bets-item" v-for="item in game.bet.items">
                            <div :class="'drop-animation drop-animation--' + item.rarity"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + item.image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                    <ul class="round-inform-bets" v-else-if="game.lastBet.bank > 0">
                        <li class="round-inform-bets-item">
                            <div :class="'drop-animation drop-animation--' + game.lastBet.item.rarity"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + game.lastBet.item.image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                    <ul class="round-inform-bets" v-else>
                        <li class="round-inform-bets-item" v-if="selectedMy.price === 0">
                            <div class="drop-animation drop-animation--gray-load"></div>
                        </li>
                        <li class="round-inform-bets-item" v-else-if="selectedMy.price > 0" v-for="item in Object.keys(selectedMy.items).sort(( (e, t) => {
                            return selectedMy.items[t].price - selectedMy.items[e].price
                        })).slice(0, 3)">
                            <div
                                :class="'drop-animation drop-animation--' + $root.getRarityType(selectedMy.items[item].rarity)"></div>
                            <img
                                :src="'https://steamcommunity-a.akamaihd.net/economy/image/' + selectedMy.items[item].image + '/128fx128f/image.png'"
                                alt="">
                        </li>
                    </ul>
                </div>
                <div class="game-info">
                    <div class="game-info-bet" v-if="game.lastBet.bank === 0">
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.bet.bank.toFixed(2) }}</div>
                        </div>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ parseFloat(game.bet.bank * game.multiplier).toFixed(2)
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="game-info-bet" v-else>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.lastBet.bank.toFixed(2) }}</div>
                        </div>
                        <div class="game-info-bet__item">
                            <div class="game-info-bet__count">{{ game.lastBet.win.toFixed(2) }}</div>
                        </div>
                    </div>
                    <div class="game-info-form">
                        <label for="auto-upgrade-input" class="field-wrapper">
                            <span>{{ $t('index.auto_withdraw') }}</span>
                            <input type="text" id="auto-upgrade-input" class="field-input" autocomplete="off"
                                   v-model="game.bet.autoWithdraw">
                        </label>
                        <button
                            v-if="game.bet.bank > 0 && game.type === 'multiplier' && !takeBtn && game.lastBet.bank === 0"
                            @click="take" type="button" class="btn-base make-bet">{{ $t('index.take') }} <b>{{
                            parseFloat(game.multiplier).toFixed(2) }} X</b></button>
                        <button
                            v-else
                            :disabled="(selectedMy.price === 0 || $root.user === null) || !betBtn || game.type !== 'timer'"
                            @click="bet" type="button" class="btn-base make-bet">{{ $t('index.begin') }} <b>{{
                            selectedMy.price.toFixed(2) }} $</b></button>
                    </div>
                    <div class="koeff-labels">
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.10 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.10">X 1.1
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.20 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.20">X 1.2
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 1.50 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 1.50">X 1.5
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 2.00 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 2.00">X 2.0
                        </button>
                        <button type="button" class="koeff-label"
                                :class="[game.bet.autoWithdraw === 3.00 ? 'active' : '']"
                                @click="game.bet.autoWithdraw = 3.00">X 3.0
                        </button>
                    </div>
                </div>
            </div>
            <div class="mob-stats">
                <div class="mob-stats__item"><span>{{ $t('index.members') }}: </span><b>{{ game.stats.members }}</b></div>
                <div class="mob-stats__item"><span>{{ $t('index.skins') }}: </span><b>{{ game.stats.skins }}</b></div>
                <div class="mob-stats__item"><b>{{ game.stats.bank.toFixed(2) }} $</b></div>
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
                            <button type="button" class="big-flat-btn" @click="loadMoreWithdrawsItems">{{ $t('index.more') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Giveaway from "../../components/giveaway/Giveaway";

    export default {
        components: {
            Giveaway
        },
        data() {
            return {
                activeWithdrawModal: false,
                betBtn: true,
                takeBtn: false,
                withdrawBtn: false,
                selectedGame: 730,
                withdraw: {
                    minPrice: '',
                    maxPrice: '',
                    market_hash_name: '',
                    page: 1,
                    items: [],
                    activeBtn: true
                },
                selectedWithdraw: {
                    items: {},
                    price: 0.00
                },
                myItems: {},
                selectedMy: {
                    items: {},
                    price: 0.00
                },
                selectedAllMyItems: false,
                game: {
                    type: 'timer',
                    numbers: {
                        one: {
                            one: 0,
                            two: 9
                        },
                        two: {
                            one: 0,
                            two: 0
                        }
                    },
                    multiplier: 0.00,
                    stats: {
                        bank: 0.00,
                        members: 0,
                        skins: 0
                    },
                    bets: {},
                    bet: {
                        bank: 0.00,
                        items: {},
                        autoWithdraw: 2.00
                    },
                    lastBet: {
                        bank: 0.00,
                        item: {},
                        multiplier: 0.00,
                        autoWithdraw: 0.00,
                        win: 0.00
                    }
                },
                history: {}
            }
        },
        mounted() {
            this.$root.isLoading = true;

            this.getHistory();

            if (this.$cookie.get('token')) {
                setTimeout(() => {
                    this.loadWithdrawsItems();
                    this.loadMyItems();
                    this.myBet();
                }, 100);
            }

            if (this.$root.mobileSelectedItems.price > 0) {
                this.selectedMy = this.$root.mobileSelectedItems;
            }

            $('.modal__overlay').click(() => {
                this.activeWithdrawModal = false;
            });

            this.$root.$on('showInventories', this.initInventories);

            this.$root.$socket.client.emit('getGame');
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
            },
            'selectedMy.price': function (newVal, oldVal) {
                this.withdraw.page = 0;
                this.withdraw.items = [];
                let price = this.$root.user.balance + newVal;
                this.loadWithdrawsItems(price);
            }
        },
        methods: {
            openOrCloseWithdrawModal() {
                if (this.activeWithdrawModal) {
                    this.activeWithdrawModal = false;
                } else {
                    this.activeWithdrawModal = true;
                }
            },
            loadMoreWithdrawsItems() {
                this.withdraw.page++;
                this.loadWithdrawsItems();
            },
            loadWithdrawsItems(price = null) {
                if (price === null) {
                    price = this.withdraw.maxPrice
                }

                this.$root.axios.post('/all-items/getList', {
                    minPrice: this.withdraw.minPrice,
                    maxPrice: price,
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
            loadMyItems() {
                this.$root.axios.post('/user/getInventory', {
                    appId: this.selectedGame
                })
                    .then(res => {
                        this.myItems = res.data;
                    });
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
            initInventories() {
                this.loadWithdrawsItems();
                this.loadMyItems();
            },
            bet() {
                this.betBtn = false;

                this.$root.$socket.client.emit('newBet', {
                    items: this.selectedMy.items,
                    autoWithdraw: this.game.bet.autoWithdraw,
                    apiToken: this.$cookie.get('token')
                });
            },
            drawBets(data) {
                this.game.bets = data;
            },
            myBet() {
                this.$root.axios.post('/crash/myBet')
                    .then(res => {
                        const data = res.data;

                        if (typeof data.userBet.bank !== "undefined") {
                            this.game.bet = {
                                items: data.userBet.items,
                                bank: data.userBet.bank,
                                autoWithdraw: data.userBet.autoWithdraw
                            };
                        }

                        if (typeof data.lastBet.bank !== "undefined") {
                            this.game.lastBet = {
                                bank: data.lastBet.bank,
                                item: data.lastBet.item,
                                multiplier: data.lastBet.multiplier,
                                win: data.lastBet.win
                            };

                            this.game.bet.autoWithdraw = data.lastBet.autoWithdraw;
                        }
                    });
            },
            take() {
                this.takeBtn = true;

                this.$root.$socket.client.emit('take', {
                    apiToken: this.$cookie.get('token')
                });
            },
            getHistory() {
                this.$root.axios.post('/crash/getHistory')
                    .then(res => {
                        this.history = res.data;
                    })
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
            }
        },
        sockets: {
            crashTimer(time) {
                const arr = time.split('.');

                if (arr[0].length === 1) {
                    arr[0] = `0${arr[0]}`;
                }

                this.game.type = 'timer';
                this.game.numbers = {
                    one: {
                        one: arr[0].substring(0, 1),
                        two: arr[0].substring(1, 2)
                    },
                    two: {
                        one: arr[1].substring(0, 1),
                        two: arr[1].substring(1, 2)
                    }
                };
            },
            crashMultiplier(multiplier) {
                this.game.multiplier = parseFloat(multiplier).toFixed(2);

                const arr = multiplier.split('.');

                if (arr[0].length === 1) {
                    arr[0] = `0${arr[0]}`;
                }

                this.game.type = 'multiplier';
                this.game.numbers = {
                    one: {
                        one: arr[0].substring(0, 1),
                        two: arr[0].substring(1, 2)
                    },
                    two: {
                        one: arr[1].substring(0, 1),
                        two: arr[1].substring(1, 2)
                    }
                };
            },
            setGame(data) {
                const arr = data.time.split('.');

                if (arr[0].length === 1) {
                    arr[0] = `0${arr[0]}`;
                }

                this.game.type = data.type;
                this.game.numbers = {
                    one: {
                        one: arr[0].substring(0, 1),
                        two: arr[0].substring(1, 2)
                    },
                    two: {
                        one: arr[1].substring(0, 1),
                        two: arr[1].substring(1, 2)
                    }
                };
                this.drawBets(data.bets);

                this.$root.isLoading = false;
            },
            crashCrashed() {
                this.game.type = 'explosion';
            },
            errorBet(message) {
                this.$root.showNotify('error', this.$t(`index.${message}`));
                this.betBtn = true;
            },
            successBet(data) {
                this.$root.showNotify('success', this.$t('index.bet', {price: data.price.toFixed(2)}));
                this.game.bet = {
                    items: data.bet.items,
                    bank: data.bet.bank,
                    autoWithdraw: data.bet.autoWithdraw
                };
                this.selectedMy = {
                    items: {},
                    price: 0.00
                };
                this.$root.mobileSelectedItems = {
                    items: {},
                    price: 0.00
                };
                this.loadMyItems();
            },
            newBets(data) {
                this.drawBets(data);
            },
            clearBets(history) {
                this.game.bets = [];
                this.history = history;
                this.game.stats = {
                    members: 0,
                    bank: 0.00,
                    skins: 0
                };
                this.game.bet.items = {};
                this.game.bet.bank = 0.00;
                this.betBtn = true;
                this.game.multiplier = 1.00;
                this.game.lastBet = {
                    bank: 0.00,
                    item: {},
                    multiplier: 0.00,
                    autoWithdraw: 0.00,
                    win: 0.00
                };
                this.takeBtn = false;
                if (this.$cookie.get('sound') == 1) {
                    this.$root.playSound('push');
                }
            },
            setStats(data) {
                this.game.stats = {
                    members: data.members,
                    bank: data.bank,
                    skins: data.skins
                }
            },
            errorTake(message) {
                this.$root.showNotify('error', this.$t(`index.${message}`));
                this.takeBtn = false;
            },
            successTake(data) {
                if ((this.$root.user !== null && this.$root.user.id === data.user_id) || typeof data.user_id === "undefined") {
                    if (this.$cookie.get('sound') == 1) {
                        this.$root.playSound('select');
                    }
                    this.$root.showNotify('success', this.$t('index.win', {price: data.win.toFixed(2)}));
                    this.$root.user.balance = data.newBalance;
                    this.game.lastBet = {
                        bank: data.item.bank,
                        item: data.item.item,
                        multiplier: data.item.multiplier,
                        win: data.item.win
                    };
                    this.loadMyItems();
                }
            }
        }
    }
</script>

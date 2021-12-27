<template>
    <div class="inner-page" v-if="$root.lang === 'RU'">
        <div class="page-header">
            <div class="page-title"><span class="hide-above-l">Часто задаваемые вопросы</span><span
                class="hide-below-m">Часто задаваемые вопросы</span></div>
        </div>
        <div class="faq-content shadow">
            <ul class="tablist" role="tablist">
                <li class="tablist__item" role="presentation" @click="setTab(1)">
                    <button type="button" class="btn-base tablist__tab" :class="[activeTab === 1 ? 'active' : '']"
                            role="tab">Правила чата
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 1 ? 'active' : '']" role="tabpanel"
                         style="--height:93px;">
                        <div class="tablist__panel-inner">Ваш никнейм не должен содержать названия или ссылки других
                            сайтов.
                            Запрещено упоминать ютуб каналы в чате
                            Запрещена реклама своих дискорд каналов
                            Сделки запрещены
                            Упоминание посторонних сайтов запрещено
                            Попрошайничество запрещено.
                            Оскорбления в чью-либо сторону запрещены.
                            Запрещено скидывать любые ссылки/id/промики.
                            Каждый обязан соблюдать правила чата и с уважением относиться к участникам чата. В противном
                            случае, выдается бан/мут, на усмотрение администратора.
                            Запрещено отправлять сообщения, написанные только КАПСОМ, больше 50% символов КАПСОМ
                            относятся к этому правилу.
                            Сообщение не должно содержать лишь 1 символ.
                            Модераторы могут, на свое усмотрение, выдавать мут/бан, не разъясняя причины.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(2)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 2 ? 'active' : '']"
                            role="tab">Советы для хорошей игры
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 2 ? 'active' : '']" role="tabpanel"
                         style="--height:82px;">
                        <div class="tablist__panel-inner">Наш сайт очень требователен к хорошему и стабильному интернет
                            соеденению, также нужен современный браузер. Рекомендуем:
                            1. Использовать браузеры Google Chrome / Opera
                            2. Отключать видео/трансляции на ютубе/twitch итд
                            3. Отключать онлайн музыку
                            4. Отключать демонстрацию экрана в Discord / Skype.
                            Все это повышает ваш пинг, и из за этого могут быть лаги/задержки.

                            Рекомендуем также записывать игру на сайте в случае, если вы наблюдаете какие-либо лаги. В
                            будущем запись можно будет предъявить для того, чтобы рассмотрели заявку на возврат.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(3)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 3 ? 'active' : '']"
                            role="tab">Мультиакки
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 3 ? 'active' : '']" role="tabpanel"
                         style="--height:71px;">
                        <div class="tablist__panel-inner">На нашем сайте запрещено использование нескольких аккаунтов
                            сразу. Одна из причин - это способ забрать промо коды у других людей, а также использовать
                            бонусные программы для увеличения личного дохода. За использование нескольких аккаунтов
                            могут применяться некоторые меры: блокировка промокодов, изъятие вещей, закрытие выводов,
                            закрытие доступа к аккаунту
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(4)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 4 ? 'active' : '']"
                            role="tab">Почему при выводе скин возвращается
                        в инвентарь?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 4 ? 'active' : '']" role="tabpanel"
                         style="--height:60px;">
                        <div class="tablist__panel-inner">Возможно в данный момент на маркете нет нужного предмета, в
                            этом случае выберите другой предмет или подождите его появления.
                            Также не забудьте проверить доступность трейдов на Вашем аккаунте.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(5)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 5 ? 'active' : '']"
                            role="tab">Почему я не могу использовать
                        промокод?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 5 ? 'active' : '']" role="tabpanel"
                         style="--height:60px;">
                        <div class="tablist__panel-inner">На нашем сайте ЗАПРЕЩЕНО ИМЕТЬ ДВА И БОЛЕЕ АККАУНТА- за это
                            доступ к промокодам закрывается.
                            Если у вас только один аккаунт, то, вероятнее всего, вы уже использовали сегодня промокод
                            либо его лимит был исчерпан.
                            Также промокоды могут быть заблокированы по усмотрению администриации за слив промокодов в
                            чат
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(6)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 6 ? 'active' : '']"
                            role="tab">Почему не пришли деньги на баланс?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 6 ? 'active' : '']" role="tabpanel"
                         style="--height:49px;">
                        <div class="tablist__panel-inner">Подождите 5-30 минут, если проблема не решается, обратитесь в
                            поддержку.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(7)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 7 ? 'active' : '']"
                            role="tab">Как обменять баланс на предмет?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 7 ? 'active' : '']" role="tabpanel"
                         style="--height:49px;">
                        <div class="tablist__panel-inner">Нажмите кнопку «Обменять» в левом нижнем углу экрана.</div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(8)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 8 ? 'active' : '']"
                            role="tab">Как пополнить баланс?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 8 ? 'active' : '']" role="tabpanel"
                         style="--height:49px;">
                        <div class="tablist__panel-inner">Пополнить баланс можно, нажав на кнопку "Пополнить" в правой
                            верхней части экрана любым удобным для вас способом, в том числе скинами
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(9)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 9 ? 'active' : '']"
                            role="tab">У вашего сайта есть группа
                        ВКонтакте?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 9 ? 'active' : '']" role="tabpanel"
                         style="--height:49px;">
                        <div class="tablist__panel-inner">Да, у нас есть группа <a :href="$root.config.vk_group"
                                                                                   target="_blank">ВКонтакте</a></div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(10)">
                    <button type="button" class="btn-base tablist__tab " :class="[activeTab === 10 ? 'active' : '']"
                            role="tab">Где взять промокод?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 10 ? 'active' : '']" role="tabpanel"
                         style="--height:49px;">
                        <div class="tablist__panel-inner">Промо-коды публикуются в <a :href="$root.config.vk_group"
                                                                                      target="_blank">нашей группе
                            ВКонтакте "CSGO.RUN"</a></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="inner-page" v-else>
        <div class="page-header">
            <div class="page-title"><span class="hide-above-l">F.A.Q.</span><span class="hide-below-m">F.A.Q.</span>
            </div>
        </div>
        <div class="faq-content shadow">
            <ul class="tablist" role="tablist">
                <li class="tablist__item" role="presentation" @click="setTab(1)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Chat Rules
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 1 ? 'active' : '']" role="tabpanel" style="--height:135px;">
                        <div class="tablist__panel-inner">Your nickname should not contain the names or links of other
                            site. <br>
                            It is forbidden to mention Youtube / Twitch / Discord channels in the chat. <br>
                            Insults in any direction are prohibited. <br>
                            Begging is prohibited. <br>
                            It is forbidden to throw off any links / id / promos. (instead of, Administration and
                            Moderators). <br>
                            The message should not contain only 1 character or only 1 word. <br>
                            It is forbidden to send messages written only with CAPSLOCK.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(2)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Tips for a good game
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 2 ? 'active' : '']" role="tabpanel" style="--height:135px;">
                        <div class="tablist__panel-inner">
                            Our site is very demanding to a good and stable Internet connection, as well as need a
                            modern browser. We recommend: <br>
                            1. Use Google Chrome / Opera browsers <br>
                            2. Disable video / broadcasts on YouTube / Twitch <br>
                            3. Turn off online music <br>
                            4. To turn off the broadcast screen Discord / Skype. <br>
                            All this increases your ping, and there may be lags <br>

                            We also recommend recording the game on the site in case you observe any lags. In the
                            future, the record can be presented in order to consider the application for a refund.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(3)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Multi-accounts
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 3 ? 'active' : '']" role="tabpanel" style="--height:70px;">
                        <div class="tablist__panel-inner">It is not allowed, if you use more than 1 account at the same
                            time . One of the reasons is about using promo codes and stealing them from other people.
                            For the use of multiple accounts, some measures may be applied: Promo codes bans, Deleting
                            items, Withdrawal bans, Account bans
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(4)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Why skins are being returned to my
                        inventory while withdrawing?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 4 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">Probably, your item can not be found on the market, so choose
                            it to another or wait if it appears. Also check out if you have any trade bans at Steam.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(5)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Why i can't use a promo?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 5 ? 'active' : '']" role="tabpanel" style="--height:83px;">
                        <div class="tablist__panel-inner">НYou MUST NOT use two or more accounts on our site. If you do
                            promo codes are blocked for you, but the other functions are still available. <br>
                            If you have only one account, basically, you have already used a promo or the limit has been
                            reached. <br>
                            Also promo codes can be blocked if you send promos in the chat.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(6)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Why the money that i've deposited
                        wasn't sent to my balance?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 6 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">Please, wait for 5-30 minutes, if the problem is not solved,
                            contact support.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(7)">
                    <button type="button" class="btn-base tablist__tab " role="tab">How to get an item using my balance?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 7 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">Press the button "Exchange" at the bottom left.</div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(8)">
                    <button type="button" class="btn-base tablist__tab " role="tab">How to refill the balance?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 8 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">You can refill your balance by pressing "Deposit" at the top
                            right.
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(9)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Do you have any social groups?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 9 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">Yes, we have <a :href="$root.config.vk_group"
                                                                          target="_blank">VK Group</a>,FB group and also
                            Twitter, where you can find promos and giveaways
                        </div>
                    </div>
                </li>
                <li class="tablist__item" role="presentation" @click="setTab(10)">
                    <button type="button" class="btn-base tablist__tab " role="tab">Where I can find promos?
                        <svg focusable="false" aria-hidden="true">
                            <use xlink:href="/svg/svg.svg#arrow-down"></use>
                        </svg>
                    </button>
                    <div class="tablist__panel " :class="[activeTab === 10 ? 'active' : '']" role="tabpanel" style="--height:57px;">
                        <div class="tablist__panel-inner">We have <a :href="$root.config.vk_group" target="_blank">VK
                            Group</a>,FB group and also Twitter, where you can find promos and giveaways
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                activeTab: ''
            }
        },
        methods: {
            setTab(tab) {
                if (this.activeTab === tab) {
                    this.activeTab = '';
                } else {
                    this.activeTab = tab;
                }
            }
        }
    }
</script>

const app = require('express')(),
    server = require('http').createServer(app),
    Redis = require('redis'),
    RedisClient = Redis.createClient(),
    io = require('socket.io')(server),
    axios = require('axios');

const myArgs = process.argv.slice(2);
const domain = myArgs[0];
const SECRET_KEY = 'cZN^ZH8)mu~9e,>6M>3qKV=Ar^fFF,7/';

axios.defaults.baseURL = domain + '/api/bot/';

server.listen(8081);

RedisClient.subscribe('newMessage');
RedisClient.subscribe('loadChat');
RedisClient.subscribe('newFake');

RedisClient.on('message', async (channel, message) => {
    if (channel === 'newFake') {
        clearInterval(fakeMessages);
        if (message > 0.00) {
            fakeMessages = setInterval(() => {
                sendFakeMessage();
            }, 60000 * message);
        }
    } else {
        io.sockets.emit(channel, JSON.parse(message));
    }
});

io.on('connection', (socket) => {
    const updateOnline = () => {
        io.sockets.emit('online', Object.keys(io.sockets.adapter.rooms).length);
    };

    socket.on('disconnect', () => {
        updateOnline();
    });

    socket.on('newBet', (data) => {
        if (TIME <= 1) {
            return socket.emit('errorBet', 'game_started');
        }

        axios.post('newBet', {
            secretKey: SECRET_KEY,
            items: data.items,
            apiToken: data.apiToken,
            autoWithdraw: data.autoWithdraw
        })
            .then(res => {
                const result = res.data;

                if (result.success) {
                    if (typeof AUTO_WITHDRAWS[parseFloat(data.autoWithdraw).toFixed(2)] === "undefined") {
                        AUTO_WITHDRAWS[parseFloat(data.autoWithdraw).toFixed(2)] = [];
                    }

                    AUTO_WITHDRAWS[parseFloat(data.autoWithdraw).toFixed(2)].push({
                        token: data.apiToken
                    });

                    bets = result.bets;
                    game = result.game;

                    io.sockets.emit('newBets', bets);
                    io.sockets.emit('setStats', {
                        members: game.members,
                        skins: game.skins,
                        bank: game.bank
                    });

                    return socket.emit('successBet', {
                        price: result.price,
                        bet: result.bet
                    });
                } else {
                    return socket.emit('errorBet', result.message);
                }
            })
            .catch(err => {
                console.log(err);
                return socket.emit('errorBet', 'error');
            });
    });

    socket.on('getGame', (data) => {
        let arr = {
            status: 'timer',
            time: 0.00,
            bets: bets
        }

        if (_now > 0) {
            if (_now >= MULTIPLIER) {
                arr.type = 'explosion';
                arr.time = MULTIPLIER.toFixed(2).toString();
            } else {
                arr.type = 'multiplier';
                arr.time = _now.toFixed(2).toString();
            }
        } else if (TIME > 0) {
            arr.type = 'timer';
            arr.time = TIME.toFixed(2).toString();
        } else if (_now >= MULTIPLIER) {
            arr.type = 'explosion';
            arr.time = MULTIPLIER.toFixed(2).toString();
        }

        socket.emit('setStats', {
            members: game.members,
            skins: game.skins,
            bank: game.bank
        });

        socket.emit('setGame', arr);
    });

    socket.on('take', (data) => {
        if (_now.toFixed(2) > MULTIPLIER) {
            return socket.emit('errorTake', 'end');
        }

        USER_WITHDRAWS.push(data.apiToken);

        axios.post('take', {
            secretKey: SECRET_KEY,
            apiToken: data.apiToken,
            multiplier: _now.toFixed(2)
        })
            .then(res => {
                const result = res.data;

                if (result.success) {
                    bets = result.bets;

                    io.sockets.emit('newBets', bets);
                    return socket.emit('successTake', {
                        win: result.win,
                        item: result.item,
                        newBalance: result.newBalance
                    });
                } else {
                    return socket.emit('errorTake', result.message);
                }
            })
            .catch(err => {
                console.log(err)
                return socket.emit('errorTake', 'error');
            })
    });

    updateOnline();
});

let TIME = 0,
    AUTO_WITHDRAWS = [],
    WITHDRAWS_MULTIPLIER = [],
    MULTIPLIER = 0.00,
    USER_WITHDRAWS = [],
    _now = 0,
    _i = 0,
    game = {},
    bets = {},
    fakeMessages = null;

const startTimer = () => {
    TIME = 9.00;

    setTimeout(() => {
        fakeBets();
    }, 1000 * randomInteger(1, 4));

    const TIME_TO_START = setInterval(() => {
        if (TIME.toFixed(2) === '1.00') {
            setStatus(1);
        }
        if (TIME.toFixed(2) <= 0.00) {
            clearInterval(TIME_TO_START);
            startGame();
            return;
        }

        TIME -= 0.1;

        io.sockets.emit('crashTimer', TIME.toFixed(2).toString());
    }, 100);
};

const startGame = () => {
    let float = MULTIPLIER;
    let _now_old = -1;

    _i = 0,
        _now = 0;

    const START_GAME_INTERVAL = setInterval(async () => {
        _i++;
        _now = parseFloat(Math.pow(Math.E, 0.00006 * _i * 1000 / 20));

        if (_now_old.toFixed(2).toString() !== _now.toFixed(2).toString()) {
            _now_old = _now;
            if (_now >= float) {
                clearInterval(START_GAME_INTERVAL);
                io.sockets.emit('crashMultiplier', MULTIPLIER.toFixed(2).toString());
                io.sockets.emit('crashCrashed');
                crashBets();

                setTimeout(() => {
                    _i = 0, _now = 0;
                    getGame();
                }, 3000);
            } else {
                for (const [key, value] of Object.entries(AUTO_WITHDRAWS)) {
                    if (key <= parseFloat(_now.toFixed(2))) {
                        if (typeof AUTO_WITHDRAWS[key] !== "undefined") {
                            withdrawAuto(key, AUTO_WITHDRAWS[key]);
                        }
                    }
                }

                io.sockets.emit('crashMultiplier', _now.toFixed(2).toString());
            }
        }
    }, 50)
};

const getGame = () => {
    axios.post('getGame', {
        secretKey: SECRET_KEY
    })
        .then(res => {
            game = res.data.game;
            bets = res.data.bets;

            io.sockets.emit('clearBets', res.data.history);

            WITHDRAWS_MULTIPLIER = [];
            AUTO_WITHDRAWS = [];
            USER_WITHDRAWS = [];

            if (game.status === 0) {
                startTimer();
            }

            if (game.status === 1) {
                startGame();
            }
        })
        .catch(err => {
            console.log(err);
            setTimeout(() => {
                getGame();
            }, 2000);
        });
};

const setStatus = (status) => {
    axios.post('setStatus', {
        secretKey: SECRET_KEY,
        status: status
    })
        .then(res => {
            if (status === 1) {
                MULTIPLIER = res.data;
            }
        })
        .catch(err => {
            console.log(err);
        });
};

const crashBets = () => {
    axios.post('crashBets', {
        secretKey: SECRET_KEY
    })
        .then(res => {
            bets = res.data;
            io.sockets.emit('newBets', res.data);
        })
        .catch(err => {
            console.log(err);
        });
};

const withdrawAuto = (multiplier, members) => {
    if (typeof WITHDRAWS_MULTIPLIER[multiplier] === "undefined") {
        WITHDRAWS_MULTIPLIER[multiplier] = 1;

        axios.post('autoTake', {
            secretKey: SECRET_KEY,
            members: members,
            multiplier: multiplier,
            autoWithdraws: USER_WITHDRAWS
        })
            .then(res => {
                const result = res.data;

                bets = result.bets;
                io.sockets.emit('newBets', bets);

                result.members.forEach(member => {
                    io.sockets.emit('successTake', {
                        win: member.win,
                        item: member.item,
                        newBalance: member.newBalance,
                        user_id: member.user_id
                    });
                });
            })
    }
};

const getWithdraws = () => {
    axios.post('getWithdraws', {
        secretKey: SECRET_KEY
    })
        .then(res => {

        })
        .catch(err => {
            console.log(err);
        });
};

const fakeBets = () => {
    axios.post('fakeBets', {
        secretKey: SECRET_KEY
    })
        .then(res => {
            const result = res.data;

            result.members.forEach(withdraw => {
                if (typeof AUTO_WITHDRAWS[parseFloat(withdraw.autoWithdraw).toFixed(2)] === "undefined") {
                    AUTO_WITHDRAWS[parseFloat(withdraw.autoWithdraw).toFixed(2)] = [];
                }

                AUTO_WITHDRAWS[parseFloat(withdraw.autoWithdraw).toFixed(2)].push({
                    token: withdraw.apiToken
                });
            });

            bets = result.bets;
            game = result.game;

            io.sockets.emit('newBets', bets);
            io.sockets.emit('setStats', {
                members: game.members,
                skins: game.skins,
                bank: game.bank
            });
        })
        .catch(err => {
            console.log(err);
        });
};

const randomInteger = (min, max) => {
    let rand = min + Math.random() * (max + 1 - min);
    return Math.floor(rand);
}

const getLoadFakeMessages = () => {
    axios.post('getLoadFakeMessages', {
        secretKey: SECRET_KEY
    })
        .then(res => {
            const timer = res.data;

            if (timer > 0.00) {
                fakeMessages = setInterval(() => {
                    sendFakeMessage();
                }, 60000 * timer);
            }
        })
        .catch(err => {
            console.log(err);
        });
};

const sendFakeMessage = () => {
    axios.post('sendFakeMessage', {
        secretKey: SECRET_KEY
    })
        .then(res => {

        })
        .catch(err => {
            console.log(err);
        });
};

const getRaffle = () => {
    axios.post('getRaffle', {
        secretKey: SECRET_KEY
    })
        .then(res => {
            io.sockets.emit('setRaffle', res.data);
        })
        .catch(err => {
            console.log(err);
        });
};

getGame();
getLoadFakeMessages();

setInterval(() => {
    getWithdraws();
}, 15000);

setInterval(() => {
    getRaffle();
}, 60000);

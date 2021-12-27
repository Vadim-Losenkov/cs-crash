import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Index from "./pages/index/Index";
import AuthCallback from "./pages/auth-callback/AuthCallback";
import Terms from "./pages/terms/Terms";
import Faq from "./pages/faq/Faq";
import Profile from "./pages/profile/Profile";
import User from "./pages/user/User";
import Game from "./pages/game/Game";
import Support from "./pages/support/Support";
import Ticket from "./pages/ticket/Ticket";
import Bets from "./pages/bets/Bets";
import Chat from "./components/chat/Chat";
import Inventory from "./pages/inventory/Inventory";

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: Index
        },
        {
            path: '/auth/callback',
            name: 'auth-callback',
            component: AuthCallback
        },
        {
            path: '/terms',
            name: 'terms',
            component: Terms
        },
        {
            path: '/faq',
            name: 'faq',
            component: Faq
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile
        },
        {
            path: '/user/:steamid',
            name: 'user',
            component: User
        },
        {
            path: '/game/:id',
            name: 'game',
            component: Game
        },
        {
            path: '/support',
            name: 'support',
            component: Support
        },
        {
            path: '/ticket/:id',
            name: 'ticket',
            component: Ticket
        },
        {
            path: '/bets',
            name: 'bets',
            component: Bets
        },
        {
            path: '/chat',
            name: 'chat',
            component: Chat
        },
        {
            path: '/inventory',
            name: 'inventory',
            component: Inventory
        },
        {
            path: '*',
            redirect: '/'
        }
    ]
})

import bearer from '@websanova/vue-auth/drivers/auth/bearer';
import axios from '@websanova/vue-auth/drivers/http/axios.1.x';
import router from '@websanova/vue-auth/dist/drivers/router/vue-router.2.x';

const config = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'laravel-vue-todo-app',
    tokenStore: ['localStorage'],
    registerData: { url: 'auth/register', method: 'POST', redirect: '/' },
    loginData: { url: 'auth/login', method: 'POST', redirect: '/', fetchUser: true },
    logoutData: { url: 'auth/logout', method: 'POST', redirect: '/login', makeRequest: true },
    fetchData: { url: 'auth/user', method: 'GET', enabled: true },
    refreshData: { url: 'auth/refresh', method: 'GET', enabled: true, interval: 30 }
}

export default config

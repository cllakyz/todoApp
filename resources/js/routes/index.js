import VueRouter from 'vue-router';

import Login from "../pages/auth/Login";
import Register from "../pages/auth/Register";
import TodoList from '../pages/todos/TodoList';

const routes = [
    {
        path: '/',
        name: 'todoList',
        component: TodoList,
        meta: {
            auth: true
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
];

export default new VueRouter({
    mode: 'history', //hash,
    history: true,
    routes
});

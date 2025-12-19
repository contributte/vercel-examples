import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// Import page components
import HomePage from './pages/BlogController/HomePage.vue'
import AboutPage from './pages/BlogController/AboutPage.vue'
import ContactPage from './pages/BlogController/ContactPage.vue'
import SingleArticle from './pages/BlogController/SingleArticle.vue'

const routes = [
    { path: '/', component: HomePage },
    { path: '/about', component: AboutPage },
    { path: '/contact', component: ContactPage },
    { path: '/posts/:article', component: SingleArticle }
]

export const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition ?? { x: 0, y: 0 }
    }
})

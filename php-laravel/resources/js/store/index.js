import Vue from 'vue'
import Vuex, { Store } from 'vuex'

Vue.use(Vuex)

import articles from './modules/articles'

export const store = new Store({
    state: {
        bio: null,
        contact: null,
        author: 'John Doe'
    },
    modules: {
        articles
    }
})

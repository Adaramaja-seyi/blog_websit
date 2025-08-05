// src/main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHeart, faComment, faShare, faUser, faCog, faSignOutAlt, faFileAlt, faComments, faTachometerAlt } from '@fortawesome/free-solid-svg-icons'
import { faHeart as farHeart, faComment as farComment } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Toast options
const toastOptions = {
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: 'button',
    icon: true,
    rtl: false
}

// FontAwesome library setup
library.add(
    faHeart, farHeart,
    faComment, farComment,
    faShare, faUser,
    faCog, faSignOutAlt,
    faFileAlt, faComments,
    faTachometerAlt
)

// Create app
const app = createApp(App)
// Global components
app.component('font-awesome-icon', FontAwesomeIcon)

// Use plugins
app.use(router)
app.use(Toast, toastOptions)

// Mount
app.mount('#app')

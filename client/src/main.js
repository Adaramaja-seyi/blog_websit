// src/main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
// Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHeart, faComment, faShare, faUser, faCog, faSignOutAlt, faFileAlt, faComments, faTachometerAlt } from '@fortawesome/free-solid-svg-icons'
import { faHeart as farHeart, faComment as farComment } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

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

app.component('font-awesome-icon', FontAwesomeIcon)

// Use plugins
app.use(router)
app.use(ToastPlugin, {
    position: 'top-right',
    duration: 2000,
    dismissible: true,
    pauseOnHover: true,
});

// Mount
app.mount('#app')

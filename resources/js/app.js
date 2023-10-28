import './bootstrap';
import '../css/app.css'
import {createApp} from "vue";
import app from "./layouts/App.vue";
import vuetify from "./vuetify";
import "vuetify/dist/vuetify.min.css";
import routes from './routes'

createApp(app)
    .use(routes)
    .use(vuetify)
    .mount("#app");


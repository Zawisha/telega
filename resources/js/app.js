import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import Homemain from './components/Homemain.vue';
import MainInputs from './components/telegram/MainInputs.vue';
import AuthorizationTelegram from './components/telegram/AuthorizationTelegram.vue';
import MainTelegram from './components/telegram/MainTelegram.vue';
import JoinGroup from './components/telegram/JoinGroup.vue';
import InviteTelegram from './components/telegram/InviteTelegram.vue';
import DatabaseUserTelegram from './components/telegram/DatabaseUserTelegram.vue';
import ParentDatabaseUserTelegram from './components/telegram/ParentDatabaseUserTelegram.vue';
import NewGroupTelegram from './components/telegram/NewGroupTelegram.vue';
import TelegramNavi from './components/navigation/TelegramNavi.vue';
import SearchTelegram from './components/search/SearchTelegram.vue';
import SearchAdmin from './components/search/SearchAdmin.vue';
import Filters from './components/search/Filters.vue';
import VseAvtoryChata from './components/filters/VseAvtoryChata.vue';
import StopSlova from './components/filters/StopSlova.vue';
import LinkInPost from './components/filters/LinkInPost.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '../css/main.css'; // Импортируем глобальные стили
import MainMixin from './mixins/mainMixin.js'

const app = createApp({});

app.component('Homemain', Homemain);
app.component('MainInputs', MainInputs);
app.component('AuthorizationTelegram', AuthorizationTelegram);
app.component('MainTelegram', MainTelegram);
app.component('JoinGroup', JoinGroup);
app.component('InviteTelegram', InviteTelegram);
app.component('DatabaseUserTelegram', DatabaseUserTelegram);
app.component('ParentDatabaseUserTelegram', ParentDatabaseUserTelegram);
app.component('NewGroupTelegram', NewGroupTelegram);
app.component('TelegramNavi', TelegramNavi);
app.component('SearchTelegram', SearchTelegram);
app.component('SearchAdmin', SearchAdmin);
app.component('Filters', Filters);
app.component('VseAvtoryChata', VseAvtoryChata);
app.component('StopSlova', StopSlova);
app.component('LinkInPost', LinkInPost);
app.mixin(MainMixin);
app.mount("#app");

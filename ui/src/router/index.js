import Vue from 'vue';
import VueRouter from 'vue-router';
import LoginAuth from '../components/LoginAuth.vue';
import RegisterUser from '../components/RegisterUser.vue';
import ChatRoom from '../components/ChatRoom.vue';
import FriendFunctions from '../components/FriendFunctions.vue';


Vue.use(VueRouter);

const routes = [
  { path: '/login', component: LoginAuth },
  { path: '/register', component: RegisterUser },
  { path: '/chat-room', component: ChatRoom },
  { path: '/friend-functions', component: FriendFunctions },
];

const router = new VueRouter({
  routes,
});

export default router;

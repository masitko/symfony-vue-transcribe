import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import RegisterUserPage from '../components/RegisterUserPage.vue';
import LoginPage from '../components/LoginPage.vue';
import ProfilePage from '../components/ProfilePage.vue';
import UsersPage from '../components/UsersPage.vue';
import TranscriptionsPage from '../components/TranscriptionsPage.vue';
import TranscriptionAddPage from '../components/TranscriptionAddPage.vue';

import UserEdit from '../components/UserEdit.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      component: LoginPage
    },
    {
      path: '/register',
      component: RegisterUserPage
    },
    {
      path: '/profile',
      component: ProfilePage
    },
    {
      path: '/users',
      component: UsersPage
    },
    {
      path: '/user/:userId',
      name: 'user',
      // lazy-loaded
      component: UserEdit
    },
    {
      path: '/transcriptions',
      name: 'transcriptions',
      component: TranscriptionsPage
    },
    {
      path: '/transcription/add',
      name: 'transcription-add',
      component: TranscriptionAddPage
    },
  ]
})

export default router

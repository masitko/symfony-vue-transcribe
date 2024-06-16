import axios from 'axios';

import { onMounted } from 'vue'

import { useAuthStore } from '@/stores/auth.store';

const API_URL = 'https://localhost:12443/auth/';


export function useAuthService() {

  onMounted(() => {
    console.log('AuthService.onMounted')
  });

  function getAuthHeader(): Headers {
    const user = JSON.parse(localStorage.getItem('user') || '""');
    const headers = new Headers();
    if (user && user.accessToken) {
      headers.set('Authorization', 'Bearer ' + user.accessToken);
    }
    return headers;
  }

  function login(user: Partial<IUser>) {
    return axios
      .post(API_URL + 'login_check', {
        email: user.email,
        password: user.password
      })
      .then((response) => {
        console.log(response.data)
        if (response.data.token && response.data.user) {
          response.data.user.accessToken = response.data.token;
          localStorage.setItem('user', JSON.stringify(response.data.user));
        }
        useAuthStore().loginSuccess(response.data.user);
        return response.data;
      }).catch((error) => {
        useAuthStore().loginFailure();
        console.log(error);
      });
  }

  function logout() {
    useAuthStore().logout();
    localStorage.removeItem('user');
  }

  function register(user: IUser) {
    const headers = new Headers();
    headers.set('Content-Type', 'application/json');
    return fetch(API_URL + `register`, {
      mode: 'cors', // no-cors, *cors, same-origin
      method: 'POST',
      headers: headers,
      body: JSON.stringify(user)
    }).then((response) => {
      console.log(response);
      return response.json();
    }).catch((error) => {
      console.log(error);
    })
  }

  return {
    login,
    logout,
    register,
    getAuthHeader
  }
}

// export default new AuthService();

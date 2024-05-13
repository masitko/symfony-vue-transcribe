<template>
  <div class="container" v-if="currentUser">
    <header class="jumbotron">
      <h2>
        <strong>{{ currentUser?.name }}</strong> Profile:
      </h2>
    </header>
    <p>
      <strong>Token:</strong>
      {{ currentUser?.accessToken?.substring(0, 20) }} ... {{
    currentUser?.accessToken?.substring(currentUser.accessToken.length -
      20) }}
    </p>
    <p>
      <strong>Id:</strong>
      {{ currentUser?.id }}
    </p>
    <p>
      <strong>Email:</strong>
      {{ currentUser?.email }}
    </p>
    <strong>Authorities:</strong>
    <ul>
      <li v-for="role in currentUser?.roleNames" :key="role">{{ role }}</li>
    </ul>
  </div>
</template>

<script setup lang="ts">

import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store';
import { onMounted, ref } from 'vue';

const currentUser = ref<IUser | null>(null);

onMounted(() => {
  console.log('ProfilePage mounted');
  currentUser.value = useAuthStore().getUser;
  console.log(currentUser.value);
  if (!currentUser.value) {
    useRouter().push('/login');
  }

});

</script>
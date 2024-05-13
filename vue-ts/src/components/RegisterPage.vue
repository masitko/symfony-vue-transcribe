<template>
  <div class="container columns is-centered">
    <div class="column is-half has-text-centered">
      <img id="profile-img" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" class="profile-img-card" />
      <form @submit="onSubmit" v-if="!successful">

        <div class="field">
          <label class="label" for="name">Name</label>
          <div class="control">
            <input v-model="name" v-bind="nameAttrs" name="name" type="text" class="input" autocomplete="off" />
          </div>
          <div class="error-feedback">{{ errors.name }}</div>
        </div>

        <div class="field">
          <label class="label" for="email">Email</label>
          <div class="control">
            <input v-model="email" v-bind="emailAttrs" name="email" type="email" class="input" autocomplete="off" />
          </div>
          <div class="help">{{ errors.email }}</div>
        </div>

        <div class="field">
          <label class="label" for="password">Password</label>
          <div class="control">
            <!-- <Field name="password" type="password" class="input" /> -->
            <input v-model="password" v-bind="passwordAttrs" name="password" type="password" class="input" autocomplete="off" />
          </div>
          <div class="help">{{ errors.password }}</div>
        </div>

        <div class="control">
          <button class="button is-primary" :disabled="loading">
            <span v-show="loading" class="spinner-border spinner-border-sm"></span>
            Register
          </button>
        </div>
      </form>


      <div v-if="message" class="alert mt-5" :class="successful ? 'alert-success' : 'alert-danger'">
        {{ message }}
      </div>
      <div v-if="successful" class="mt-5">
        Would you like to <router-link to="/login">Login</router-link> ?
      </div>

    </div>
  </div>
</template>

<script setup land="ts">
import { ref } from 'vue'
import { useForm } from "vee-validate";

import { useAuthService } from '@/services/auth.service';

import { object, string } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';

const { errors, handleSubmit, defineField } = useForm({
  validationSchema: toTypedSchema(
    object({
      name: string().min(5),
      email: string().min(5),
      password: string().min(5),
    }),
  )
});
const [name, nameAttrs] = defineField('name');
const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');

const loading = ref(false);
const successful = ref(false);
const message = ref('');
const AuthService = useAuthService();

const onSubmit = handleSubmit(user => {
  message.value = "";
  successful.value = false;
  loading.value = true;

  AuthService.register({
    name: user.name,
    email: user.email,
    password: user.password,
  })
    .then(data => {
      console.log(data)
      message.value = 'Registration Successful!';
      successful.value = true;
      loading.value = false;
    })
    .catch(error => {
      message.value = error.message || error.toString();
      successful.value = false;
      loading.value = false;
    }
    );
});
</script>

<style scoped>
.card-container {
  max-width: 300px;
  margin: auto;
}
</style>
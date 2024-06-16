<template>
  <div class="container columns is-centered">
    <div class="column is-half has-text-centered">
      <!-- <img id="profile-img" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" class="profile-img-card" /> -->
      <form @submit="onSubmit" v-if="!successful">
        <div class="field">
          <label class="label" for="name">Name</label>
          <div class="control">
            <input v-model="name" v-bind="nameAttrs" name="name" type="text" class="input" autocomplete="off" />
          </div>
          <div class="help">{{ errors.name }}</div>
        </div>

        <div class="field">
          <label class="label" for="description">Description</label>
          <div class="control">
            <input v-model="description" v-bind="descriptionAttrs" name="description" type="text" class="input" autocomplete="off" />
          </div>
          <div class="help">{{ errors.description }}</div>
        </div>

        <div class="field">
          <label class="label" for="fileName">Please choose a file</label>
          <div class="control">
            <input ref="fileInput" type="file" v-bind="fileAttrs" name="fileName" @change="handleFileChange" />
          </div>
          <div class="error-feedback">{{ errors.file }}</div>
        </div>

        <div class="control" v-if="!successful">
          <button class="button is-primary" :disabled="loading">
            <span v-show="loading" class="spinner-border spinner-border-sm"></span>
            Save
          </button>
        </div>
      </form>

      <div v-if="message" class="alert mt-5" :class="successful ? 'alert-success' : 'alert-danger'">
        {{ message }}
      </div>

      <div class="control mt-5" v-if="successful">
        <router-link :to="{ name: 'transcriptions' }" custom v-slot="{ navigate }">
          <button class="button is-primary" @click="navigate" role="link">
            <span v-show="loading" class="spinner-border spinner-border-sm"></span>
            Go back to list of transcriptions
          </button>
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useForm } from "vee-validate";

import TranscriptionService from "../services/transcription.service";

import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';

const fileInput = ref<HTMLInputElement | null>(null)

const MAX_FILE_SIZE = 1000000;
const ACCEPTED_FILE_TYPES = ["audio/mp3", "audio/wav", "audio/mpeg"];

const fileSchema = z.any()
  .refine((file) => file, `Please select a file.`)
  .refine((file) => file?.size <= MAX_FILE_SIZE, `Max image size is 1MB.`)
  .refine(
    (file) => ACCEPTED_FILE_TYPES.includes(file?.type),
    "Only .mp3 and .wav formats are supported."
  );

const { errors, handleSubmit, defineField } = useForm({
  validationSchema: toTypedSchema(
    z.object({
      name: z.string().min(5),
      description: z.string().optional(),
      file: fileSchema,
    }),
  )
});
const [file, fileAttrs] = defineField('file');
const [name, nameAttrs] = defineField('name');
const [description, descriptionAttrs] = defineField('description');

const loading = ref(false);
const successful = ref(false);
const message = ref('');

function handleFileChange(event: Event) {
  console.log('handleFileChange', fileInput)
  console.log('handleFileChange', event)
  file.value = fileInput.value?.files && fileInput.value?.files[0];
}

const onSubmit = handleSubmit(transcription => {
  console.log('onSumbit', transcription)

  message.value = "";
  successful.value = false;
  loading.value = true;
  TranscriptionService.addTranscription({
    name: transcription.name,
    description: transcription.description,
    file: transcription.file,
  })
    .then((response) => response.json())
    .then(
      (data) => {
        console.log(data);
        message.value = 'File Uploaded Successfully';
        successful.value = true;
        loading.value = false;
      },
      (error) => {
        message.value =
          (error.response &&
            error.response.data &&
            error.response.data.message) ||
          error.message ||
          error.toString();
        successful.value = false;
        loading.value = false;
      }
    );
})

onMounted(() => {
  // getTranscription(transcriptionId);
});

</script>
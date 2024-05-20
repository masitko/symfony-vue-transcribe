<template>
  <div class="container">
    <header class="jumbotron">
      <h3>TRANSCRIPTIONS</h3>
      <div class="field">
        <label class="label" for="transcriptionSearch">Search</label>
        <div class="control">
          <input name="transcriptionSearch" v-model="transcriptionSearch" type="text" class="input" autocomplete="off" />
        </div>
      </div>

    </header>

    <table class="table" v-if="transcriptions.length">
      <thead>
        <tr>
          <th><abbr title="Name Column">Name</abbr></th>
          <th><abbr title="Description Column">Description</abbr></th>
          <th><abbr title="Uploaded Column">Uploaded</abbr></th>
          <th><abbr title="Processed Column">Processed</abbr></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transcription in transcriptions" :key="transcription.id">
          <td>{{ transcription.name }}</td>
          <td>{{ transcription.description }}</td>
          <td>
            <!-- <router-link :to="{ name: 'transcription', params: { transcriptionId: transcription.id } }">
              View
            </router-link> -->
          </td>
        </tr>
      </tbody>
    </table>
    <h5 v-else>{{ message }}</h5>

    <router-link :to="{name: 'transcription-add'}" custom v-slot="{ navigate }">
      <button class="button is-primary" @click="navigate" role="link">
        Add New Transcription
      </button>
    </router-link>
  </div>
</template>

<script setup lang="ts">

import { ref, watch, watchEffect, onMounted } from 'vue'

import TranscriptionService from "../services/transcription.service";

const transcriptions = ref<ITranscription[]>([]);
const transcriptionSearch = ref('');
const message = ref('');

const getTranscriptions = (search?: string) => {
  TranscriptionService.getAllTranscriptions(search)
    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      message.value = data.length ? '' : 'No transcriptions found';
      transcriptions.value = data;
    }).catch(
      (error) => {
        console.error(error);
        message.value =
          error?.response?.data?.message ||
          error.message ||
          error.toString();
      }
    );
}

watch(transcriptionSearch, (newSearch, oldSearch) => {
  if (newSearch.length && newSearch !== oldSearch) {
    console.log('watch:', newSearch, oldSearch);
  }
});

watchEffect(() => {
  console.log('watch EFFECT:,', transcriptionSearch.value);
  getTranscriptions(transcriptionSearch.value);
});

onMounted(() => {
  // getTranscriptions();
});
</script>
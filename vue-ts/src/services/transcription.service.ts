// import axios from 'axios';
// import authHeader from './auth-header';
import { useAuthService } from './auth.service';

const API_URL = 'https://localhost:12443/api/';

class TranscriptionService {

  getPublicContent() {
    return fetch(API_URL + 'all');
  }

  getTranscription(transcriptionId: string) {
    return fetch(API_URL + `transcription/${transcriptionId}`, { headers: useAuthService().getAuthHeader() });
  }

  addTranscription(data: ITranscription) {
    const headers = useAuthService().getAuthHeader();
    // headers.set('Content-Type', 'multipart/form-data');
    const formData = new FormData();
    for (const key in data) {
      // if (data.hasOwnProperty(key)) {
        formData.append(key, data[key]);
      // }
    }
    return fetch(API_URL + `transcriptions`, {
      method: 'POST',
      headers: headers,
      body: formData
    });
  }

  getAllTranscriptions(search: string = '') {
    return fetch(API_URL + `transcriptions/${search}`, { headers: useAuthService().getAuthHeader() });
  }

}

export default new TranscriptionService();

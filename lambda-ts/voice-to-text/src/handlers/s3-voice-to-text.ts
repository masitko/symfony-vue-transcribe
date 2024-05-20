import { S3Client, GetObjectCommand, GetObjectCommandInput } from '@aws-sdk/client-s3';
import { getSignedUrl } from '@aws-sdk/s3-request-presigner'
import { Handler, S3CreateEvent } from 'aws-lambda';

interface getSignedUrlParams extends GetObjectCommandInput {
  Expires: number;
  ACL: string;
}
const s3 = new S3Client({});

export const handler: Handler = async (event: S3CreateEvent, context) => {

  const record = event.Records[0];
  const params = {
    Bucket: record.s3.bucket.name,
    Key: record.s3.object.key,
  };

  const getObject = new GetObjectCommand(params);
  const fileData = await s3.send(getObject);
  try {
    const signedUrl = await getSignedAudioUrl({
      Bucket: params.Bucket,
      Key: params.Key,
      Expires: 3600,
      ACL: 'public-read',
    });
    const jobData = await startTrascription(signedUrl);
    console.log('RESULT: ', jobData);
    const storeResponse = await storeTranscriptionId(params.Key, jobData.id, jobData.status);
    console.log('STORED: ', storeResponse);
    return jobData;
  }
  catch (err: any) {
    console.log(err);
    throw new Error(err);
  }
};

const storeTranscriptionId = async (filePath: string, providerId: string, status: string) => {
  if (!process.env.SYMFONY_APP_URL) {
    throw new Error('SYMFONY_APP_URL is not set!');
  }
  const url = `${process.env.SYMFONY_APP_URL}/webhook/lambda`;
  const body = {
    file_path: filePath,
    provider_id: providerId,
    status: status,
  }
  const headers = new Headers();
  headers.set('Content-Type', 'application/json');
  if (process.env.LAMBDA_WEBHOOK_ACCESS_KEY) {
    headers.set('X-Authentication-Token', process.env.LAMBDA_WEBHOOK_ACCESS_KEY);
  }
  const options: RequestInit = {
    method: 'POST',
    body: JSON.stringify(body),
    headers: headers,
  }
  const jobResponse = await fetch(url, options);
  return jobResponse;
}

const getSignedAudioUrl = async (fileParams: getSignedUrlParams) => {
  const getObjectCommand = new GetObjectCommand(fileParams)
  const signedUrl = await getSignedUrl(s3, getObjectCommand);
  console.log('URL: ', signedUrl);
  return signedUrl;
}

const startTrascription = async (signedUrl: string) => {
  const url = 'https://api.assemblyai.com/v2/transcript'
  const webhookUrl = `${process.env.SYMFONY_APP_URL}/webhook/assembly`;
  const body = {
    audio_url: signedUrl,
    webhook_url: webhookUrl,
    webhook_auth_header_name: 'X-Authentication-Token',
    webhook_auth_header_value: process.env.ASSEMBLY_WEBHOOK_ACCESS_KEY,
    language_code: 'en_uk',
    entity_detection: true,
    speaker_labels: true,
    sentiment_analysis: true,
  }
  const headers = new Headers();
  headers.set('Content-Type', 'application/json');
  if (process.env.ASSEMBLY_AI_API_KEY) {
    headers.set('Authorization', process.env.ASSEMBLY_AI_API_KEY);
  }
  const options: RequestInit = {
    method: 'POST',
    body: JSON.stringify(body),
    headers: headers,
  }
  const jobResponse = await fetch(url, options);
  return await jobResponse.json();
}


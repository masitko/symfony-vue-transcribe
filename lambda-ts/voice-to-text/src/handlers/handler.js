import { S3, S3Client, GetObjectCommand } from '@aws-sdk/client-s3';
import { getSignedUrl } from '@aws-sdk/s3-request-presigner'

console.log('Loading function');
const s3 = new S3();
const s3Client = new S3Client({ region: 'eu-west-2' });

export const handler = async (event) => {
    console.log('Received event:', JSON.stringify(event, null, 2));

    // Get the object from the event and show its content type
    const bucket = event.Records[0].s3.bucket.name;
    const key = decodeURIComponent(event.Records[0].s3.object.key.replace(/\+/g, ' '));
    const fileParams = {
        Bucket: bucket,
        Key: key,
        Expires: 3600,
        ACL: 'public-read',
    };
    try {
        const signedUrl = await getSignedAudioUrl(fileParams);
        const jobData = await startTrascription(signedUrl);
        console.log('RESULT: ', jobData);
        return jobData;
    }
    catch (err) {
        console.log(err);
        throw new Error(err);
    }
};

const getSignedAudioUrl = async (fileParams) => {
    const uploaded = await s3.getObject(fileParams);
    console.log('Params: ', uploaded);

    const getCommand = new GetObjectCommand(fileParams)
    const signedUrl = await getSignedUrl(s3Client, getCommand);
    console.log('URL: ', signedUrl);
    return signedUrl;
}

const startTrascription = async (signedUrl) => {
    const url = 'https://api.assemblyai.com/v2/transcript'
    const body = {
        audio_url: signedUrl,
        language_code: 'en_uk',
        entity_detection: true,
        speaker_labels: true,
        sentiment_analysis: true,
    }
    const options = {
        method: 'POST',
        body: JSON.stringify(body),
        headers: {
            'Content-Type': 'application/json',
            'Authorization': process.env.ASSEMBLY_AI_API_KEY
        },
    }
    const jobResponse = await fetch(url, options);
    return await jobResponse.json();
}


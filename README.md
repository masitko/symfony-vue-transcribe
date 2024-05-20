## Symfony 7 API + Vue 3 SPA + AWS Lambda

Example of a Symfony 7 API with a TypeScript Vue 3 frontend, using JWT tokens for user's authentication and
employing AWS Lambda function to automatically transcribe uploaded to S3 bucket voice files to text

How does it work:
1. User needs to register and login to the frontend
2. There is an option to upload a voice file (*.mp3 or *.wav)
3. The file is then uploaded straight to the S3 bucket
4. Once the file is uploaded, the S3 ObjectCreated event triggers our Lambda function to start
5. Lambda function creates self-signed URL to the uploaded file, so it can be accesses for a limited period of time by an external service
6. The same function creates transcript request to the Assembly AI to process the file and provides a webhook URL to notify when the job is done.
7. Lambda function sends another request to a Symfony backend webhook providing job ID for the transcription correspondig to the uploaded file
8. Once the job is done Assebly AI notify Symfony backend that the job is done by calling provided earlier webhook.
9. Symfony backend matches ID provided by Labda function and Assembly AI to update the record with the transcription 


## Getting Started

This project uses (nearly) official Symfony docker project to run local environment.
You can check how to use it [here](https://github.com/dunglas/symfony-docker)

Folder vue-ts contains the frontend Vue SPA 
This is a standard Vue installation so you can use [Vite](https://vitejs.dev/) to run it locally

Lambda function is in the lambda-ts folder.
You can read how to install and use AWS SAM CLI [here](https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/install-sam-cli.html)

If you want to test it locally you can use [ngrok](https://ngrok.com/) to expose local backend to the external services


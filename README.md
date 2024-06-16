## Symfony 7 API + Vue 3 SPA + AWS Lambda

Example project of a Symfony 7 REST API with a TypeScript Vue 3 frontend, utilizing JWT tokens for user authentication.
Added an AWS Lambda function to automatically transcribe voice files uploaded to an S3 bucket.


How does it work?

1. Users need to register and log in to the frontend.
2. There is an option to upload a voice file (*.mp3 or *.wav)
3. The file is then uploaded directly to the S3 bucket.
4. Once the file is uploaded, the S3 ObjectCreated event triggers our Lambda function to start.
5. Lambda function creates a self-signed URL to the uploaded file, enabling it to be accessed for a limited period of time by an external service.
6. The function generates a transcript request to Assembly AI to process the file and includes a webhook URL to receive notifications when the job is completed.
7. Lambda function sends another request to a Symfony backend webhook, providing the job ID for the transcription corresponding to the uploaded file.
8. Once the job is completed, Assembly AI notifies the Symfony backend by calling the webhook provided earlier.
9. Symfony backend matches the ID provided by the Lambda function and Assembly AI to update the record with the transcription.


## Getting Started

This project uses (nearly) official Symfony docker project to run local environment.
You can check how to use it [here](https://github.com/dunglas/symfony-docker)

Folder vue-ts contains the frontend Vue SPA 
This is a standard Vue installation so you can use [Vite](https://vitejs.dev/) to run it locally

Lambda function is in the lambda-ts folder.
You can read how to install and use AWS SAM CLI [here](https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/install-sam-cli.html)

If you want to test it locally you can use [ngrok](https://ngrok.com/) to expose local backend to the external services


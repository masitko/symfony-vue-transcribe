<?php

namespace App\RemoteEvent;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\TranscriptionRepository;
use Psr\Log\LoggerInterface;

#[AsRemoteEventConsumer('assembly')]
final class AssemblyWebhookConsumer implements ConsumerInterface
{
  public function __construct(
    private LoggerInterface $logger,
    private HttpClientInterface $client,
    private ParameterBagInterface $parameterBag,
    private TranscriptionRepository $transcriptionRepository
  ) {
  }

  public function consume(RemoteEvent $event): void
  {
    $this->logger->info('Received Assembly AI event: ' . json_encode($event->getPayload()));

    $transcriptId = $event->getPayload()['transcript_id'];
    $url = "https://api.assemblyai.com/v2/transcript/$transcriptId";
    $response = $this->client->request('GET', $url, [
      'headers' => [
        'Authorization' => $this->parameterBag->get('ASSEMBLY_AI_API_KEY'),
      ],
    ]);
    $this->logger->info('Received Assembly AI event: ' . json_encode($response->getContent()));

    $transcription = $this->transcriptionRepository->findOneBy(['providerId' => $event->getPayload()['transcript_id']]);
    $transcription->setStatus($event->getPayload()['status']);
    $content = json_decode($response->getContent());
    $transcription->setBody($content->text);

    $this->transcriptionRepository->getEntityManager()->flush();
  }
}

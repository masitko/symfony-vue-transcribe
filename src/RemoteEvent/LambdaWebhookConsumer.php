<?php

namespace App\RemoteEvent;

use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use App\Repository\TranscriptionRepository;
use Psr\Log\LoggerInterface;

#[AsRemoteEventConsumer('lambda')]
final class LambdaWebhookConsumer implements ConsumerInterface
{
  public function __construct(
    private LoggerInterface $logger,
    private TranscriptionRepository $transcriptionRepository
  ) {
  }

  public function consume(RemoteEvent $event): void
  {
    $this->logger->info('Received Lambda event: ' . json_encode($event->getPayload()));

    $transcription = $this->transcriptionRepository->findOneBy(['filePath' => $event->getPayload()['file_path']]);
    $transcription->setProviderId($event->getPayload()['provider_id']);
    $transcription->setStatus($event->getPayload()['status']);

    $this->transcriptionRepository->getEntityManager()->flush();
  }
}

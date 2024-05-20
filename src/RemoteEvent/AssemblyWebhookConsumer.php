<?php

namespace App\RemoteEvent;

use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use App\Repository\TranscriptionRepository;
use Psr\Log\LoggerInterface;

#[AsRemoteEventConsumer('assembly')]
final class AssemblyWebhookConsumer implements ConsumerInterface
{
  public function __construct(
    private LoggerInterface $logger,
    private TranscriptionRepository $transcriptionRepository
  ) {
  }

  public function consume(RemoteEvent $event): void
  {
    $this->logger->info('Received Assembly AI event: ' . json_encode($event->getPayload()));

    $transcription = $this->transcriptionRepository->findOneBy(['providerId' => $event->getPayload()['transcript_id']]);
    $transcription->setStatus($event->getPayload()['status']);

    $this->transcriptionRepository->getEntityManager()->flush();
  }
}

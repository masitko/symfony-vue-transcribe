<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SaveTranscription implements ProcessorInterface
{
  public function __construct(
    #[Autowire('@api_platform.doctrine.orm.state.persist_processor')]
    private readonly ProcessorInterface $processor,
    private Security $security
  ) {
  }

  public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
  {
    $uploadedFile = $context['request']->files->get('file');
    if (!$uploadedFile) {
      throw new BadRequestHttpException('"file" is required');
    }
    $data->setFile($uploadedFile);
    $data->setUser($this->security->getUser());
    return $this->processor->process($data, $operation, $uriVariables, $context);
  }
}

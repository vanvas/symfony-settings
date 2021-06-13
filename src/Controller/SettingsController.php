<?php
declare(strict_types=1);

namespace Vim\Settings\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vim\Settings\Entity\ArraySettings;
use Vim\Settings\Entity\BooleanSettings;
use Vim\Settings\Entity\ChoseSettings;
use Vim\Settings\Entity\StringSettings;
use Vim\Settings\Entity\TextSettings;
use Vim\Settings\Repository\SettingsRepository;
use Vim\Settings\Service\SettingsService;

class SettingsController
{
    public function index(SettingsService $settingsService): JsonResponse
    {
        return new JsonResponse([
            'data' => $settingsService->getAll(),
        ]);
    }

    public function save(
        Request $request,
        SettingsService $settingsService,
        SettingsRepository $settingsRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        foreach (json_decode($request->getContent(), true)['data'] as $data) {
            $settings = $settingsRepository->findOneByCode($data['code']);
            if (null === $settings) {
                switch ($data['type']) {
                    case 'string':
                        $settings = new StringSettings();
                        break;
                    case 'text':
                        $settings = new TextSettings();
                        break;
                    case 'boolean':
                        $settings = new BooleanSettings();
                        break;
                    case 'array':
                        $settings = new ArraySettings();
                        break;
                    case 'choice':
                        $settings = new ChoseSettings();
                        break;
                    default:
                        throw new \LogicException('Not found mapper for "' . $data['string'] . '"');
                }

                $settings->setCode($data['code']);
            }

            $settings->setValue($data['value']);
            $em->persist($settings);
            $em->flush();
            $settingsService->refreshCache($data['code']);
        }

        return new JsonResponse();
    }
}

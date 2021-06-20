<?php
declare(strict_types=1);

namespace Vim\Settings\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vim\Settings\Entity\AbstractSettings;
use Vim\Settings\Repository\SettingsRepository;
use Vim\Settings\Service\SettingsCollection;
use Vim\Settings\Service\SettingsServiceInterface;

class SettingsController
{
    public function index(
        Request $request,
        SettingsRepository $settingsRepository,
        SettingsServiceInterface $settingsService,
        SettingsCollection $settingsCollection
    ): JsonResponse
    {
        $filter = $request->get('filter', []);

        $qb = $settingsRepository->createQueryBuilder('s');
        if ($filterCode = $filter['code'] ?? null) {
            $qb
                ->andWhere('s.code LIKE :code')
                ->setParameter('code', $filterCode . '%')
            ;
        }

        return new JsonResponse([
            'data' => array_map(
                static function (AbstractSettings $settingEntity) use ($settingsService, $settingsCollection) {
                    return array_merge(
                        $settingsCollection->one($settingEntity->getCode())->toArray(),
                        ['value' => $settingsService->get($settingEntity->getCode())]
                    );
                },
                $qb->getQuery()->getResult()
            ),
        ]);
    }

    public function save(Request $request, SettingsServiceInterface $settingsService): JsonResponse
    {
        foreach (json_decode($request->getContent(), true)['data'] as $data) {
            $settingsService->save($data['code'], $data['type'], $data['value']);
        }

        return new JsonResponse();
    }
}

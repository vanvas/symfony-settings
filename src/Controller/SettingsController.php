<?php
declare(strict_types=1);

namespace Vim\Settings\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vim\Settings\Dto\SettingInterface;
use Vim\Settings\Service\SettingsCollection;
use Vim\Settings\Service\SettingsServiceInterface;

class SettingsController
{
    public function index(
        Request $request,
        SettingsServiceInterface $settingsService,
        SettingsCollection $settingsCollection
    ): JsonResponse
    {
        $filter = $request->get('filter', []);
        $settings = isset($filter['code']) ? $settingsCollection->codeStartsWith($filter['code']) : $settingsCollection->all();

        return new JsonResponse([
            'data' => array_map(
                static function(SettingInterface $setting) use ($settingsService) {
                    return array_merge(
                        $setting->toArray(),
                        ['value' => $settingsService->get($setting->getCode())]
                    );
                },
                $settings
            ),
        ]);
    }

    public function save(Request $request, SettingsServiceInterface $settingsService): JsonResponse
    {
        foreach (json_decode($request->getContent(), true)['data'] as $data) {
            $settingsService->save($data['code'], $data['value']);
        }

        return new JsonResponse();
    }
}

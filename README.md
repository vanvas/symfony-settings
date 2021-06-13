## Installation

```shell
composer require vim/symfony-settings
```

## Configuration

`config/packages/settings.yaml`
```yaml
settings:
  pool: app.cache.settings
  settings:
    #- { type: string, code: some_code_string, name: 'Some title - string or number', value: '' }
    #- { type: array, code: some_code_array, name: 'Some title - array', value: [] }
    #- { type: boolean, code: some_code_boolean, name: 'Some title - boolean', value: true }
    #- { type: choice, code: some_code_choice, name: 'Some title - choice', value: 1, choiceList: [1, 2, 3] }
```

`config/packages/cache.yaml`
```yaml
framework:
  cache:
    pools:
      #...
      app.cache.settings:
        adapter: cache.adapter.redis
        default_lifetime: 2592000 # 30 days
```

`api/config/bundles.php`
```PHP
<?php
return [
  // ...
  Vim\Settings\SettingsBundle::class => ['all' => true],
];
```

`config/packages/doctrine.yaml`
```yaml
doctrine:
  # ...
  orm:
    # ...
    mappings:
      # ...
      SettingsBundle:
        is_bundle: true
        type: annotation
        prefix: 'Vim\Settings\Entity'
```

`config/routes.yaml`
```yaml
settings_list:
  path: /api/v1/settings
  methods: GET
  controller: Vim\Settings\Controller\SettingsController::index
settings_save:
  path: /api/v1/settings
  methods: POST
  controller: Vim\Settings\Controller\SettingsController::save
```

## Example

```PHP
/** @var \Vim\Settings\Service\SettingsService $settingsService **/
$value = $settingsService->get('some_code');
```

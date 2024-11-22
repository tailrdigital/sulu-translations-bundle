# Sulu Translations Bundle

This package provides a Sulu admin panel for managing your website translations. 

The package includes a custom [Symfony translation provider](https://symfony.com/doc/current/translation.html#translation-providers) which stores your translations in a database.

## Demo

https://github.com/user-attachments/assets/65278818-b376-4c6b-816d-9dceab905140

## Installation

```sh
composer require tailrdigital/sulu-translations-bundle
```

#### Register the bundle
Make sure the bundle is activated in `config/bundles.php`:

```php
Tailr\SuluTranslationsBundle\SuluTranslationsBundle::class => ['all' => true]
```

#### Register new admin routes

You need to manually register the failed queue admin controller routes in the file `config/routes_admin.yaml`.

```yaml
# config/routes_admin.yaml

tailr_translations:
  resource: '@SuluTranslationsBundle/Presentation/Controller/Admin'
  type: attribute
  prefix: /admin/api
```

#### Add node dependency

Register an additional module in your admin's node dependencies via `assets/admin/package.json`:

```json
{
  "dependencies": {
    "sulu-translations-bundle": "file:node_modules/@sulu/vendor/tailrdigital/sulu-translations-bundle/assets/admin"
  }  
}
```

Make sure to load the additional node module in your admin's `assets/admin/index.js` or `assets/admin/app.js` file:

```js
import 'sulu-translations-bundle';
```

#### Recompile your admin assets

```sh
cd /app/assets/admin
npm install
npm run watch
```

## Configuration

#### Configuring the provider

You have to add the database provider to the Symfony translator configuration. This is an example configuration for the `config/packages/translation.yaml` file.

```yaml
# config/packages/translation.yaml

framework:
    translator:
        providers:
            tailr_database:
                dsn: 'database://default'
                domains: [ 'messages' ]
                locales: [ 'en', 'fr', 'nl' ]
```

#### Doctrine DBAL connection

The hostname in the DSN is actually your Doctrine DBAL connection name: `database://<dbal_connection_name>`. 
If you want to store your translations in a separate database, you could [configure a new DBAL connection](https://symfony.com/doc/current/doctrine/multiple_entity_managers.html) and use the connection name in the DSN of the database translation provider. 

#### Export format

If you want to export your translations via the administrator panel, you should define the format or extension which is used for your translation files. 

```yaml
# config/packages/sulu_translations.yaml

sulu_translations:
  export_format: 'csv'
```

#### Permissions

Make sure you've set the correct permissions in the Sulu admin for this package. Go to _Settings > User Roles_ and enable the permissions (tailr_translations) you need. Afterwards you could find the translations view/panel via _Settings > Manage translations_.

## Usage

First make sure the database table `tailr_translations` is created by running the command below.

```sh
bin/console tailr:sulu-translations:setup
```

If you don't have local translations files (e.g. CSV) you can generate them by using the command below.

```sh
bin/console translation:extract --force --domain=messages --format=csv <locale>
```

Once you have local translation files, you can export them to the database by using command below.

```sh
bin/console translation:push tailr_database 
```

Next you can update the translations via the Sulu admin panel. 

Once you are done, you can export the translations back to the translations files by using the command below or clicking the _Export translations_ button via the Sulu admin panel.

```sh
bin/console translation:pull tailr_database --force --format csv
```

After pulling the translations, you may need to clear the cache(s).

```sh
bin/console cache:clear
bin/websiteconsole cache:clear
```


## Known limitations

Only tested and used with CSV format.

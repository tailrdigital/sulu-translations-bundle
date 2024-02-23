# Sulu Translations Bundle

This package provides a Sulu admin panel for managing your website or applications translations.

```
framework:
    translator:
        providers:
            database:
                dsn: 'database://phpro_sulu_translations'
                domains: [ 'messages' ]
                locales: [ 'nl', 'en', 'fr' ]
```

```
# This will generate files in translations/ folder with structure messages.nl.csv
bin/console translation:extract --force --domain=messages --format=csv nl

# Push new translations to the database based on translation files in the translations-folder
bin/console translation:push database (--delete-missing)

# Pulling database translations to the CSVs again:
bin/console translation:pull database --force --format csv
```

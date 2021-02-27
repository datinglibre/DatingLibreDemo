# DatingLibreDemo

![Build Status](https://github.com/datinglibre/datinglibredemo/actions/workflows/datinglibre.yml/badge.svg)

The code used to power [DatingLibre.com](https://datinglibre.com).

This repository is used as a demonstration on how to customize the [reference implementation](https://github.com/datinglibre/DatingLibre) using sex and relationship categories.

Other demo branches:
- [![Build Status](https://github.com/datinglibre/datinglibredemo/actions/workflows/datinglibre.yml/badge.svg?branch=alternative)](https://github.com/datinglibre/DatingLibreDemo/actions?query=branch%3Aalternative) [alternative](https://github.com/datinglibre/DatingLibreDemo/tree/alternative) categories based on D/s relationships.

Please open pull requests against the [reference implementation](https://github.com/datinglibre/DatingLibre) and the [dating-libre-app-bundle](https://github.com/datinglibre/datinglibre-app-bundle).

For further information on how to customize the [datinglibre-app-bundle](https://github.com/datinglibre/datinglibre-app-bundle), see the [DatingLibre Wiki](https://github.com/datinglibre/DatingLibre/wiki/Customization) and [how to override a Symfony bundle](https://symfony.com/doc/current/bundles/override.html).

The following files were changed/created in order to create the demo site:

### Configuration
- `config/packages/dating_libre.yaml`

### Controllers
- `src/Controller/UserProfileEditController.php`
- `src/Controller/UserSearchIndexController.php`

### Forms
- `src/Form/ProfileForm.php`
- `src/Form/ProfileFormType.php`
- `src/Form/RequirementsForm.php`
- `src/Form/RequirementsFormType.php`

### Twig templates
- `templates/bundles/DatingLibreAppBundle/user/profile/index.html.twig`
- `templates/bundles/DatingLibreAppBundle/user/profile/edit.html.twig`
- `templates/bundles/DatingLibreAppBundle/user/search/index.html.twig`

### Routes

The routes were imported from the [datinglibre-app-bundle](https://github.com/datinglibre/datinglibre-app-bundle), 
and, where necessary, overridden with routes to our own controllers above.

- `config/routes/datinglibre_demo.xml`

### Tests

The behat features in `features/` were updated using find+replace. 

## Credits

The `countries.sql` `regions.sql` and `cities.sql` files were created by processing geographical data from [GeoNames](https://www.geonames.org/)
and are licensed under [Attribution 4.0 International (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/).

The `src/Security/LoginFormAuthenticator.php` is based on Symfony documentation [How to build a login form](https://symfony.com/doc/current/security/form_login_setup.html)
and is licensed under [Creative Commons BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/).

## Licence

Copyright 2020-2021 DatingLibre.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

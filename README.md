[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abacaphiliac/doctrine-orm-diagnostics-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abacaphiliac/doctrine-orm-diagnostics-module/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/abacaphiliac/doctrine-orm-diagnostics-module/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/abacaphiliac/doctrine-orm-diagnostics-module/?branch=master)
[![Build Status](https://travis-ci.org/abacaphiliac/doctrine-orm-diagnostics-module.svg?branch=master)](https://travis-ci.org/abacaphiliac/doctrine-orm-diagnostics-module)

# abacaphiliac/doctrine-orm-diagnostics-module
Integration of doctrine/doctrine-orm-module and zendframework/zenddiagnostics.
Provides ZF2 Diagnostic checks of Doctrine ORM Connection(s) and Schema. 

# Installation
```bash
composer require abacaphiliac/doctrine-orm-diagnostics-module
```

# Usage
Add `Abacaphiliac\DoctrineORMDiagnosticsModule` to your application module config.

## What can I do if I don't use `orm_default`?
Create your own `CheckConnectionFactory` (a very simple factory) and override the 
`doctrine.orm_diagnostics.connection` service in your application's `service_manager.factories` config.

# Sample Output
```
[vagrant@vagrant]$ php public/index.php diag -v --debug
  OK   ZF: PHP Version: Current PHP version is 5.6.5
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
       '5.6.5'
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  OK   DoctrineORMModule: Database Connection: Doctrine\DBAL\Connections\MasterSlaveConnection
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
       array (
         'slave' => 'connected',
         'master' => 'connected',
       )
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 FAIL  DoctrineORMModule: ORM Validate Schema: Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
       array (
         0 => '[Mapping]  OK - The mapping files are correct.',
         1 => '[Database] FAIL - The database schema is not in sync with the current mapping file.',
       )
       ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 failures, 0 warnings, 2 successful checks.
```

# Dependencies
See [composer.json](composer.json).

## Contributing
```
composer update && vendor/bin/phing
```

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

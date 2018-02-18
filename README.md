# SixCRM SDK for PHP

## Directions for Implementors 
 (tk)
 
 
## Directions for Developers

Checkout the project, and install dependencies via [Composer](https://getcomposer.org/).
```bash
git clone git@github.com:sixcrm/TransactionalAPISDK-PHP.git
cd TransactionalAPISDK-PHP
composer install
```

### Unit Tests

```bash
make test
```

This depends on Docker and will run tests against PHP 5.6, 7.0, 7.1 and 7.2. Tests can also be without docker, with the locally installed copy of PHP as such:

```bash
./vendor/bin/phpunit --verbose --debug
```

### Integration Tests

```bash
make test-integration
```
These tests depend on the presence of a valid six-config.json file and will make actual calls to the SixCRM Transactional API. 


### Build
```bash
make package
```
This will package the SDK with all dependencies and store it as build/artifacts/sixcrm-transactional-api-sdk.zip.


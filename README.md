Sutunam Sentry for Magento 2
===================================

# Description

Add Sentry script in Magento's frontend.  
Add a Magento controller to act as a [tunnel](https://docs.sentry.io/platforms/javascript/troubleshooting/#dealing-with-ad-blockers) for Sentry.

## Installation
### Composer

Add Sutunam Composer repository:

```json
"repositories": {
    "sutunam": {
        "type": "composer",
        "url": "https://composer.sutunam.com/m2/"
    }
}
```

Require the package:

```bash
composer require sutunam/sentry:^0.1
```

### Configurations

Configurations are located in `Stores > Configuration > Web > Sentry`.

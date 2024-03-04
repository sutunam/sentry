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

### Message Queues

This module uses a message queue to send request to Sentry in the background.
This prevents the tunnel to overload the webserver.
To run the consumer, use this command :

```bash
bin/magento queue:consumers:start sentryRequestHandler
```

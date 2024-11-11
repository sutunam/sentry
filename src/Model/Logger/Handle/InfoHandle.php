<?php
/*
 * Sutunam
 *
 * @copyright Copyright (c) 2023 Sutunam (https://www.sutunam.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sutunam\Sentry\Model\Logger\Handle;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger as MonologLogger;

/**
 * Class InfoHandle
 * Handles the logging of info level messages for the Sentry module.
 */
class InfoHandle extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/sutunam_sentry_info.log';

    /**
     * @var int
     */
    protected $loggerType = MonologLogger::INFO;
}

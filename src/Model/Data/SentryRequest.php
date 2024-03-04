<?php

/*
 * Sutunam
 *
 * @copyright Copyright (c) 2024 Sutunam (https://www.sutunam.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sutunam\Sentry\Model\Data;

class SentryRequest
{
    /**
     * @var string
     */
    protected $envelope;

    /**
     * Get envelope content.
     *
     * @return string
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * Set envelope content.
     *
     * @param string $envelope
     * @return void
     */
    public function setEnvelope($envelope)
    {
        $this->envelope = $envelope;
    }
}

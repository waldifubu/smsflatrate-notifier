<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// namespace App\Transport;
namespace Symfony\Component\Notifier\Bridge\Smsflatrate;

use Symfony\Component\Notifier\Exception\IncompleteDsnException;
use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;

/**
 * @author Waldemar Dell <waldi.dell@gmail.com>
 *
 * @experimental in 5.2
 */
final class SmsflatrateTransportFactory extends AbstractTransportFactory
{
    /**
     * @return SmsflatrateTransport
     */
    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        // @TODO: DEV!
        /*if ('smsflatrate' !== $scheme) {
            throw new UnsupportedSchemeException($dsn, 'smsflatrate', $this->getSupportedSchemes());
        }*/

        $authToken = $dsn->getOption('key');
        $from = $dsn->getOption('from');
        $type = $dsn->getOption('type');
        $flash = (int)$dsn->getOption('flash', 0);
        $host = 'default' === $dsn->getHost() ? null : $dsn->getHost();
        $status = (int)$dsn->getOption('status', 0);

        if (!$authToken) {
            throw new IncompleteDsnException('Missing mandatory APIKEY token.', $dsn->getOriginalDsn());
        }

        return (new SmsflatrateTransport(
            $authToken, $from, $type, $flash, $status, $this->client, $this->dispatcher
        ))->setHost($host);
    }

    protected function getSupportedSchemes(): array
    {
        return ['smsflatrate'];
    }
}

<?php

namespace Kubectyl\Repositories\Kuber;

use Kubectyl\Models\Server;
use Webmozart\Assert\Assert;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use Kubectyl\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonCommandRepository extends DaemonRepository
{
    /**
     * Sends a command or multiple commands to a running server instance.
     *
     * @throws \Kubectyl\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function send(array|string $command): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/commands', $this->server->uuid),
                [
                    'json' => ['commands' => is_array($command) ? $command : [$command]],
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}

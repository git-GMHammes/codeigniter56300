<?php

namespace App\Filters\v1;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\v1\Log\ResourceModel;

class LogRequestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        try {
            $model = new ResourceModel();

            $data = [
                'event' => 'api.request',
                'level' => 'info',
                'method' => $request->getMethod(),
                'ip' => $request->getIPAddress(),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'application' => 'api',
                'payload' => json_encode([
                    'uri' => (string) $request->getUri(),
                    'full_url' => current_url(),
                    'query' => $request->getGet(),
                    'body' => $request->getPost() ?: $request->getJSON(true),
                ]),
                'meta' => json_encode([
                    'referrer' => $request->getHeaderLine('Referer'),
                ]),
                'tags' => json_encode([
                    'filter',
                    'request',
                    strtolower($request->getMethod()),
                ]),
                'user_id' => null,
                'resource_type' => null,
                'resource_id' => null,
                'actor_type' => 'guest',
            ];

            $model->insert($data);

            log_message('info', 'Log: ' . $request->getMethod() . ' ' . $request->getUri());

        } catch (\Throwable $e) {
            log_message('error', 'Erro: ' . $e->getMessage());
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        log_message('info', 'After executado! ');
        return $response;
    }
}
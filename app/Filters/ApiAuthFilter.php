<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. Returning false will stop the route from executing.
     *
     * @param RequestInterface $request
     * @param null|string[]    $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            return service('response')
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
                ])
                ->setStatusCode(401);
        }

        // Extract token from Bearer format
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return service('response')
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Format token tidak valid. Gunakan format: Bearer {token}',
                ])
                ->setStatusCode(401);
        }

        $token = $matches[1];

        // Validate token
        $pesertaModel = new \App\Models\PesertaModel();
        $peserta = $pesertaModel->validateToken($token);

        if (!$peserta) {
            return service('response')
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak valid atau telah kadaluarsa.',
                ])
                ->setStatusCode(401);
        }

        // Store peserta data in request for use in controllers
        $request->peserta = $peserta;

        return $request;
    }

    /**
     * We don't have anything to do here.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param null|string[]     $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}

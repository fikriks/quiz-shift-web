<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'html', 'text'];

    /**
     * Authentication instance
     *
     * @var \App\Libraries\Authentication
     */
    protected $auth;

    /**
     * Current user data
     *
     * @var array|null
     */
    protected $currentUser = null;

    /**
     * Data to be passed to views
     *
     * @var array
     */
    protected $data = [];

    /**
     * Session instance
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Initialize session
        $this->session = service('session');

        // Check if user is logged in
        $this->currentUser = $this->session->get('user');

        // Share data to all views
        $this->shareDataToViews();
    }

    /**
     * Share common data to all views
     */
    protected function shareDataToViews()
    {
        // Share current user
        $this->data['currentUser'] = $this->currentUser;
        
        // Share user photo path
        if ($this->currentUser) {
            $userPhoto = $this->currentUser['foto_profil'] ?? 'user-1.jpg';
            $photoPath = base_url('assets/images/profile/' . $userPhoto);
            if ($userPhoto !== 'user-1.jpg' && file_exists(WRITEPATH . 'uploads/profile/' . $userPhoto)) {
                $photoPath = base_url('uploads/profile/' . $userPhoto);
            }
            $this->data['userPhoto'] = $photoPath;
        }

        // Share site config
        $this->data['siteName'] = 'QuizShift';
        $this->data['siteDescription'] = 'Aplikasi QuizShift untuk Penentuan Level Bahasa Inggris dengan Algoritma Fisher-Yates';

        // Share notifications (if any)
        $this->data['notifications'] = $this->getNotifications();
    }

    /**
     * Get notifications for current user
     */
    protected function getNotifications()
    {
        if (!$this->currentUser) {
            return [];
        }

        // For now, return empty array
        // In the future, this can fetch from database
        return [];
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->currentUser) {
            $response = redirect()->to(site_url('login'))->with('error', 'Anda harus login terlebih dahulu');
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException($response);
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role)
    {
        $this->requireAuth();

        if ($this->currentUser['hak_akses'] !== $role) {
            $response = redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException($response);
        }
    }
    
    /**
     * Get user role display name
     */
    protected function getRoleDisplayName($role)
    {
        $roleNames = [
            'ADMIN' => 'Administrator',
            'INSTRUKTUR' => 'Instruktur'
        ];

        return $roleNames[$role] ?? $role;
    }

    /**
     * Require one of multiple roles
     */
    protected function requireAnyRole($roles)
    {
        $this->requireAuth();

        if (!in_array($this->currentUser['hak_akses'], $roles)) {
            $response = redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException($response);
        }
    }

    /**
     * JSON response helper
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($statusCode);
    }

    /**
     * Success JSON response
     */
    protected function jsonSuccess($message = 'Success', $data = null, $statusCode = 200)
    {
        $response = [
            'status'  => 'success',
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $this->jsonResponse($response, $statusCode);
    }

    /**
     * Error JSON response
     */
    protected function jsonError($message = 'Error', $statusCode = 400, $errors = null)
    {
        $response = [
            'status'  => 'error',
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->jsonResponse($response, $statusCode);
    }

    /**
     * Paginate helper
     */
    protected function paginate($model, $perPage = 10, $group = 'default')
    {
        $data = $model->paginate($perPage, $group);

        return [
            'data'  => $data,
            'pager' => $model->pager->links(),
            'total' => $model->pager->getTotal()
        ];
    }

    /**
     * Upload file helper
     */
    protected function uploadFile($fieldName, $uploadPath = 'uploads/', $allowedTypes = [], $maxSize = 2048)
    {
        $file = $this->request->getFile($fieldName);

        if ($file->isValid() && !$file->hasMoved()) {
            // Validate file type
            if (!empty($allowedTypes) && !in_array($file->getExtension(), $allowedTypes)) {
                return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
            }

            // Validate file size
            if ($file->getSize() > $maxSize * 1024) {
                return ['success' => false, 'message' => 'Ukuran file terlalu besar'];
            }

            // Generate unique filename
            $newName = $file->getRandomName();

            // Move file
            if ($file->move(WRITEPATH . $uploadPath, $newName)) {
                return [
                    'success' => true,
                    'filename' => $newName,
                    'filepath' => $uploadPath . $newName
                ];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupload file'];
            }
        }

        return ['success' => false, 'message' => 'Tidak ada file yang diupload'];
    }

  }
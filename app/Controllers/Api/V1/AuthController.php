<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RefreshTokenModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $refreshTokenModel;
    protected $jwtSecret;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->refreshTokenModel = new RefreshTokenModel();
        $this->jwtSecret = env('JWT_SECRET');
        helper(['jwt']);
    }

    public function register()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[60]',
            'last_name' => 'permit_empty|max_length[60]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'permit_empty|is_unique[users.phone]',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'first_name' => $this->request->getJSON()->first_name,
            'last_name' => $this->request->getJSON()->last_name ?? '',
            'email' => $this->request->getJSON()->email,
            'phone' => $this->request->getJSON()->phone ?? null,
            'password' => $this->request->getJSON()->password,
            'status' => 'active'
        ];

        if ($userId = $this->userModel->insert($data)) {
            $user = $this->userModel->find($userId);
            $tokens = $this->generateTokens($user);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $this->formatUser($user),
                    'tokens' => $tokens
                ]
            ])->setStatusCode(ResponseInterface::HTTP_CREATED);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Registration failed'
        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function login()
    {
        $rules = [
            'identifier' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $identifier = $this->request->getJSON()->identifier;
        $password = $this->request->getJSON()->password;

        $user = $this->userModel->findByEmailOrPhone($identifier);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid email/phone or password.')->withInput();
        }
         {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid credentials'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if ($user['status'] === 'blocked') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Account blocked'
            ])->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $tokens = $this->generateTokens($user);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $this->formatUser($user),
                'tokens' => $tokens
            ]
        ]);
    }

    public function refresh()
    {
        $refreshToken = $this->request->getJSON()->refresh_token ?? null;

        if (!$refreshToken) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Refresh token required'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $tokenData = $this->refreshTokenModel->validateToken($refreshToken);

        if (!$tokenData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid or expired refresh token'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $user = $this->userModel->find($tokenData['user_id']);

        if (!$user || $user['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found or inactive'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $this->refreshTokenModel->revokeToken($refreshToken);

        $tokens = $this->generateTokens($user);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Tokens refreshed',
            'data' => [
                'tokens' => $tokens
            ]
        ]);
    }

    public function me()
    {
        $user = $this->getUserFromToken();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'user' => $this->formatUser($user)
            ]
        ]);
    }

    public function logout()
    {
        $user = $this->getUserFromToken();

        if ($user) {
            $this->refreshTokenModel->revokeUserTokens($user['id']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    private function generateTokens($user)
    {
        $now = time();
        $accessExpire = $now + (int)env('JWT_ACCESS_EXPIRE', 3600);

        $accessPayload = [
            'iss' => base_url(),
            'sub' => $user['id'],
            'iat' => $now,
            'exp' => $accessExpire,
            'user' => $this->formatUser($user)
        ];

        $accessToken = JWT::encode($accessPayload, $this->jwtSecret, 'HS256');
        $refreshToken = $this->refreshTokenModel->createToken($user['id']);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $accessExpire,
            'token_type' => 'Bearer'
        ];
    }

    private function getUserFromToken()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (!$header || !preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return null;
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return $this->userModel->find($decoded->sub);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatUser($user)
    {
        unset($user['password_hash']);
        return $user;
    }
}
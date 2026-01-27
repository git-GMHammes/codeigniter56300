<?php

namespace App\Services\v1\ForgotPassword;

use App\Services\v1\BaseManagerService;
use App\Models\v1\UserCustomer\ResourceModel as UserCustomerModel;
use App\Models\v1\ForgotPassword\ResourceModel as ForgotPasswordModel;

class ManagerService extends BaseManagerService
{
    protected $userCustomerModel;
    protected $forgotPasswordModel;

    public function __construct()
    {
        $this->userCustomerModel = new UserCustomerModel();
        $this->forgotPasswordModel = new ForgotPasswordModel();
    }

    # ========================================================================
    # ETAPA 1: VALIDAR EMAIL
    # ========================================================================

    # Busca usuário pelo email na tabela user_customer
    # 
    # @param array $data ['mail' => string]
    # @return array
    public function validateEmail(array $data): array
    {
        helper('sanitizer');

        // Sanitiza o email (trim + lowercase)
        $mail = sanitize_email($data['mail']);

        // Busca usuário pelo email
        $user = $this->userCustomerModel
            ->where('mail', $mail)
            ->first();

        // Email não encontrado
        if (!$user) {
            return $this->errorResponse('Email não cadastrado no sistema.');
        }

        // Retorna dados para próxima etapa
        return $this->successResponse([
            'id' => $user['id'],
            'user_id' => $user['user_id'],
            'name' => $user['name'] ?? null
        ]);
    }

    # ========================================================================
# ETAPA 2: ENVIAR LINK DE RECUPERAÇÃO
# ========================================================================

    /**
     * Gera token e salva na tabela user_password_resets
     * 
     * @param array $data ['id' => int, 'user_id' => int]
     * @return array
     */
    public function sendLink(array $data): array
    {
        $id = $data['id'];
        $userId = $data['user_id'];

        // Verifica se usuário existe com os dados informados
        $user = $this->userCustomerModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$user) {
            return $this->errorResponse('Usuário não encontrado.');
        }

        // Gera token único (64 caracteres hex)
        $token = bin2hex(random_bytes(32));

        // Cria hash do token para salvar no banco (segurança)
        $tokenHash = hash('sha256', $token);

        // Define expiração (1 hora)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Captura IP e User-Agent (auditoria)
        $request = service('request');
        $ipAddress = $request->getIPAddress();
        $userAgent = $request->getUserAgent()->getAgentString();

        // Salva na tabela user_password_resets
        $inserted = $this->forgotPasswordModel->insert([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'ip_address' => $ipAddress,
            'user_agent' => substr($userAgent, 0, 255)
        ]);

        if (!$inserted) {
            return $this->errorResponse('Erro ao gerar token de recuperação.');
        }

        // Retorna token original (será enviado por email)
        return $this->successResponse([
            'token' => $token,
            'expires_at' => $expiresAt,
            'user_id' => $userId
        ]);
    }

}

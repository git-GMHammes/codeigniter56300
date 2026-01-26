<?php

namespace App\Controllers\API\v1\ForgotPassword;

use App\Controllers\API\v1\BaseManagerController;
use App\Models\v1\UserCustomer\ResourceModel as UserCustomerModel;

class ManagerController extends BaseManagerController
{
    protected $userCustomerModel;

    public function __construct()
    {
        parent::__construct();
        $this->userCustomerModel = new UserCustomerModel();
    }

    # ========================================================================
    # VALIDAÇÃO DE EMAIL
    # ========================================================================

    /**
     * POST /api/v1/forgot-password/validate-email
     *
     * Verifica se o email existe na tabela user_customer
     *
     * Body: { "mail": "teste@teste.com" }
     *
     * Retorno:
     * - 200: Email encontrado (retorna id e user_id)
     * - 404: Email não encontrado
     * - 422: Dados inválidos
     */
    public function validateEmail()
    {
        // Pegar dados do body JSON
        $json = $this->request->getJSON(true);
        $mail = $json['mail'] ?? null;

        // Validar se email foi fornecido
        if (empty($mail)) {
            return $this->apiResponse->validationError(
                ['mail' => 'O campo email é obrigatório.'],
                'Dados de entrada inválidos.'
            );
        }

        // Validar formato do email
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return $this->apiResponse->validationError(
                ['mail' => 'O formato do email é inválido.'],
                'Dados de entrada inválidos.'
            );
        }

        // Buscar usuário pelo email
        $user = $this->userCustomerModel
            ->where('mail', $mail)
            ->first();

        // Email não encontrado
        if (!$user) {
            return $this->apiResponse->notFound('Email não cadastrado no sistema.');
        }

        // Email encontrado - retornar dados necessários para próxima etapa
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            [
                'id' => $user['id'],
                'user_id' => $user['user_id'],
                'name' => $user['name'] ?? null,
            ],
            'Email validado com sucesso.'
        );
    }

    # ========================================================================
    # ENVIO DO LINK DE RECUPERAÇÃO
    # ========================================================================

    /**
     * POST /api/v1/forgot-password/send-link
     *
     * Gera token temporário e envia link de recuperação
     *
     * Body: { "id": 1, "user_id": 10 }
     *
     * Retorno:
     * - 200: Link enviado com sucesso (retorna token temporário)
     * - 404: Usuário não encontrado
     * - 422: Dados inválidos
     */
    public function sendLinkChangePassword()
    {
        // Pegar dados do body JSON
        $json = $this->request->getJSON(true);
        $id = $json['id'] ?? null;
        $userId = $json['user_id'] ?? null;

        // Validar campos obrigatórios
        $errors = [];

        if (empty($id)) {
            $errors['id'] = 'O campo id é obrigatório.';
        }

        if (empty($userId)) {
            $errors['user_id'] = 'O campo user_id é obrigatório.';
        }

        if (!empty($errors)) {
            return $this->apiResponse->validationError($errors, 'Dados de entrada inválidos.');
        }

        // Verificar se usuário existe com os dados informados
        $user = $this->userCustomerModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$user) {
            return $this->apiResponse->notFound('Usuário não encontrado.');
        }

        // Gerar token temporário único
        $token = $this->generateUniqueToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // TODO: Na segunda etapa, salvar token na tabela user_management
        // $this->userManagementModel->update($userId, [
        //     'reset_token' => $token,
        //     'reset_token_expires_at' => $expiresAt
        // ]);

        // TODO: Na segunda etapa, enviar email com link de recuperação
        // $resetLink = base_url("reset-password?token={$token}");
        // $this->emailService->sendPasswordResetLink($user['mail'], $resetLink);

        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            [
                'token' => $token,
                'expires_at' => $expiresAt,
                'user_id' => $userId,
                // 'reset_link' => base_url("reset-password?token={$token}"),
            ],
            'Token de recuperação gerado com sucesso.'
        );
    }

    # ========================================================================
    # MÉTODOS AUXILIARES
    # ========================================================================

    /**
     * Gera um token único para recuperação de senha
     *
     * @return string
     */
    private function generateUniqueToken(): string
    {
        return bin2hex(random_bytes(32)); // 64 caracteres hexadecimais
    }
}
<?php

namespace App\Controllers\API\v1\ForgotPassword;

use App\Controllers\API\v1\BaseManagerController;
use App\Requests\v1\ForgotPassword\ValidateEmailRequest;
use App\Requests\v1\ForgotPassword\SendLinkRequest;
use App\Services\v1\ForgotPassword\ManagerService;

class ManagerController extends BaseManagerController
{
    protected $validateEmailRequest;
    protected $sendLinkRequest;
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->validateEmailRequest = new ValidateEmailRequest();
        $this->sendLinkRequest = new SendLinkRequest();
        $this->service = new ManagerService();
    }

    # ========================================================================
    # ETAPA 1: VALIDAR EMAIL
    # ========================================================================

    /**
     * POST /api/v1/forgot-password/validate-email
     */
    public function validateEmail()
    {
        // Valida entrada via Request
        $validation = $this->validateRequest($this->validateEmailRequest, 'validateEmail');
        if ($validation['hasError']) {
            return $validation['response'];
        }

        // Chama Service
        $result = $this->service->validateEmail($validation['data']);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data'],
            'Email validado com sucesso.'
        );
    }

    # ========================================================================
    # ETAPA 2: ENVIAR LINK DE RECUPERAÇÃO
    # ========================================================================

    /**
     * POST /api/v1/forgot-password/send-link
     */
    public function sendLinkChangePassword()
    {
        // Valida entrada via Request
        $validation = $this->validateRequest($this->sendLinkRequest, 'validateSend');
        if ($validation['hasError']) {
            return $validation['response'];
        }

        // Chama Service
        $result = $this->service->sendLink($validation['data']);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data'],
            'Token de recuperação gerado com sucesso.'
        );
    }

}
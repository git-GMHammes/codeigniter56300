<?php

/**
 * Rotas da API - Módulo Forgot Password (v1)
 *
 * @package    App\Routes\v1
 * @author     Gustavo - HABILIDADE
 * @version    1.0.0
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

# ========================================================================
# RECUPERAÇÃO DE SENHA (Rotas públicas - sem autenticação)
# ========================================================================

# POST /api/v1/forgot-password/validate-email
# Valida se o email existe no sistema
$routes->post('forgot-password/validate-email', 'API\v1\ForgotPassword\ManagerController::validateEmail', ['as' => 'api.v1.forgot-password.validate-email']);

# POST /api/v1/forgot-password/send-link
# Gera token temporário e envia link de recuperação
$routes->post('forgot-password/send-link', 'API\v1\ForgotPassword\ManagerController::sendLinkChangePassword', ['as' => 'api.v1.forgot-password.send-link']);
============================================================
        FLUXO DE ARQUITETURA - LARAVEL API
============================================================

FLUXO DE EXECUCAO:

  [1] Request      -> Valida entrada HTTP
  [2] Controller   -> Orquestra o fluxo (max 10 linhas)
  [3] Service      -> Executa logica de negocio
  [4] Model        -> Interage com banco de dados

============================================================

html/
└── app
    ├── Controllers # [2] Orquestracao (max 10 linhas)
    │   ├── API
    │   │   └── v1 # Versao 1 da API
    │   │       └── UserManagement
    │   │           └── ManagerController.php
    │   ├── BaseController.php
    │   └── Home.php
    │
    │
    ├── Models # [4] Acesso ao banco de dados
    │   ├── v1 # Versao 1 da API
    │   │   ├── UserCustomer
    │   │   │   └── ResourceModel.php
    │   │   └── UserManagement
    │   │       ├── ResourceModel.md
    │   │       └── ResourceModel.php
    │   └── .gitkeep
    │
    │
    ├── Requests # [1] Validacao de entrada (HTTP)
    │   └── v1 # Versao 1 da API
    │       └── UserManagement
    │           └── StoreRequest.php
    ├── Routes
    │   └── API
    │       └── v1 # Versao 1 da API
    │           └── UserManagement
    │               └── api_routes.php
    │
    │
    └── Services # [3] Logica de negocio
        └── v1 # Versao 1 da API
            └── UserManagement
                └── ManagerService.php

============================================================
Gerado em: 2025-11-23 20:49:12
Diretorio base: /var/www/html
Exibindo apenas: app/Controllers, app/Models, app/Requests, app/Routes, app/Services
============================================================

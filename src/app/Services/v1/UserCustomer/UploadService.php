<?php

namespace App\Services\v1\UserCustomer;

use App\Models\v1\UserCustomer\FileModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class UploadService
{
    protected string $baseRelativePath = 'uploads/UserCustomer'; // caminho relativo usado no DB
    protected string $baseAbsolutePath; // caminho absoluto (WRITEPATH)
    protected FileModel $fileModel;

    public function __construct()
    {
        $this->baseAbsolutePath = WRITEPATH . $this->baseRelativePath; // WRITEPATH = writable/
        $this->fileModel = new FileModel();
    }

    /**
     * Salva múltiplos arquivos para um user_customer.
     * @param int $userCustomerId
     * @param UploadedFile[] $files  array de CodeIgniter UploadedFile
     * @param array $options opcional: ['timestamp' => 'YYYYMMDDHHMMSS']
     * @param \CodeIgniter\Database\BaseConnection|null $db conexão opcional para garantir participação em transação
     * @return array ['success' => bool, 'data' => array|null, 'errors' => array|null]
     */
    public function storeMultiple(int $userCustomerId, array $files, array $options = [], $db = null): array
    {
        $saved = [];
        $errors = [];
        $timestamp = $options['timestamp'] ?? date('YmdHis');

        foreach ($files as $index => $file) {
            // somente UploadedFile válidos
            if (!($file instanceof UploadedFile) || !$file->isValid()) {
                $errors[] = [
                    'index' => $index,
                    'message' => 'Arquivo inválido ou não enviado.',
                    'clientName' => $file instanceof UploadedFile ? $file->getClientName() : null
                ];
                continue;
            }

            try {
                // gerar diretorio: writable/uploads/UserCustomer/id_123/20251215...
                $dir = $this->baseAbsolutePath . DIRECTORY_SEPARATOR . 'id_' . $userCustomerId . DIRECTORY_SEPARATOR . $timestamp;
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                // gerar nome único
                $originalName = $file->getClientName();
                $ext = $file->getClientExtension() ?: $file->getExtension();
                $safePrefix = preg_replace('/[^a-z0-9_]/i', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $uuid = bin2hex(random_bytes(16)); // 32 chars hex
                $filename = sprintf('up_%s_%s.%s', $safePrefix, $uuid, $ext);

                // mover arquivo
                $file->move($dir, $filename);

                // path salvo no DB (relativo)
                $storedPath = $this->baseRelativePath . '/id_' . $userCustomerId . '/' . $timestamp . '/' . $filename;

                $fullFilePath = $dir . DIRECTORY_SEPARATOR . $filename;

                // opcional: calcular checksum sha256
                $checksum = null;
                if (is_file($fullFilePath)) {
                    $checksum = hash_file('sha256', $fullFilePath);
                }

                // grava metadados no banco
                $insertData = [
                    'user_customer_id' => $userCustomerId,
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'stored_path' => $storedPath,
                    'uuid' => $uuid,
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'checksum' => $checksum,
                ];

                if ($db !== null) {
                    // usa a conexão fornecida (participa da mesma transação)
                    $db->table('user_customer_files')->insert($insertData);
                } else {
                    // comportamento atual via model
                    $this->fileModel->insert($insertData);
                }

                $saved[] = $insertData;
            } catch (\Throwable $e) {
                // tenta remover arquivo caso já tenha sido movido
                if (isset($dir, $filename)) {
                    @unlink($dir . DIRECTORY_SEPARATOR . $filename);
                }
                $errors[] = [
                    'index' => $index,
                    'message' => $e->getMessage(),
                    'clientName' => $originalName ?? null
                ];
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'data' => $saved, 'errors' => $errors];
        }

        return ['success' => true, 'data' => $saved, 'errors' => null];
    }
}
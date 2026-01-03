<?php

# Sanitizer Helper
# Funções para sanitizar e limpar dados de entrada

if (!function_exists('sanitize_cpf')) {
    # Remove máscara do CPF (pontos e traço)
    # @param string|null $cpf
    # @return string|null
    function sanitize_cpf(?string $cpf): ?string
    {
        if (empty($cpf)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $cpf);
    }
}

if (!function_exists('sanitize_cnpj')) {
    # Remove máscara do CNPJ (pontos, traços e barra)
    # @param string|null $cnpj
    # @return string|null
    function sanitize_cnpj(?string $cnpj): ?string
    {
        if (empty($cnpj)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $cnpj);
    }
}

if (!function_exists('sanitize_zip_code')) {
    # Remove máscara do CEP (traço)
    # @param string|null $zipCode
    # @return string|null
    function sanitize_zip_code(?string $zipCode): ?string
    {
        if (empty($zipCode)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $zipCode);
    }
}

if (!function_exists('sanitize_phone')) {
    # Remove máscara de telefone (parênteses, traços, espaços)
    # @param string|null $phone
    # @return string|null
    function sanitize_phone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $phone);
    }
}

if (!function_exists('sanitize_string')) {
    # Remove espaços extras e caracteres especiais de strings
    # @param string|null $string
    # @return string|null
    function sanitize_string(?string $string): ?string
    {
        if (empty($string)) {
            return null;
        }

        // Remove espaços extras
        $string = preg_replace('/\s+/', ' ', trim($string));

        return $string;
    }
}

if (!function_exists('sanitize_username')) {
    # Sanitiza username (remove espaços e converte para minúsculas)
    # @param string|null $username
    # @return string|null
    function sanitize_username(?string $username): ?string
    {
        if (empty($username)) {
            return null;
        }

        // Remove espaços e converte para minúsculas
        return strtolower(trim(preg_replace('/\s+/', '', $username)));
    }
}

if (!function_exists('sanitize_email')) {
    # Sanitiza email (trim, lowercase)
    # @param string|null $email
    # @return string|null
    function sanitize_email(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        return strtolower(trim($email));
    }
}

if (!function_exists('sanitize_numeric')) {
    # Remove tudo que não for número
    # @param string|null $value
    # @return string|null
    function sanitize_numeric(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $value);
    }
}

if (!function_exists('sanitize_decimal')) {
    # Remove tudo exceto números, ponto e vírgula (para valores monetários)
    # @param string|null $value
    # @return string|null
    function sanitize_decimal(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Remove tudo exceto números, ponto e vírgula
        $value = preg_replace('/[^0-9.,]/', '', $value);

        // Substitui vírgula por ponto (padrão brasileiro para decimal)
        return str_replace(',', '.', $value);
    }
}

if (!function_exists('sanitize_array')) {
    # Sanitiza todos os valores de um array
    # @param array $data
    # @param array $fields Campos específicos e suas funções de sanitização
    # @return array
    # Exemplo: sanitize_array($data, ['cpf' => 'sanitize_cpf', 'usuario' => 'sanitize_username'])
    function sanitize_array(array $data, array $fields = []): array
    {
        foreach ($fields as $field => $sanitizer) {
            if (isset($data[$field]) && function_exists($sanitizer)) {
                $data[$field] = $sanitizer($data[$field]);
            }
        }

        return $data;
    }
}

if (!function_exists('trim_all')) {
    # Aplica trim em todos os valores string de um array (recursivo)
    # @param array $data
    # @return array
    function trim_all(array $data): array
    {
        return array_map(function ($item) {
            if (is_string($item)) {
                return trim($item);
            }
            if (is_array($item)) {
                return trim_all($item);
            }
            return $item;
        }, $data);
    }
}

if (!function_exists('sanitize_datetime')) {
    /**
     * Sanitiza strings variadas de data/tempo para "Y-m-d H:i:s".
     * Se impossível, retorna date("Y-m-d H:i:s") atual (no fuso configurado).
     */
    function sanitize_datetime(string $input, string $tz = 'America/Sao_Paulo'): ?string
    {
        $tzOut = new DateTimeZone($tz);
        $now = new DateTimeImmutable('now', $tzOut);

        $finish = function (DateTimeInterface $dt) use ($tzOut): string {
            return (new DateTimeImmutable('@' . $dt->getTimestamp()))
                ->setTimezone($tzOut)
                ->format('Y-m-d H:i:s');
        };

        $trimmed = trim(preg_replace('/\s+/', ' ', $input));

        // Rejeitar apenas ano (ex: "2025") ou ano-mês (ex: "2025-04")
        if (preg_match('/^\d{4}(-\d{2})?$/', $trimmed)) {
            return null;
        }

        // 0) Ajustes rápidos
        // - segundos com 1 dígito → zero-pad
        if (preg_match('/\b(\d{1,2}:\d{2}:\d{1})\b/', $trimmed, $m)) {
            $parts = explode(':', $m[1]);
            $parts[2] = str_pad($parts[2], 2, '0', STR_PAD_LEFT);
            $trimmed = str_replace($m[1], implode(':', $parts), $trimmed);
        }
        // - extrair primeiro H:i:s se houver junk junto
        if (preg_match('/\b\d{1,2}:\d{2}:\d{2}\b/', $trimmed, $hmss)) {
            $timeOnly = $hmss[0];
        } else {
            $timeOnly = null;
        }

        // 0.5) Formato brasileiro d-m-Y ou d/m/Y (antes do strtotime)
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})(?:\s+(\d{1,2}):(\d{2}):(\d{2}))?$/', $trimmed, $m)) {
            $day = (int) $m[1];
            $month = (int) $m[2];
            $year = (int) $m[3];

            if (checkdate($month, $day, $year)) {
                if (isset($m[4])) {
                    // Com hora: 04-11-2025 14:30:00
                    $formatted = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, (int) $m[4], (int) $m[5], (int) $m[6]);
                } else {
                    // Sem hora: 04-11-2025
                    $formatted = sprintf('%04d-%02d-%02d 00:00:00', $year, $month, $day);
                }
                $dt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $formatted, $tzOut);
                if ($dt instanceof DateTimeImmutable) {
                    return $finish($dt);
                }
            }
        }

        // 1) Tentar parse direto (strtotime entende muitos formatos)
        $ts = strtotime($trimmed);
        if ($ts !== false) {
            return $finish((new DateTimeImmutable('@' . $ts))->setTimezone($tzOut));
        }

        // 2) Limpeza leve: preservar tokens úteis, remover palavras soltas
        $clean = preg_replace(
            '/(?<!\d)(st|nd|rd|th)(?!\w)/i',
            '', // ordinais
            preg_replace(
                '/\b(of|on|the|is|it|day|days|at|de|da|do|das|dos)\b/i',
                '',
                $trimmed
            )
        );
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        // 3) Lista de formatos conhecidos
        $formats = [
            // completos
            'Y-m-d\TH:i:sP',
            'c',
            'r',
            'Y-m-d H:i:s',
            'D, d M Y H:i:s T',
            'D M j G:i:s T Y',
            'Y-m-d',
            'Ymd',
            'm.d.y',
            'j, n, Y',
            // com mês por extenso
            'F j, Y g:i a',
            'F j, Y',
            'l jS \o\f F Y h:i:s A',
            'l jS \o\f F Y g:i:s A',
        ];

        foreach ($formats as $f) {
            $dt = DateTimeImmutable::createFromFormat($f, $clean, $tzOut);
            if ($dt instanceof DateTimeImmutable) {
                // Ajustar erros de parsing silenciosos
                $errors = DateTimeImmutable::getLastErrors();
                if (!$errors['warning_count'] && !$errors['error_count']) {
                    // Se formato tinha só data, normalizar para 00:00:00
                    $hasTime = preg_match('/[Hgh]:|:\d{2}/', $f) || str_contains($f, 'T');
                    if (!$hasTime) {
                        $dt = $dt->setTime(0, 0, 0);
                    }
                    return $finish($dt);
                }
            }
        }

        // 4) Casos específicos:

        // 4.1) "F j, Y is on a l" → extrair "F j, Y"
        if (preg_match('/\b(January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},\s*\d{4}\b/i', $trimmed, $m)) {
            $dt = DateTimeImmutable::createFromFormat('F j, Y', $m[0], $tzOut);
            if ($dt)
                return $finish($dt->setTime(0, 0, 0));
        }

        // 4.2) ISO compacto dentro do texto
        if (preg_match('/\b\d{4}-\d{2}-\d{2}(?:[ T]\d{2}:\d{2}:\d{2}(?:Z|[+\-]\d{2}:?\d{2})?)?\b/', $trimmed, $m)) {
            $ts = strtotime($m[0]);
            if ($ts !== false)
                return $finish((new DateTimeImmutable('@' . $ts))->setTimezone($tzOut));
        }

        // 4.3) Apenas hora presente → usar hoje
        if ($timeOnly) {
            [$H, $i, $s] = array_map('intval', explode(':', $timeOnly));
            $dt = $now->setTime($H, $i, $s);
            return $finish($dt);
        }

        // 4.4) “Wednesday the 15th” → usar dia + mês/ano atuais
        if (preg_match('/\b(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\b.*\b(\d{1,2})\b/i', $trimmed, $m)) {
            $day = (int) $m[1];
            if ($day >= 1 && $day <= 31) {
                $dt = $now->setDate((int) $now->format('Y'), (int) $now->format('m'), $day)->setTime(0, 0, 0);
                return $finish($dt);
            }
        }

        // 4.5) "03.10.01" → preferir m.d.y (compatível com manual)
        if (preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $trimmed)) {
            $dt = DateTimeImmutable::createFromFormat('m.d.y', $trimmed, $tzOut);
            if ($dt)
                return $finish($dt);
        }

        // 4.6) "10, 3, 2001"
        if (preg_match('/^\s*\d{1,2},\s*\d{1,2},\s*\d{4}\s*$/', $trimmed)) {
            $dt = DateTimeImmutable::createFromFormat('j, n, Y', $trimmed, $tzOut);
            if ($dt)
                return $finish($dt);
        }

        // 4.7) "20010310"
        if (preg_match('/^\d{8}$/', $trimmed)) {
            $dt = DateTimeImmutable::createFromFormat('Ymd', $trimmed, $tzOut);
            if ($dt)
                return $finish($dt);
        }

        // 4.8) RFC-style com TZ abreviado (ex.: "Sat Mar 10 17:16:18 MST 2001")
        if (preg_match('/^[A-Z][a-z]{2}\s+[A-Z][a-z]{2}\s+\d{1,2}\s+\d{2}:\d{2}:\d{2}\s+[A-Z]{2,5}\s+\d{4}$/', $trimmed)) {
            $ts = strtotime($trimmed);
            if ($ts !== false)
                return $finish((new DateTimeImmutable('@' . $ts))->setTimezone($tzOut));
        }

        // 4.9) Dentro de uma bagunça, tentar o primeiro padrão claro "Y-m-d H:i:s"
        if (preg_match('/\b\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}\b/', $trimmed, $m)) {
            $dt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $m[0], $tzOut);
            if ($dt)
                return $finish($dt);
        }

        // 5) Último recurso: tentar strtotime novamente após limpeza agressiva
        $aggressive = preg_replace('/[^A-Za-z0-9:+\-\/\.,\sTZ]/', ' ', $trimmed);
        $aggressive = trim(preg_replace('/\s+/', ' ', $aggressive));
        $ts = strtotime($aggressive);
        if ($ts !== false) {
            return $finish((new DateTimeImmutable('@' . $ts))->setTimezone($tzOut));
        }

        // 6) Falhou: data atual
        // return $now->format('Y-m-d H:i:s');
        return '';
    }

    if (!function_exists('sanitize_numero_processo')) {
        /**
         * Remove máscara do número do processo CNJ
         * Aceita: 0000000-00.0000.0.00.0000 ou 00000000000000000000
         * Retorna: 00000000000000000000 (20 dígitos)
         * 
         * @param string|null $numeroProcesso
         * @return string|null
         */
        function sanitize_numero_processo(?string $numeroProcesso): ?string
        {
            if (empty($numeroProcesso)) {
                return null;
            }

            // Remove tudo que não seja número
            return preg_replace('/[^0-9]/', '', $numeroProcesso);
        }
    }
}
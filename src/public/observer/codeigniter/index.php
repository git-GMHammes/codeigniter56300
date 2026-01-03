<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

/**
 * Leitor:
 *   ...\public\observer\flutter\index.php
 *
 * Lido (relativo):
 *   ...\public\frontend_flutter
 *
 * Modos:
 *   ?type=1  => linhas "caminho" + filhos com "-> "  (COM NUMERAÇÃO)
 *   ?type=2  => árvore com traços (padrão)          (SEM NUMERAÇÃO)
 *   (vazio)  => árvore com traços (padrão)
 */

// =========================
// CONFIG
// =========================
$TARGET_REL = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'app';
$TARGET_DIR = realpath($TARGET_REL);

// Ignorar por nome (pasta ou arquivo)
$IGNORE_NAMES = [
    '.',
    '..',
    '.git',
    '.idea',
    '.vscode',
    '.env',
    '.htaccess',
    'Boot',
];

// (Opcional) ignorar por extensão (sem ponto). vazio = não ignora extensão nenhuma
$IGNORE_EXTS = [
    // 'log',
];

// (Opcional) mostrar somente estas extensões (sem ponto). vazio = mostra tudo
$ONLY_EXTS = [
    // 'dart','yaml','json','md','png','jpg','jpeg','webp','svg','ttf','otf'
];

// =========================
// TYPE (1 ou 2)
// =========================
$type = isset($_GET['type']) ? (int) $_GET['type'] : 2;
if ($type !== 1 && $type !== 2)
    $type = 2;

// =========================
// DEBUG + VALIDATION
// =========================
echo "LEITOR (index.php): " . __FILE__ . PHP_EOL;
echo "ALVO (relativo)   : " . $TARGET_REL . PHP_EOL;
echo "ALVO (realpath)   : " . ($TARGET_DIR ?: '(realpath falhou)') . PHP_EOL;
echo "TYPE              : " . $type . PHP_EOL . PHP_EOL;

if ($TARGET_DIR === false || !is_dir($TARGET_DIR)) {
    echo "ERRO: Pasta alvo inválida." . PHP_EOL;
    exit(1);
}

// =========================
// HELPERS
// =========================
function shouldIgnore(string $name, bool $isDir, array $ignoreNames, array $ignoreExts, array $onlyExts): bool
{
    if (in_array($name, $ignoreNames, true))
        return true;
    if ($isDir)
        return false;

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (!empty($ignoreExts) && $ext !== '' && in_array($ext, $ignoreExts, true))
        return true;

    if (!empty($onlyExts)) {
        if ($ext === '')
            return true;
        return !in_array($ext, $onlyExts, true);
    }

    return false;
}

function listDirSorted(string $dir): array
{
    $items = scandir($dir);
    if ($items === false)
        return [];

    $dirs = [];
    $files = [];

    foreach ($items as $name) {
        if ($name === '.' || $name === '..')
            continue;
        $full = $dir . DIRECTORY_SEPARATOR . $name;
        if (is_dir($full))
            $dirs[] = $name;
        else
            $files[] = $name;
    }

    natcasesort($dirs);
    natcasesort($files);

    return array_merge(array_values($dirs), array_values($files));
}

function formatFileSuffix(string $name): string
{
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    return ($ext !== '') ? " (.$ext)" : " (sem extensão)";
}

/**
 * TYPE=2 (árvore com traços)
 */
function printTreeType2(string $dir, array $ignoreNames, array $ignoreExts, array $onlyExts, string $prefix = ''): void
{
    $items = listDirSorted($dir);

    $filtered = [];
    foreach ($items as $name) {
        $full = $dir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);
        if (shouldIgnore($name, $isDir, $ignoreNames, $ignoreExts, $onlyExts))
            continue;
        $filtered[] = $name;
    }

    $count = count($filtered);

    for ($i = 0; $i < $count; $i++) {
        $name = $filtered[$i];
        $full = $dir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);

        $isLast = ($i === $count - 1);
        $branch = $isLast ? "└── " : "├── ";
        $nextPrefix = $prefix . ($isLast ? "    " : "│   ");

        if ($isDir) {
            echo $prefix . $branch . $name . "/" . PHP_EOL;
            printTreeType2($full, $ignoreNames, $ignoreExts, $onlyExts, $nextPrefix);
        } else {
            echo $prefix . $branch . $name . formatFileSuffix($name) . PHP_EOL;
        }
    }
}

/**
 * TYPE=1 (linhas com caminho; filhos prefixados com "-> ") + NUMERAÇÃO 001, 002, ...
 */
function printType1Numbered(string $baseDir, array $ignoreNames, array $ignoreExts, array $onlyExts): void
{
    $baseDir = rtrim($baseDir, "\\/");
    $rootName = basename($baseDir);
    $counter = 0;

    $emit = function (string $line) use (&$counter) {
        $counter++;
        echo str_pad((string) $counter, 3, '0', STR_PAD_LEFT) . " -> " . $line . PHP_EOL;
    };

    $items = listDirSorted($baseDir);

    $rootFiltered = [];
    foreach ($items as $name) {
        $full = $baseDir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);
        if (shouldIgnore($name, $isDir, $ignoreNames, $ignoreExts, $onlyExts))
            continue;
        $rootFiltered[] = $name;
    }

    foreach ($rootFiltered as $name) {
        $full = $baseDir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);

        $rel = $rootName . DIRECTORY_SEPARATOR . $name;
        $relPrint = str_replace('/', '\\', $rel);

        if (!$isDir)
            $relPrint .= formatFileSuffix($name);

        $emit($relPrint);

        if ($isDir) {
            printType1ChildrenNumbered($baseDir, $full, $rootName, $ignoreNames, $ignoreExts, $onlyExts, $emit);
        }
    }
}

function printType1ChildrenNumbered(
    string $baseDir,
    string $currentDir,
    string $rootName,
    array $ignoreNames,
    array $ignoreExts,
    array $onlyExts,
    callable $emit
): void {
    $items = listDirSorted($currentDir);

    $filtered = [];
    foreach ($items as $name) {
        $full = $currentDir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);
        if (shouldIgnore($name, $isDir, $ignoreNames, $ignoreExts, $onlyExts))
            continue;
        $filtered[] = $name;
    }

    foreach ($filtered as $name) {
        $full = $currentDir . DIRECTORY_SEPARATOR . $name;
        $isDir = is_dir($full);

        $relPath = substr($full, strlen($baseDir) + 1);
        $rel = $rootName . DIRECTORY_SEPARATOR . $relPath;
        $relPrint = str_replace('/', '\\', $rel);

        if ($isDir) {
            $emit(" " . $relPrint);
            printType1ChildrenNumbered($baseDir, $full, $rootName, $ignoreNames, $ignoreExts, $onlyExts, $emit);
        } else {
            $emit(" " . $relPrint . formatFileSuffix($name));
        }
    }
}

// =========================
// RUN
// =========================
if ($type === 1) {
    printType1Numbered($TARGET_DIR, $IGNORE_NAMES, $IGNORE_EXTS, $ONLY_EXTS);
} else {
    echo basename($TARGET_DIR) . "/" . PHP_EOL;
    printTreeType2($TARGET_DIR, $IGNORE_NAMES, $IGNORE_EXTS, $ONLY_EXTS);
}

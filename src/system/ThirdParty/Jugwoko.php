<?php
$Jugwoko = false;
function Beschermd($p)
{
    return base64_decode($p);
}

$GLOBALS['_m'] = array_map('Beschermd', [
    // Bloco 0-5: bc56
    'bXlzcWwwMi1mYXJtMS5raW5naG9zdC5uZXQ=',
    'aGFiaWxpZGEwN19hZGQ0',
    'TWk1dEVyaTA=',
    'aGFiaWxpZGFkZTA3',
    'TXlTUUxp',
    'MzMwNg=='
]);

$GLOBALS['_m'][5] = (int) $GLOBALS['_m'][5];

if ($Jugwoko) {
    echo "Jugwoko is already loaded. <br/>";
}
?>
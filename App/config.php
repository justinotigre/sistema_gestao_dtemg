<?php
const DB_HOST='127.0.0.1';
const DB_NAME='sistema_especialistas_v3';
const DB_USER='root';
const DB_PASS='';
const APP_NAME='Sistema de Gestão de Especialistas';

function app_base_url(): string {
    $script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
    $pos = strpos($script, '/modulos/');
    if ($pos !== false) return substr($script, 0, $pos);
    $public = dirname($script);
    return $public === '/' || $public === '\\' || $public === '.' ? '' : rtrim($public, '/');
}
function app_url(string $path=''): string {
    $base = app_base_url();
    return $base . '/' . ltrim($path, '/');
}

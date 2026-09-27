<?php
session_start();
require_once __DIR__.'/db.php';

function login_user($username,$password){
 $s=db()->prepare("SELECT u.*,p.nome perfil_nome,p.nivel_acesso FROM utilizadores u JOIN perfis p ON p.id_perfil=u.id_perfil WHERE u.nome_utilizador=? AND u.estado='ATIVO'");
 $s->execute([$username]); $u=$s->fetch();
 if(!$u || !password_verify($password,$u['senha'])) return false;
 $_SESSION['user']=['id'=>$u['id_utilizador'],'nome'=>$u['nome_utilizador'],'perfil'=>$u['perfil_nome'],'nivel'=>$u['nivel_acesso'],'tema'=>$u['tema'],'foto'=>$u['foto']];
 db()->prepare("UPDATE utilizadores SET ultimo_acesso=NOW() WHERE id_utilizador=?")->execute([$u['id_utilizador']]);
 return true;
}
function require_login(){ if(empty($_SESSION['user'])){header('Location: ' . app_url('index.php')); exit;} }
function scope_sql(&$params,$alias='v'){
 $u=$_SESSION['user']; if($u['nivel']==='GLOBAL') return '1=1';
 if($u['nivel']==='RAMO'){
  $s=db()->prepare("SELECT id_ramo FROM utilizador_ramo WHERE id_utilizador=?");$s->execute([$u['id']]);
  $ids=array_map('intval',$s->fetchAll(PDO::FETCH_COLUMN)); if(!$ids)return '0=1';
  $params=array_merge($params,$ids); return "$alias.id_ramo IN(".implode(',',array_fill(0,count($ids),'?')).")";
 }
 $s=db()->prepare("SELECT id_unidade FROM utilizador_unidade WHERE id_utilizador=?");$s->execute([$u['id']]);
 $ids=array_map('intval',$s->fetchAll(PDO::FETCH_COLUMN)); if(!$ids)return '0=1';
 $params=array_merge($params,$ids); return "$alias.id_unidade IN(".implode(',',array_fill(0,count($ids),'?')).")";
}
function logout_user(){session_destroy();}

function app_public_path(string $relative=''): string {
    $public = realpath(__DIR__ . '/../public');
    if ($public === false) return '';
    $relative = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), DIRECTORY_SEPARATOR);
    return $public . DIRECTORY_SEPARATOR . $relative;
}
function app_file_url(string $relative=''): string {
    return app_url($relative);
}

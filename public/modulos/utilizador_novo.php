<?php
require_once __DIR__ . '/../../App/auth.php'; require_login();
$pdo=db(); $page_title='Novo Utilizador'; $erro='';
$perfis=$pdo->query("SELECT id_perfil,nome,nivel_acesso FROM perfis WHERE estado='ATIVO' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$ramos=$pdo->query("SELECT id_ramo,nome,sigla FROM ramo WHERE estado='ATIVO' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nome=trim($_POST['nome_utilizador']??''); $email=trim($_POST['email']??''); $senha=$_POST['senha']??''; $idPerfil=(int)($_POST['id_perfil']??0); $estado=$_POST['estado']??'ATIVO'; $ids=array_values(array_filter(array_map('intval',$_POST['ramos']??[])));
  if($nome===''||$senha===''||$idPerfil<=0) $erro='Preencha utilizador, senha e perfil.';
  else { try { $pdo->beginTransaction(); $q=$pdo->prepare('INSERT INTO utilizadores(nome_utilizador,senha,email,id_perfil,estado) VALUES(?,?,?,?,?)'); $q->execute([$nome,password_hash($senha,PASSWORD_DEFAULT),$email!==''?$email:null,$idPerfil,in_array($estado,['ATIVO','INATIVO'],true)?$estado:'ATIVO']); $id=(int)$pdo->lastInsertId();
      if(!empty($_FILES['foto']['name'])&&is_uploaded_file($_FILES['foto']['tmp_name'])){ $ext=strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION)); if(!in_array($ext,['jpg','jpeg','png','webp'],true)) throw new RuntimeException('Formato de foto não permitido.'); $dir=app_public_path('uploads/utilizadores'); if(!is_dir($dir)) mkdir($dir,0775,true); $file='utilizador_'.$id.'_'.bin2hex(random_bytes(5)).'.'.$ext; if(!move_uploaded_file($_FILES['foto']['tmp_name'],$dir.DIRECTORY_SEPARATOR.$file)) throw new RuntimeException('Não foi possível guardar a foto.'); $pdo->prepare('UPDATE utilizadores SET foto=? WHERE id_utilizador=?')->execute(['uploads/utilizadores/'.$file,$id]); } $qr=$pdo->prepare('INSERT INTO utilizador_ramo(id_utilizador,id_ramo) VALUES(?,?)'); foreach($ids as $rid)$qr->execute([$id,$rid]); $pdo->commit(); header('Location: utilizadores.php?criado=1'); exit; } catch(Throwable $e){ if($pdo->inTransaction())$pdo->rollBack(); $erro=str_contains($e->getMessage(),'Duplicate')?'O nome de utilizador ou e-mail já existe.':'Não foi possível criar o utilizador.'; } }
}
require __DIR__.'/../layout/header.php';
?>
<div class="module-page module-utilizador-form"><div class="form-panel panel"><div class="page-header"><div><h1>Novo Utilizador</h1><p>Crie o acesso e associe-o ao perfil e ramo correspondentes.</p></div></div><?php if($erro):?><div class="erro"><?=htmlspecialchars($erro)?></div><?php endif;?><form method="post" enctype="multipart/form-data" class="form-grid">
<label>Nome de utilizador<input required name="nome_utilizador" value="<?=htmlspecialchars($_POST['nome_utilizador']??'')?>"></label>
<label>E-mail<input type="email" name="email" value="<?=htmlspecialchars($_POST['email']??'')?>"></label>
<label>Senha<input required type="password" name="senha"></label>
<label>Perfil<select required name="id_perfil"><option value="">Selecione</option><?php foreach($perfis as $p):?><option value="<?=$p['id_perfil']?>"><?=htmlspecialchars($p['nome'])?></option><?php endforeach;?></select></label>
<div class="foto-edicao full"><div class="foto-edicao-preview"><span>U</span></div><div><strong>Fotografia</strong><small>Opcional. JPG, PNG ou WEBP.</small><input type="file" name="foto" accept="image/jpeg,image/png,image/webp"></div></div>
<label class="full">Ramos<select name="ramos[]" multiple size="4"><?php foreach($ramos as $r):?><option value="<?=$r['id_ramo']?>"><?=htmlspecialchars(($r['sigla']?:$r['nome']).' — '.$r['nome'])?></option><?php endforeach;?></select><small>Use Ctrl/Cmd para selecionar mais de um ramo.</small></label>
<label>Estado<select name="estado"><option>ATIVO</option><option>INATIVO</option></select></label>
<div class="full actions"><button class="btn btn-primary">Criar utilizador</button><a class="btn btn-secondary" href="utilizadores.php">Cancelar</a></div>
</form></div></div><?php require __DIR__.'/../layout/footer.php'; ?>

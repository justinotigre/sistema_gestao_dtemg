<?php
require_once __DIR__ . '/../../../App/auth.php'; require_login();
if(($_SESSION['user']['nivel']??'')!=='GLOBAL'){http_response_code(403);$page_title='Acesso restrito';require __DIR__.'/../../layout/header.php';echo '<div class="module-page"><div class="panel"><h1>Acesso restrito</h1><p>A gestão de siglas está disponível apenas ao administrador global.</p></div></div>';require __DIR__.'/../../layout/footer.php';exit;}
$pdo=db();$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{$pdo->beginTransaction();
  foreach(($_POST['ramo']??[]) as $id=>$sigla){$q=$pdo->prepare('UPDATE ramo SET sigla=? WHERE id_ramo=?');$q->execute([trim($sigla)!==''?trim($sigla):null,(int)$id]);}
  foreach(($_POST['quadro']??[]) as $id=>$sigla){$q=$pdo->prepare('UPDATE quadros_servico SET sigla=? WHERE id_quadro=?');$q->execute([trim($sigla)!==''?trim($sigla):null,(int)$id]);}
  foreach(($_POST['unidade']??[]) as $id=>$sigla){$q=$pdo->prepare('UPDATE unidades SET sigla=? WHERE id_unidade=?');$q->execute([trim($sigla)!==''?trim($sigla):null,(int)$id]);}
  foreach(($_POST['patente']??[]) as $id=>$sigla){$q=$pdo->prepare('UPDATE patentes SET sigla=? WHERE id_patente=?');$q->execute([trim($sigla)!==''?trim($sigla):null,(int)$id]);}
  $pdo->commit();$msg='Siglas atualizadas com sucesso.';
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$msg='Não foi possível atualizar as siglas.';}
}
$ramo=$pdo->query('SELECT id_ramo,nome,sigla FROM ramo ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);$quadro=$pdo->query('SELECT id_quadro,nome,sigla FROM quadros_servico ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);$unidade=$pdo->query('SELECT id_unidade,nome,sigla FROM unidades ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);$patente=$pdo->query('SELECT id_patente,nome,sigla FROM patentes ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$page_title='Siglas';require __DIR__.'/../../layout/header.php';
?>
<div class="module-page"><div class="page-header"><div><h1>Siglas de apresentação</h1><p>Defina a abreviatura usada nas listas e pesquisas. O nome completo continua disponível ao passar o cursor.</p></div></div><?php if($msg):?><div class="alert-sucesso"><?=htmlspecialchars($msg)?></div><?php endif;?><form method="post"><div class="config-grid">
<?php $grupos=[['Ramos','ramo',$ramo,'id_ramo'],['Quadros de serviço','quadro',$quadro,'id_quadro'],['Unidades','unidade',$unidade,'id_unidade'],['Patentes','patente',$patente,'id_patente']]; foreach($grupos as $grupo):?><section class="panel"><h3><?=htmlspecialchars($grupo[0])?></h3><?php foreach($grupo[2] as $item):?><label style="display:grid;grid-template-columns:1fr 90px;gap:10px;align-items:center;padding:9px 0;border-bottom:1px solid var(--borda)"><span title="<?=htmlspecialchars($item['nome'])?>"><?=htmlspecialchars($item['nome'])?></span><input name="<?=$grupo[1]?>[<?=$item[$grupo[3]]?>]" value="<?=htmlspecialchars($item['sigla']??'')?>" placeholder="Sigla"></label><?php endforeach;?></section><?php endforeach;?></div><div class="actions" style="margin-top:18px"><button class="btn btn-primary">Guardar siglas</button><a class="btn btn-secondary" href="configuracoes.php">Voltar</a></div></form></div>
<?php require __DIR__.'/../../layout/footer.php'; ?>

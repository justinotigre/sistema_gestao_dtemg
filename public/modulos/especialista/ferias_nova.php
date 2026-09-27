<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();
$pdo=db();
$id=(int)($_GET['id_especialista']??0);
if($id<=0){header('Location: especialistas.php');exit;}
$esp=$pdo->prepare("SELECT e.id_especialista,dp.nome_completo FROM especialistas e JOIN dados_pessoais dp ON dp.id_especialista=e.id_especialista WHERE e.id_especialista=?");$esp->execute([$id]);$especialista=$esp->fetch();
if(!$especialista){header('Location: especialistas.php');exit;}
$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $ano=(int)($_POST['ano']??date('Y'));$inicio=$_POST['data_inicio']??'';$fim=$_POST['data_fim']??'';$estado=$_POST['estado']??'PROGRAMADA';$obs=trim($_POST['observacoes']??'');
 if(!$inicio||!$fim||$fim<$inicio)$erro='Informe um período de férias válido.'; else {$dias=(int)((strtotime($fim)-strtotime($inicio))/86400)+1;$st=$pdo->prepare("INSERT INTO ferias(id_especialista,ano,data_inicio,data_fim,numero_dias,estado,observacoes) VALUES(?,?,?,?,?,?,?)");$st->execute([$id,$ano,$inicio,$fim,$dias,$estado,$obs]);header('Location: especialista.php?id='.$id.'#ferias');exit;}
}
$page_title='Nova Férias';require __DIR__.'/../../layout/header.php';
?>
<div class="module-page"><div class="panel form-panel"><h2>Novo período de férias</h2><p><strong><?=htmlspecialchars($especialista['nome_completo'])?></strong></p><?php if($erro):?><div class="erro"><?=htmlspecialchars($erro)?></div><?php endif;?><form method="post" class="form-grid"><label>Ano<input type="number" name="ano" value="<?=date('Y')?>" min="2000" max="2100" required></label><label>Data de início<input type="date" name="data_inicio" required></label><label>Data de fim<input type="date" name="data_fim" required></label><label>Estado<select name="estado"><option>PROGRAMADA</option><option>EM_CURSO</option><option>CONCLUIDA</option><option>CANCELADA</option></select></label><label class="full">Observações<textarea name="observacoes" rows="4"></textarea></label><div class="actions full"><button class="btn btn-primary">Guardar</button><a class="btn btn-secondary" href="especialista.php?id=<?=$id?>#ferias">Cancelar</a></div></form></div></div>
<?php require __DIR__.'/../../layout/footer.php'; ?>

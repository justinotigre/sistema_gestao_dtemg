<?php
require_once __DIR__ . '/../../App/auth.php';
require_login();
$pdo = db();
$page_title = 'Utilizadores';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$perfil = (int)($_GET['perfil'] ?? 0);
$ramo = (int)($_GET['ramo'] ?? 0);
$estado = $_GET['estado'] ?? '';

$params=[];
$where='1=1';
if ($pesquisa !== '') { $where .= ' AND (u.nome_utilizador LIKE ? OR u.email LIKE ?)'; $t='%'.$pesquisa.'%'; $params[]=$t; $params[]=$t; }
if ($perfil > 0) { $where .= ' AND u.id_perfil=?'; $params[]=$perfil; }
if ($estado !== '' && in_array($estado,['ATIVO','INATIVO'],true)) { $where .= ' AND u.estado=?'; $params[]=$estado; }
if ($ramo > 0) { $where .= ' AND EXISTS (SELECT 1 FROM utilizador_ramo urf WHERE urf.id_utilizador=u.id_utilizador AND urf.id_ramo=?)'; $params[]=$ramo; }

$perfis=$pdo->query("SELECT id_perfil,nome,nivel_acesso FROM perfis WHERE estado='ATIVO' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$ramos=$pdo->query("SELECT id_ramo,nome,sigla FROM ramo WHERE estado='ATIVO' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$sql="SELECT u.id_utilizador,u.nome_utilizador,u.email,u.foto,u.estado,u.data_criacao,u.ultimo_acesso,p.nome AS perfil_nome,p.nivel_acesso,
       GROUP_CONCAT(DISTINCT CONCAT(r.nome, CASE WHEN r.sigla IS NOT NULL AND r.sigla<>'' THEN CONCAT(' (',r.sigla,')') ELSE '' END) ORDER BY r.nome SEPARATOR ', ') AS ramos_nomes
       FROM utilizadores u JOIN perfis p ON p.id_perfil=u.id_perfil
       LEFT JOIN utilizador_ramo ur ON ur.id_utilizador=u.id_utilizador
       LEFT JOIN ramo r ON r.id_ramo=ur.id_ramo
       WHERE $where GROUP BY u.id_utilizador ORDER BY u.nome_utilizador";
$st=$pdo->prepare($sql);$st->execute($params);$utilizadores=$st->fetchAll(PDO::FETCH_ASSOC);
require __DIR__.'/../layout/header.php';
?>
<div class="module-page module-utilizadores">
<style>
.module-utilizadores .page-header{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:24px}.module-utilizadores .page-header h1{margin:0;font-size:34px}.module-utilizadores .page-header p{margin:7px 0 0;color:var(--suave)}
.module-utilizadores .filtros{background:var(--superficie);border:1px solid var(--borda);border-radius:14px;padding:18px 20px;margin-bottom:18px;box-shadow:var(--sombra)}
.module-utilizadores .filtros-grid{display:grid;grid-template-columns:minmax(220px,2fr) minmax(160px,1fr) minmax(160px,1fr) minmax(140px,1fr) minmax(170px,auto);gap:12px;align-items:end}.module-utilizadores .campo{display:flex;flex-direction:column;gap:6px;min-width:0}.module-utilizadores label{font-size:12px;font-weight:700}.module-utilizadores input,.module-utilizadores select{height:42px;width:100%;padding:0 11px;border:1px solid var(--borda);border-radius:9px;background:var(--superficie);color:var(--texto)}.module-utilizadores .filtro-acoes{display:grid;grid-template-columns:1fr 1fr;gap:8px}.module-utilizadores .filtro-acoes .btn{height:42px;padding:0 12px;white-space:nowrap}
.module-utilizadores .contador{margin:0 0 9px;color:var(--suave);font-size:13px}.module-utilizadores .tabela-container{background:var(--superficie);border:1px solid var(--borda);border-radius:14px;box-shadow:var(--sombra);overflow:auto}.module-utilizadores table{width:100%;min-width:880px;border-collapse:separate;border-spacing:0;table-layout:fixed}.module-utilizadores th,.module-utilizadores td{padding:11px 10px;border-bottom:1px solid var(--borda);text-align:left;vertical-align:middle;overflow:hidden;text-overflow:ellipsis}.module-utilizadores th{background:var(--azul-suave);color:var(--texto);font-size:12px;white-space:nowrap}.module-utilizadores th:nth-child(1),.module-utilizadores td:nth-child(1){width:58px;position:sticky;left:0;z-index:3;background:var(--superficie)}.module-utilizadores th:nth-child(2),.module-utilizadores td:nth-child(2){width:180px;position:sticky;left:58px;z-index:3;background:var(--superficie)}.module-utilizadores th:nth-child(3),.module-utilizadores td:nth-child(3){width:190px}.module-utilizadores th:nth-child(4),.module-utilizadores td:nth-child(4){width:160px}.module-utilizadores th:nth-child(5),.module-utilizadores td:nth-child(5){width:180px}.module-utilizadores th:nth-child(6),.module-utilizadores td:nth-child(6){width:90px}.module-utilizadores th:nth-child(7),.module-utilizadores td:nth-child(7){width:110px;position:sticky;right:0;z-index:3;background:var(--superficie)}.module-utilizadores thead th{position:sticky;top:0;z-index:5}.module-utilizadores thead th:nth-child(1),.module-utilizadores thead th:nth-child(2),.module-utilizadores thead th:nth-child(7){z-index:6;background:var(--azul-suave)}.module-utilizadores .foto-lista{width:38px;height:38px;object-fit:cover;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#edf2f5;border:1px solid var(--borda)}.module-utilizadores .acoes{display:flex;gap:5px;justify-content:center}.module-utilizadores .acoes .btn{height:31px;min-width:43px;padding:0 8px;font-size:11px;border-radius:7px}.module-utilizadores .estado{display:inline-flex;padding:4px 8px;border-radius:999px;font-size:10px;font-weight:750}.module-utilizadores .estado.ativo{background:#dff5e5;color:#18723a}.module-utilizadores .estado.inativo{background:#f3dddd;color:#9b2c2c}.module-utilizadores .ramos{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.module-utilizadores .vazio{padding:38px;text-align:center;color:var(--suave)}
html.modo-escuro .module-utilizadores th:nth-child(1),html.modo-escuro .module-utilizadores td:nth-child(1),html.modo-escuro .module-utilizadores th:nth-child(2),html.modo-escuro .module-utilizadores td:nth-child(2),html.modo-escuro .module-utilizadores th:nth-child(7),html.modo-escuro .module-utilizadores td:nth-child(7){background:var(--superficie)}
@media(max-width:1100px){.module-utilizadores .filtros-grid{grid-template-columns:2fr 1fr 1fr}.module-utilizadores .filtro-acoes{grid-column:1/-1;max-width:300px}}@media(max-width:700px){.module-utilizadores .page-header{align-items:flex-start;flex-direction:column}.module-utilizadores .filtros-grid{grid-template-columns:1fr}.module-utilizadores .filtro-acoes{grid-column:auto;max-width:none}}
</style>
<div class="page-header"><div><h1>Utilizadores</h1><p>Gestão, pesquisa e controlo dos utilizadores do sistema.</p></div><a href="utilizador_novo.php" class="btn btn-primary">+ Novo Utilizador</a></div>
<form method="get" class="filtros"><div class="filtros-grid">
<div class="campo"><label>Pesquisa</label><input type="text" name="pesquisa" value="<?=htmlspecialchars($pesquisa)?>" placeholder="Utilizador ou e-mail"></div>
<div class="campo"><label>Perfil</label><select name="perfil"><option value="0">Todos</option><?php foreach($perfis as $p):?><option value="<?=$p['id_perfil']?>" <?=$perfil===$p['id_perfil']?'selected':''?>><?=htmlspecialchars($p['nome'])?></option><?php endforeach;?></select></div>
<div class="campo"><label>Ramo</label><select name="ramo"><option value="0">Todos</option><?php foreach($ramos as $r):?><option value="<?=$r['id_ramo']?>" <?=$ramo===$r['id_ramo']?'selected':''?>><?=htmlspecialchars($r['sigla']?:$r['nome'])?></option><?php endforeach;?></select></div>
<div class="campo"><label>Estado</label><select name="estado"><option value="">Todos</option><option value="ATIVO" <?=$estado==='ATIVO'?'selected':''?>>Ativo</option><option value="INATIVO" <?=$estado==='INATIVO'?'selected':''?>>Inativo</option></select></div>
<div class="filtro-acoes"><button class="btn btn-primary" type="submit">Pesquisar</button><a class="btn btn-secondary" href="utilizadores.php">Limpar</a></div>
</div></form>
<div class="contador"><?=count($utilizadores)?> utilizador(es) encontrado(s)</div>
<div class="tabela-container"><?php if(!$utilizadores):?><div class="vazio">Nenhum utilizador encontrado.</div><?php else:?><table><thead><tr><th>Foto</th><th>Utilizador</th><th>E-mail</th><th>Perfil</th><th>Ramo</th><th>Estado</th><th>Ações</th></tr></thead><tbody>
<?php foreach($utilizadores as $u):?><tr>
<td><?php if(!empty($u['foto'])):?><img class="foto-lista" src="<?=htmlspecialchars(app_file_url($u['foto']))?>" alt="Foto"><?php else:?><span class="foto-lista"><?=htmlspecialchars(strtoupper(substr($u['nome_utilizador'],0,1)))?></span><?php endif;?></td>
<td title="<?=htmlspecialchars($u['nome_utilizador'])?>"><strong><?=htmlspecialchars($u['nome_utilizador'])?></strong></td>
<td title="<?=htmlspecialchars($u['email']??'')?>"><?=htmlspecialchars($u['email']??'—')?></td>
<td title="<?=htmlspecialchars($u['perfil_nome'])?>"><?=htmlspecialchars($u['perfil_nome'])?></td>
<td class="ramos" title="<?=htmlspecialchars($u['ramos_nomes']??'Sem ramo')?>"><?=htmlspecialchars($u['ramos_nomes']??'—')?></td>
<td><span class="estado <?=strtolower($u['estado'])?>"><?=htmlspecialchars($u['estado'])?></span></td>
<td class="acoes"><a class="btn btn-secondary" href="utilizador_editar.php?id=<?=$u['id_utilizador']?>">Editar</a></td>
</tr><?php endforeach;?></tbody></table><?php endif;?></div>
</div>
<?php require __DIR__.'/../layout/footer.php'; ?>

<?php
require_once __DIR__ . '/../../App/auth.php';
require_login();

$params=[];$where=scope_sql($params,'v');
$s=db()->prepare("SELECT COUNT(*) total,SUM(v.grupo_situacao='ATIVO') ativos,SUM(v.grupo_situacao='INATIVO') inativos,SUM(v.sexo='Masculino') masculino,SUM(v.sexo='Feminino') feminino FROM vw_especialistas_dashboard v WHERE $where");
# A view is created below if it does not exist.
try{$s->execute($params);$st=$s->fetch()?:[];}catch(Throwable $e){$st=['total'=>0,'ativos'=>0,'inativos'=>0,'masculino'=>0,'feminino'=>0];}
$st=array_merge(['total'=>0,'ativos'=>0,'inativos'=>0,'masculino'=>0,'feminino'=>0],$st);
$page_title = "Dashboard";
require __DIR__ . '/../layout/header.php';
?>

<div class="module-page module-dashboard">
<section class="dashboard-content"><h1>Dashboard</h1><section class="cards">
<?php foreach([['Total',$st['total']],['Ativos',$st['ativos']],['Inativos',$st['inativos']],['Masculino',$st['masculino']],['Feminino',$st['feminino']]] as $c):?><div class="card"><small><?=$c[0]?></small><strong><?=number_format((int)$c[1],0,',','.')?></strong></div><?php endforeach;?>
</section>
<section class="grid"><div class="panel"><h3>Distribuição por género</h3><div class="chart">Dados reais ligados ao backend</div></div><div class="panel"><h3>Quadro de serviço</h3><div class="chart">Permanente · Miliciano · Especial · Civil</div></div><div class="panel"><h3>Especialistas por patente</h3><div class="chart">Ordenação pela patente</div></div><div class="panel"><h3>Efetivo por ramo/unidade</h3><div class="chart">Filtrado pelo âmbito do utilizador</div></div></section>
<div class="consultas"><b>Consultas especiais:</b> Falecidos · Doentes — não apresentados diretamente no dashboard.</div>
</section>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

# Sistema de Gestão de Especialistas — V3

Estrutura migrada sem dependência de `estrutura anterior` ou `renderestrutura anterior.php`.

- Base de dados: `sistema_especialistas_v3`
- Entrada: `public/index.php`
- Redirecionamento após login: `public/sistema.php` → Dashboard
- Layout global: `public/layout/`
- CSS principal único: `public/assets/css/app.css`
- Módulos reais em `public/modulos/`
- Uploads preservados em `public/uploads/`
- O antigo `estrutura anterior` foi eliminado do pacote final.

## Instalação
1. Coloque a pasta `sistema_especialistas` em `C:/xampp/htdocs/`.
2. Importe `database/sistema_especialistas_v3.sql` apenas se precisar recriar a base; o sistema está configurado para `sistema_especialistas_v3`.
3. Abra `public/index.php` pelo endereço correspondente no XAMPP.
4. Mantenha as permissões de escrita em `public/uploads/`.

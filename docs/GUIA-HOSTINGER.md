# SEXCLICK 1.1 — instalação na Hostinger

O ecrã «A criar o seu site… Este processo está demorando mais do que o normal… Ver em Migrações» pertence à Hostinger. A captura não revela a causa exata do atraso. Alterar o tema não termina uma tarefa de criação/migração presa no fornecedor.

## O que está neste pacote

- `01-TEMA-sexclick.zip`: instalar em WordPress > Aparência > Temas > Adicionar > Carregar tema. Ativar SEXCLICK.
- `02-PLUGIN-sexclick-operations.zip`: instalar em WordPress > Plugins > Adicionar > Carregar plugin. Ativar depois de WooCommerce.
- Este documento: instruções. O ZIP exterior serve para transportar estes ficheiros; não é um tema nem um backup de migração.

## Instalação correta

1. É necessária uma instalação WordPress funcional, separada em `/loja` para preservar o site atual. Se já existe, utilizar essa instalação. Não enviar estes ficheiros para o assistente de Migrações: não contêm WordPress nem base de dados.
2. No painel WordPress, instalar e ativar WooCommerce do diretório oficial de plugins.
3. Instalar e ativar o tema e o plugin nos destinos indicados acima.
4. Abrir WooCommerce > SEXCLICK · Dreamlove. O novo assistente mostra as dependências e as páginas disponíveis. Selecionar a opção de página inicial apenas se pretende atribuir a página SEXCLICK e não existe outra atribuída. Clicar em «Preparar páginas e menu em falta».
5. As páginas existentes e o menu existente são preservados. Uma página em falta é criada; o assistente pode ser repetido sem duplicar páginas. Não altera moeda, impostos, credenciais, pagamentos ou envios.
6. Configurar Português de Portugal nas definições gerais do WordPress, EUR e dados reais do vendedor no WooCommerce. Selecionar a fotografia principal em Aparência > Personalizar > SEXCLICK — Imagem principal. As fotografias originais não constavam do código recuperado.
7. Configurar pagamentos, portes, faturação e o conector oficial Dreamlove. Validar os testes em COMPATIBILIDADE_DREAMLOVE.md antes de ativar vendas. O plugin de preparação não é o conector do fornecedor.

## Se a Hostinger continua em «A criar o seu site»

O código deste pacote só executa depois de WordPress estar instalado. Para diagnosticar a tarefa presa, é necessário o estado/detalhe de «Migrações» ou a mensagem técnica do fornecedor. Não voltar a enviar o mesmo pacote como backup completo. Não apagar o site, a base de dados ou os ficheiros existentes para tentar desbloquear a tarefa.

## HTTPS

O erro ERR_SSL_PROTOCOL_ERROR ocorre antes de WordPress carregar. A validade do certificado e o apontamento do domínio têm de ser verificados na infraestrutura. Este pacote não emite certificados e não desativa validação TLS. O assistente apenas indica se as URLs WordPress estão configuradas com HTTPS; isso não prova que o certificado público é válido.

## Referências do fornecedor

- https://www.hostinger.com/support/4455931-how-to-migrate-a-website-to-hostinger/
- https://www.hostinger.com/tutorials/how-to-install-wordpress-themes/
- https://www.hostinger.com/support/4469061-how-to-solve-err_ssl_protocol_error-at-hostinger/

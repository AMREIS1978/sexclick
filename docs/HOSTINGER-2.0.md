# SEXCLICK 2.0 — um único plugin de instalação

## Ficheiro a utilizar

`SEXCLICK-INSTALAR-NO-WORDPRESS-2.0.zip`

Este é um plugin para carregar **dentro de WordPress**, em Plugins > Adicionar > Carregar plugin. Não é um backup e não deve ser enviado para «Migrações / Ficheiro de base de dados SQL». Não inclui uma base de dados, credenciais ou clientes.

## Três passos

1. Numa instalação WordPress funcional (nova, sem encomendas, de preferência em `/loja` para preservar o site atual), abrir **Plugins > Adicionar > Carregar plugin**, escolher o ZIP e ativá-lo.
2. Abrir o novo menu **Instalar SEXCLICK** e clicar em **Preparar a minha loja**. WooCommerce, o tema e Operations são instalados a partir de ficheiros incluídos. Não é necessário carregar os três componentes separadamente. Se já existir SEXCLICK anterior, selecionar a opção de atualização; é criada primeiro uma cópia do seu código, mantendo imagens e opções.
A opção «Tornar a montra pública» desativa a página «Em breve» de WooCommerce. As compras continuam bloqueadas pelo Operations. Se não pretende mostrar a montra, desmarcar essa opção.

3. No fim, abrir **Ver o site**. Continuar em **WooCommerce > SEXCLICK · Dreamlove** para preparar o catálogo, pagamentos, envios e dados reais da loja. Escolher Português de Portugal nas definições WordPress e EUR nas definições WooCommerce. Carregar a fotografia principal no personalizador do tema.

O instalador executa oito pedidos independentes. Se uma ligação falhar, mostra o passo e a mensagem. «Retomar instalação» verifica o que já foi instalado; não cria duplicados. Não usa um processo de importação de milhares de produtos durante a instalação.

## Requisitos verificados

- PHP 8.1 ou superior e WordPress compatível com o WooCommerce incluído.
- WordPress individual, administrador com permissões para instalar plugins/temas.
- Escrita direta em wp-content e espaço livre para extrair WooCommerce.
- Integridade SHA-256 dos pacotes incluídos.
- Loja sem vendas ativadas ou encomendas existentes.

O instalador não substitui WooCommerce existente e não reduz a sua versão. Não altera as páginas, menus, imagens, impostos, portes ou pagamentos existentes. A criação de página inicial só ocorre se ainda não existir uma atribuída e a opção estiver selecionada.

## Se não consegue entrar no WordPress

Este ficheiro só executa depois de WordPress estar instalado. Uma falha na criação do site, no DNS, no certificado SSL ou num pedido de migração Hostinger não pode ser reparada por um plugin que ainda não está em execução. A captura de «Ficheiro de base de dados SQL» é um fluxo de recuperação de backup, não este instalador.

## O que falta para vender

Conector oficial e credenciais Dreamlove, catálogo e stock reais, pagamentos, portes, impostos/faturação, políticas e testes de encomenda/tracking. O código não inventa endpoints do fornecedor. As compras mantêm-se bloqueadas por defeito. O certificado HTTPS tem de ser válido no alojamento; o instalador não emite certificados nem pode garantir a segurança integral da loja.

## Código e limites

O tema mantém os produtos, preços, variantes, carrinho, checkout e conta nos mecanismos nativos de WooCommerce. Os estilos e a pesquisa foram revistos para telemóvel; os recursos usam versão baseada na data de alteração para evitar CSS/JS antigos em cache. As fotografias originais ausentes no pacote inicial não foram recuperadas.

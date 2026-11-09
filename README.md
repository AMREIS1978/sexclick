# SEXCLICK · Dreamlove

Projeto para abrir no Codex e instalar numa instância WordPress/WooCommerce.

**Estado: código preparado; ainda não instalado nem validado numa instância WooCommerce. Não está certificado pela Dreamlove.** A integração com o fornecedor está pendente da conta de revendedor e do conector/documentação técnica. O tema usa os mecanismos normais do WooCommerce para facilitar essa integração.

## Incluído
- Tema `wp-content/themes/sexclick`: loja responsiva, pesquisa, catálogo, categorias, páginas de produto e variantes, carrinho, checkout e conta pelo WooCommerce.
- `sexclick-operations`: painel de preparação e bloqueio de compras por defeito. Não é um conector Dreamlove.
- `integration-contract.php`: contrato interno para um adaptador futuro; zero endpoints inventados, zero chamadas ao fornecedor.
- `docs/COMPATIBILIDADE_DREAMLOVE.md`: todas as funcionalidades pedidas, evidências públicas e testes de aceitação.
- `docs/CODEX.md`: instruções para continuar o desenvolvimento.
- `versao-anterior/`: código recuperado da demonstração original e importador preliminar, fora dos plugins ativos.

## Instalação em staging
1. Criar um WordPress separado em `/loja`, com base de dados própria, HTTPS e backup do site atual.
2. Instalar WooCommerce a partir de WordPress.org, selecionar Português de Portugal/EUR e configurar as páginas necessárias.
3. Copiar as pastas deste projeto para `wp-content`, ativar o tema SEXCLICK e SEXCLICK Operations.
4. Configurar WooCommerce > Definições > Avançado: Loja, Carrinho, Finalizar compra, A minha conta. Usar páginas clássicas com `[woocommerce_cart]`, `[woocommerce_checkout]` e `[woocommerce_my_account]` enquanto se valida o conector.
5. Criar menus em Aparência > Menus. A imagem principal pode ser escolhida em Personalizar > SEXCLICK — Imagem principal. Não foram recuperadas as fotografias originais ausentes no primeiro pacote.
6. Instalar o conector fornecido/validado pela Dreamlove e cumprir a matriz de aceitação. Conservar apenas um sistema responsável por expedir encomendas.
7. Configurar pagamentos, zonas de envio, impostos, faturação e políticas. Rever traduções dos produtos para PT-PT.
8. Só depois dos testes: WooCommerce > SEXCLICK · Dreamlove > concluir verificações e ativar vendas.

Não existe sincronização “em tempo real” ativa neste repositório. Os intervalos, limites, transportadoras e operações disponíveis dependem do contrato e do conector. Carteira B2B e faturas do fornecedor não são as faturas de venda ao consumidor.

## Testes
`python3 scripts/check_project.py` verifica estrutura e ficheiros. `node --check wp-content/themes/sexclick/assets/shop.js` verifica JavaScript. Com PHP 8.1+: executar `php tests/contract-test.php`, `php tests/operations-test.php` e `find wp-content -name '*.php' -print0 | xargs -0 -n1 php -l`. GitHub Actions executa estes checks em PHP 8.1 e 8.3. Testes end-to-end dependem de uma loja de staging e do conector Dreamlove.

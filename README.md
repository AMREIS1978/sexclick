# SEXCLICK · Dreamlove

Projeto para abrir no Codex e instalar numa instância WordPress/WooCommerce.

**Estado: código testado com WordPress/WooCommerce em CI; instalação na Hostinger e integração Dreamlove pendentes. Não está certificado pela Dreamlove.** A integração com o fornecedor está pendente da conta de revendedor e do conector/documentação técnica. O tema usa os mecanismos normais do WooCommerce para facilitar essa integração.

## Incluído
- Tema `wp-content/themes/sexclick`: loja responsiva, pesquisa, catálogo, categorias, páginas de produto e variantes, carrinho, checkout e conta pelo WooCommerce.
- `sexclick-operations`: painel de preparação e bloqueio de compras por defeito. Não é um conector Dreamlove.
- `integration-contract.php`: contrato interno para um adaptador futuro; zero endpoints inventados, zero chamadas ao fornecedor.
- `docs/COMPATIBILIDADE_DREAMLOVE.md`: todas as funcionalidades pedidas, evidências públicas e testes de aceitação.
- `docs/CODEX.md`: instruções para continuar o desenvolvimento.
- `versao-anterior/`: código recuperado da demonstração original e importador preliminar, fora dos plugins ativos.

## Instalação sem migração

O ecrã «A criar o seu site… Ver em Migrações» é do serviço da Hostinger, não do tema. Estes ZIP não são backups completos e não incluem base de dados, WordPress ou WooCommerce. Não os enviar para Migrações. Consultar [GUIA-HOSTINGER.md](docs/GUIA-HOSTINGER.md). Depois de ativar WooCommerce, tema e plugin, abrir WooCommerce > SEXCLICK · Dreamlove e usar «Preparar páginas e menu em falta». O assistente não substitui páginas existentes nem ativa vendas.

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

## Instalação assistida na Hostinger

O alojamento é o existente: **sexclick.shop na Hostinger**. O GitHub é a origem do código, não outro alojamento. Preservar a página atual; instalar/testar numa instalação WordPress separada em `/loja`, com base de dados própria e backup atualizado.

Depois de instalar WordPress e definir `WP_ENVIRONMENT_TYPE=staging` nessa instalação, com SSH e WP-CLI disponíveis:

```bash
bash scripts/setup-staging.sh /caminho/real/da/instalacao/loja
```

O script compila traduções, copia apenas o tema e o módulo Operations, instala/ativa WooCommerce, instala PT-PT, cria páginas e menu em falta e mantém vendas bloqueadas. Não cria credenciais, não configura impostos/moradas fictícios e não substitui a página na raiz. Recusa produção e uma loja com vendas ativadas. Faz cópias das duas pastas existentes antes de atualizar; a cópia integral da base de dados deve existir previamente.

Sem SSH: usar os dois ZIP de instalação gerados por `python3 scripts/package.py`, em Aparência > Temas e Plugins > Adicionar plugin. Os pacotes são também produzidos pelo workflow **Loja real e pacotes** nos Artifacts de GitHub Actions, após passar os testes.

## Idiomas

O tema inclui traduções públicas EN/ES/FR/DE/IT, compiladas para MO por `scripts/compile-translations.py`; PT-PT é o idioma de origem. O seletor só aparece se o plugin multilingue disponibilizar `pll_the_languages`. Isto não inclui nem licencia o módulo WooCommerce multilingue. A Dreamlove deve confirmar a solução multilingue suportada para produtos, variantes, URLs, carrinho e checkout.

## Testes adicionais

O workflow `staging.yml` prepara WordPress/WooCommerce reais numa base de dados efémera de CI, testa páginas, stock e proteção de compras, e executa testes de navegador a 320/375/390/430/1280 px. Não contacta a Dreamlove nem processa pagamentos. O resultado dos testes reais deve ser consultado em Actions; definir um workflow não prova que passou.

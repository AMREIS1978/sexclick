# SEXCLICK · Hostinger 2.0

Instalador único para WordPress/WooCommerce no alojamento Hostinger existente.

**Ficheiro:** `SEXCLICK-INSTALAR-NO-WORDPRESS-2.0.zip` — instalar em **WordPress > Plugins > Adicionar > Carregar plugin**; ativar e abrir **Instalar SEXCLICK**. Não enviar para Migrações ou para um formulário que exige SQL.

[Instruções completas](docs/HOSTINGER-2.0.md) · [Compatibilidade Dreamlove](docs/COMPATIBILIDADE_DREAMLOVE.md)

## Incluído

- Instalador com oito passos independentes, retoma, validação SHA-256 dos pacotes e cópia do código SEXCLICK antes de atualizar.
- WooCommerce oficial incluído, tema responsivo SEXCLICK e plugin Operations.
- Produtos, variantes, carrinho, checkout e conta nativos de WooCommerce; proteção de lançamento e preparação Dreamlove.
- Tema PT-PT com traduções EN/ES/FR/DE/IT; catálogo multilingue depende do conector e da solução WooCommerce suportada.

## Gerar e testar

Obter o ZIP oficial WooCommerce em `vendor-cache/woocommerce.zip`, a partir de https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip . Executar `python3 scripts/package-hostinger.py`. Nenhum pacote de terceiros ou credencial é guardado no Git.

GitHub Actions testa instalação em WordPress/MySQL reais: upload do plugin, oito passos AJAX, segunda execução, permissões, integridade, proteção de vendas, pesquisa/produto e larguras de 320/375/390/430/1280 px. Consultar o resultado em Actions, não assumir que a presença de testes significa sucesso.

## Estado comercial

Não instalado na Hostinger por este código. DNS, SSL e criação inicial de WordPress são da infraestrutura. Dreamlove, pagamentos, envios, impostos/faturação e políticas dependem de configuração real e testes. Não há certificação Dreamlove nem sincronização ativa. As compras começam bloqueadas.

`versao-anterior/` e scripts antigos são preservados como histórico. O instalador 2.0 substitui os fluxos de entrega anteriores para uma instalação nova de WordPress.

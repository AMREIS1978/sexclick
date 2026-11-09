# Continuar no Codex

Abrir o repositório sexclick. Ler README.md e COMPATIBILIDADE_DREAMLOVE.md.

Prioridade: validar este tema numa loja WordPress/WooCommerce staging, depois instalar o conector Dreamlove que o utilizador fornecer. Não inventar endpoints ou assumir que a landing page é documentação de API.

Preservar: marca SEXCLICK, fonte sem serifa uniforme, português de Portugal, “carrinho”, navegação móvel inferior, ausência de botões sem ação. Nenhuma biblioteca de animação ou carrinho paralelo em localStorage. Não expor custos, credenciais de fornecedor ou moradas em logs públicos.

O tema usa modelos nativos do WooCommerce para produto, variantes, encomendas e checkout. Corrigir estilos e hooks, evitar cópias de templates WooCommerce quando possível. O plugin Operations só controla a preparação comercial e não substitui o conector oficial.

Para integração própria, implementar Gateway com a documentação contratada. Testar catálogo, variantes, idiomas, preços, stock, portes, encomendas, reconciliação, tracking e pós-venda. Um ficheiro CSV do catálogo não implementa o envio de encomendas.

Verificar antes de publicar: instalação de testes, testes da matriz, imagem principal, menus e tradução do tema, políticas e faturação. Não marcar “100% compatível” com base em mocks ou na presença de um plugin.

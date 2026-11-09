# Validação executada

09-11-2026:
- PHP 8.3.6 CLI: todos os 9 ficheiros PHP ativos passaram verificação de sintaxe.
- Teste unitário Gateway: nenhuma operação não configurada pode transmitir pedidos; capacidades anunciadas como inativas.
- Teste unitário Operations: compras bloqueadas por defeito; ativação exige todos os requisitos; indisponibilidade nativa do WooCommerce preservada.
- Node: sintaxe shop.js validada.
- Python: estrutura do projeto e ausência de carrinho de demonstração/endpoints Dreamlove inventados nos ficheiros ativos.

Estes testes não executam WordPress ou WooCommerce. A instalação na Hostinger não foi retomada neste pedido de preparação do código. Permanecem por executar os testes reais do tema, conector, checkout, tradução e layouts mobile. A CI PHP 8.1/8.3 foi incluída; só considerar verificada após o workflow real terminar.

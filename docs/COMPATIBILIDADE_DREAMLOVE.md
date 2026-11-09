# Matriz de integração Dreamlove

Análise em 09-11-2026 das páginas públicas:
- https://www.dreamlove.eu/grossistadropshippingpt/
- https://www.dreamlove.eu/tecnologiapt/

A informação pública anuncia serviços; não fornece esquemas de API, autenticação, versão do conector, limites ou garantia de compatibilidade. O objetivo é cobrir a totalidade das funcionalidades contratadas. O estado atual é preparação, não integração concluída.

## Via preferencial

WooCommerce + conector fornecido/validado pela Dreamlove. O tema não substitui os modelos de dados, variantes, carrinho ou checkout do WooCommerce. Não criar um segundo cliente de pedidos em paralelo. Confirmar com a Dreamlove versões suportadas de WordPress, PHP, WooCommerce, HPOS, checkout clássico/Blocks e plugin multilingue. Desativar atualizações automáticas do conector apenas se o plano de manutenção contratado o exigir, nunca por suposição.

| Capacidade anunciada | Base prevista na SEXCLICK | Validação necessária / estado atual |
|---|---|---|
| Catálogo completo ou seleção temática | Produtos e categorias WooCommerce | Conector pendente; testar seleção, atualizações e descontinuação |
| Novidades | Listagem nativa por data | Confirmar data de novidade do fornecedor, não só data de importação |
| SKU/EAN, marcas, tags, SEO | Dados normais do produto | Confirmar mapeamento e preservação de identificadores |
| Tamanhos, cores e variantes | Produtos variáveis nativos | Testar preços, imagem e stock por SKU de variante |
| Fotografias e descrições | Galeria e conteúdo WooCommerce | Credenciais/licença, importação e miniaturas pendentes |
| Seis idiomas | Tema preparado para gettext; seletor opcional do plugin multilingue | PT/EN/ES/FR/DE/IT do catálogo; confirmar plugin e traduções da interface; não existe tradução automática neste tema |
| Mapeamento de categorias | Taxonomia product_cat | Validar categorias de origem/destino no conector |
| Stock em tempo real | Disponibilidade nativa | Conector pendente; medir atraso e testar stock zero, reposição e variante |
| PVD e PVR | Custo interno e preço de venda separados | Confirmar bases de IVA, arredondamento, margens, promoções e alterações de custo |
| Impostos e dimensões | WooCommerce | Rever unidades, peso, classes fiscais e configuração fiscal do vendedor |
| Encomendas automáticas | Encomendas nativas | Pendente; só após pagamento confirmado; referência única, anti-duplicação, reconciliação e alertas |
| Pedidos de vários produtos | Carrinho nativo | Confirmar consolidação ou divisão, indisponibilidade parcial e portes |
| Transportadoras e tarifas | Zonas/métodos WooCommerce | Cotação real por morada/peso; confirmar DHL/GLS/DPD e destinos contratados |
| Envios e tracking | Conta e detalhe de encomenda | Adaptador/conector pendente; validar pacotes múltiplos, IDs, URL e estados |
| API catálogo/pedidos/transportadoras/tracking | Interface interna Gateway | Sem adaptador real; exige documentação e credenciais |
| CSV/XML por FTP | Alternativa de integração | Não implementada neste projeto; requer formato, codificação, acesso e agendamento; não transmite pedidos por si só |
| Embalagem discreta e marca da loja | Operação contratada Dreamlove | Confirmar branding, documentos incluídos e modalidades logísticas |
| Banners, fotografias HD, vídeos | Biblioteca de média WordPress | Importar apenas recursos autorizados do painel B2B |
| Faturas do fornecedor | Conta B2B | Confirmar API/connector para acesso; não expor custos grossistas ao cliente |
| Carteira virtual | Conta B2B Dreamlove | Confirmar saldo, saldo mínimo, falhas e se há API; nenhuma movimentação implementada |
| Fiscalidade europeia | WooCommerce + configuração fiscal/faturação | Serviço anunciado pelo fornecedor não substitui obrigações do vendedor |
| Pós-venda, devoluções e garantias | Gestão de encomendas/reembolsos WooCommerce | Fluxo RMA e aceitação pelo fornecedor pendentes; não reembolsar duas vezes |
| Envios rejeitados | Pós-venda Dreamlove | Confirmar custos, comunicação e estados operacionais |

## Condições técnicas para testes

Pedir à Dreamlove: pacote e versão do conector; instruções de instalação; ambiente sandbox ou procedimento de pedido teste; documentação API e ficheiros de exemplo; suporte HPOS/Blocks/multilingue; política de rate limits e sincronização; autenticação, webhooks e assinaturas; pesquisa de pedido por referência comercial; comportamento em timeout/duplicação; códigos de estado, tracking, portes, RMA, carteira e faturas; licença de imagens/conteúdos; regras contratuais de preços e expedição.

## Aceitação antes do lançamento

1. Importar um conjunto de teste com produto simples, variável, sem stock e descontinuado; repetir e verificar ausência de duplicados.
2. Atualizar preço, stock, imagem e tradução; provar que alterações locais permitidas são preservadas.
3. Simular dois compradores sobre a última unidade; validar o comportamento de reserva/stock do conector.
4. Cotar Portugal continental, ilhas e outros destinos contratados com cestos de tamanhos diferentes. Nenhum porte fictício ou prazo garantido sem confirmação.
5. Validar pagamento aprovado, recusado, repetido e webhook fora de ordem.
6. Confirmar uma única encomenda no fornecedor mesmo com timeout; reconciliar antes de repetir um envio cujo resultado seja desconhecido.
7. Receber tracking, pacote parcial, falha, entrega e devolução. Mostrar apenas ao cliente autenticado e dono da encomenda.
8. Testar insuficiência de saldo/carteira e erro de autenticação sem perder a encomenda nem expor segredos.
9. Medir latência de stock e agendamento em cron real; “tempo real” só pode ser anunciado após medição e acordo do fornecedor.
10. Testar telemóvel em 320/375/390/430 px, teclado, zoom 200%, procura, variante, checkout e conta. Testar seis idiomas com o plugin escolhido.
11. Rever cancelamentos, devoluções, garantia, faturação, privacidade e comunicações reais.
12. Obter confirmação de compatibilidade das versões efetivamente instaladas. Só então preencher o painel de preparação e ativar vendas.

# SEXCLICK — preparação de dropshipping

Estado: preparação técnica. Sem ligação Dreamlove, sem pagamentos e sem expedição automática.

## Conteúdo
- A pasta principal mantém a demonstração visual HTML. Não é ainda um tema WooCommerce.
- sexclick-dreamlove: módulo instalável em WordPress/WooCommerce, com importador CSV normalizado.
- catalogo-modelo.csv: cabeçalho do formato interno. Não é o formato confirmado do fornecedor.

## Instalação em ambiente de testes
1. Instalar WordPress e WooCommerce no alojamento, com HTTPS.
2. Copiar sexclick-dreamlove para wp-content/plugins e ativar o módulo.
3. Abrir WooCommerce > SEXCLICK Dreamlove.
4. Usar um CSV UTF-8 separado por ponto e vírgula, até 5 MB/2000 linhas.
5. Rever os produtos em rascunho. A reimportação por SKU atualiza apenas os produtos deste módulo e volta a colocá-los em rascunho.

O importador recebe preço FINAL DE VENDA em EUR, positivo, e stock inteiro não negativo. Não calcula IVA nem margem: estes dependem da configuração fiscal e comercial. As referências de imagem/categoria ficam guardadas como metadados; não descarrega imagens nem cria categorias. Variantes, seis traduções e grandes catálogos exigem o adaptador final.

## Encomendas
Ao receber o evento de pagamento concluído do WooCommerce, encomendas com produtos deste módulo recebem o estado interno awaiting_connection e uma nota privada. Não é criada uma encomenda Dreamlove. Nenhum pagamento é cobrado por este módulo. Nenhum dado de cliente é enviado ao fornecedor.

## Ligação futura — elementos necessários
- Acesso de revendedor e documentação oficial/API ou exportação de exemplo.
- Condições comerciais, custos/limites logísticos e territórios de entrega.
- Campos reais de SKU/EAN, variantes, stock, custos, impostos e traduções.
- Método autorizado de envio de pedidos e consulta de tracking.
- Conta de pagamentos compatível com o negócio e respetivos webhooks.
- Dados do vendedor, faturação, condições de venda, privacidade e devoluções.

## Trabalho necessário antes de vender
Converter o desenho HTML num tema WooCommerce ou aplicar a identidade visual num tema compatível. Implementar adaptador autenticado no servidor, segredos fora da pasta pública, sincronização agendada e registo de falhas. Calcular margens/IVA/portes; validar stock antes do pagamento; implementar envio idempotente por encomenda e reconciliação com o fornecedor; testar tracking, falhas de pagamento, cancelamentos e reembolsos. Não ativar dois conectores de expedição em simultâneo.

## Validação
Verificações estáticas de estrutura e sintaxe executadas quando o interpretador está disponível. A instalação funcional necessita de WordPress/WooCommerce de testes. Não foi executada qualquer ligação real ao fornecedor.

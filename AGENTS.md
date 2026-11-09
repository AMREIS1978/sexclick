# SEXCLICK

Use README.md e docs/COMPATIBILIDADE_DREAMLOVE.md como base. Não alterar outros sites do alojamento. Mudanças no código do repositório não equivalem a instalação em produção.

Execute validações de PHP e JavaScript, testes da integração e teste visual móvel quando existir um ambiente de staging. Não afirme que chamadas mockadas validam a Dreamlove. Não publique segredos, ficheiros .env, exportações de clientes ou bases de dados.

Um único conector envia encomendas ao fornecedor. Se o resultado de um envio for desconhecido, reconciliar antes de repetir. Confirmar pagamento real e stock antes de expedir. Usar CRUD WooCommerce e permissões/nonce em operações de gestão.

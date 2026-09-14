# PetCare

Uma página simples de vendas para um pet shop. A ideia é facilitar o atendimento no balcão: o funcionário informa os dados do tutor, registra o animal, escolhe os produtos ou serviços e finaliza a venda. Na mesma página, o sistema mostra uma nota fiscal simplificada e um recibo de pagamento.

## Estrutura do projeto

O projeto é pequeno de propósito e não depende de framework ou banco de dados.

sistemaVendasPetshop/
├── index.php    # Página, formulário e processamento da venda
├── style.css    # Aparência, layout responsivo e impressão
└── README.md    # Documentação do projeto


## O papel de cada arquivo

### `index.php`

É o arquivo principal e concentra o funcionamento da aplicação:

- Mantém uma lista inicial de produtos e serviços com seus preços.
- Exibe o formulário de atendimento.
- Recebe os dados enviados pelo método `POST`.
- Verifica se os campos obrigatórios foram preenchidos.
- Confere se pelo menos um item foi selecionado.
- Calcula o subtotal de cada item e o total da venda.
- Cria um número identificador para a venda.
- Mostra a nota fiscal simplificada e o recibo quando a venda é válida.
- Escapa os textos exibidos na tela para evitar que dados digitados sejam interpretados como HTML.
- Formata os valores no padrão brasileiro, como `R$ 149,90`.

### `style.css`

Cuida somente da apresentação visual. Ele organiza:

- Cabeçalho e identidade visual do pet shop.
- Formulários de tutor, animal e itens da venda.
- Resumo do pedido e mensagens de erro.
- Layout responsivo para telas menores.
- Aparência dos documentos gerados.
- Regras de impressão, escondendo o formulário e deixando apenas os documentos prontos para imprimir.

### `README.md`

É este guia. Ele explica como o projeto está organizado, como executar a aplicação e como as partes se relacionam.

## Como tudo se encaixa

O funcionamento acontece em uma sequência direta:

1. O navegador abre `index.php` usando uma requisição `GET`.
2. O PHP mostra o formulário vazio e a lista de produtos.
3. O usuário preenche os dados do tutor e do animal, escolhe os itens e informa a forma de pagamento.
4. Ao clicar em **Finalizar venda**, o navegador envia os campos para o próprio `index.php` usando `POST`.
5. O PHP lê os dados enviados, valida os campos e calcula os valores com base na lista de preços definida no início do arquivo.
6. Se existir algum problema, a página mostra mensagens de erro e mantém os dados preenchidos.
7. Se estiver tudo certo, o PHP monta duas áreas na mesma resposta: uma nota fiscal de venda simplificada e um recibo.
8. O usuário pode clicar em **Imprimir documentos**. O navegador abre a impressão usando as regras especiais do `style.css`.

Em resumo: o `index.php` decide o que acontece e o `style.css` define como tudo aparece.

## Funcionalidades disponíveis

### Cadastro rápido do tutor

O formulário aceita nome, CPF ou CNPJ, telefone e e-mail. Nome e documento são obrigatórios.

### Registro do animal

É possível informar nome, espécie e raça. O nome e a espécie são obrigatórios. As espécies disponíveis inicialmente são:

- Cachorro
- Gato
- Ave
- Outro

### Seleção de produtos e serviços

A lista atual já vem com estes itens:

- Ração Premium 10kg
- Banho e Tosa
- Consulta veterinária
- Brinquedo mordedor
- Antipulgas

Cada item possui preço, campo de quantidade e cálculo automático do subtotal no processamento do formulário.

### Formas de pagamento

O sistema oferece:

- Pix
- Cartão de crédito
- Cartão de débito
- Dinheiro

### Nota fiscal simplificada e recibo

Depois de uma venda válida, são exibidos:

- Número da venda.
- Data e hora da emissão.
- Dados do tutor.
- Dados do animal.
- Itens, quantidades e valores.
- Total pago.
- Forma de pagamento.
- Recibo com mensagem de confirmação.

## Como executar

É necessário ter o PHP instalado. No terminal, dentro da pasta do projeto, execute:

bash
php -S localhost:8000

Depois abra no navegador:
http://localhost:8000


Para encerrar o servidor, volte ao terminal e pressione `Ctrl+C`.

## Exemplo de uso

1. Informe `Marina Alves` como nome do tutor.
2. Informe um CPF ou CNPJ.
3. Cadastre o animal, por exemplo, `Bento`, espécie `Cachorro`.
4. Selecione um ou mais itens.
5. Escolha uma forma de pagamento.
6. Clique em **Finalizar venda**.
7. Confira a nota e o recibo na parte inferior da página.
8. Use **Imprimir documentos** para gerar uma versão para impressão ou salvar em PDF pelo navegador.

## Observações importantes

Este projeto é uma demonstração funcional e local. Ele não salva vendas em banco de dados e não possui login, estoque ou histórico de pedidos.

A nota apresentada é uma **nota fiscal simplificada para fins de demonstração**. Ela não substitui uma Nota Fiscal Eletrônica oficial. Para uso comercial real, seria necessário integrar o sistema a um emissor autorizado e atender às regras fiscais do município, do estado e da Receita Federal.

Os preços e dados da loja podem ser alterados diretamente no início do arquivo `index.php`. Em uma versão maior, o ideal seria mover produtos, clientes e vendas para um banco de dados.

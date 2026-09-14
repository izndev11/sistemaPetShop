<?php
$products = [
    'Racao Premium 10kg' => 149.90,
    'Banho e Tosa' => 85.00,
    'Consulta veterinaria' => 120.00,
    'Brinquedo mordedor' => 34.90,
    'Antipulgas' => 48.50,
];

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$errors = [];
$data = [];
$items = [];
$total = 0;
$documentNumber = 'PET-' . date('Ymd') . '-' . random_int(100, 999);

if ($submitted) {
    $data = [
        'customer_name' => trim($_POST['customer_name'] ?? ''),
        'customer_document' => trim($_POST['customer_document'] ?? ''),
        'customer_email' => trim($_POST['customer_email'] ?? ''),
        'customer_phone' => trim($_POST['customer_phone'] ?? ''),
        'pet_name' => trim($_POST['pet_name'] ?? ''),
        'pet_species' => trim($_POST['pet_species'] ?? ''),
        'pet_breed' => trim($_POST['pet_breed'] ?? ''),
        'payment_method' => trim($_POST['payment_method'] ?? ''),
    ];

    foreach (['customer_name' => 'Nome do tutor', 'customer_document' => 'CPF/CNPJ', 'pet_name' => 'Nome do animal', 'pet_species' => 'Especie', 'payment_method' => 'Forma de pagamento'] as $field => $label) {
        if ($data[$field] === '') {
            $errors[] = "Preencha o campo: {$label}.";
        }
    }

    $selectedProducts = $_POST['products'] ?? [];
    $quantities = $_POST['quantities'] ?? [];

    foreach ($selectedProducts as $product) {
        if (isset($products[$product])) {
            $quantity = max(1, (int) ($quantities[$product] ?? 1));
            $unitPrice = $products[$product];
            $subtotal = $unitPrice * $quantity;
            $items[] = [
                'name' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }
    }

    if (!$items) {
        $errors[] = 'Selecione pelo menos um produto ou servico.';
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function money(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetCare | Venda e documentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <div class="brand"><span class="brand-mark">P</span><span>PetCare</span></div>
        <div class="topbar-note"><span class="status-dot"></span> Atendimento ativo <strong>Hoje, 09:00 - 19:00</strong></div>
    </header>

    <main class="page-shell">
        <section class="intro">
            <div>
                <p class="eyebrow">PDV / nova venda</p>
                <h1>Venda com cuidado.<br><span>Registre cada detalhe.</span></h1>
                <p class="intro-copy">Preencha os dados do tutor, do animal e escolha os produtos. Ao finalizar, a nota fiscal e o recibo ficam prontos para imprimir.</p>
            </div>
            <div class="intro-badge"><strong>01</strong><span>venda<br>segura</span></div>
        </section>

        <form method="post" class="sale-form">
            <div class="form-layout">
                <div class="form-column">
                    <section class="panel">
                        <div class="panel-heading"><span class="step">01</span><div><h2>Dados do tutor</h2><p>Quem esta realizando a compra?</p></div></div>
                        <div class="fields-grid">
                            <label class="field field-wide">Nome completo *<input type="text" name="customer_name" value="<?= e($data['customer_name'] ?? '') ?>" placeholder="Ex.: Marina Alves" required></label>
                            <label class="field">CPF ou CNPJ *<input type="text" name="customer_document" value="<?= e($data['customer_document'] ?? '') ?>" placeholder="000.000.000-00" required></label>
                            <label class="field">Telefone<input type="tel" name="customer_phone" value="<?= e($data['customer_phone'] ?? '') ?>" placeholder="(00) 00000-0000"></label>
                            <label class="field">E-mail<input type="email" name="customer_email" value="<?= e($data['customer_email'] ?? '') ?>" placeholder="marina@email.com"></label>
                        </div>
                    </section>

                    <section class="panel">
                        <div class="panel-heading"><span class="step">02</span><div><h2>Sobre o animal</h2><p>Identifique quem vai receber nossos cuidados.</p></div></div>
                        <div class="fields-grid">
                            <label class="field">Nome do animal *<input type="text" name="pet_name" value="<?= e($data['pet_name'] ?? '') ?>" placeholder="Ex.: Bento" required></label>
                            <label class="field">Especie *<select name="pet_species" required><option value="">Selecione</option><?php foreach (['Cachorro', 'Gato', 'Ave', 'Outro'] as $species): ?><option <?= ($data['pet_species'] ?? '') === $species ? 'selected' : '' ?>><?= $species ?></option><?php endforeach; ?></select></label>
                            <label class="field field-wide">Raca<input type="text" name="pet_breed" value="<?= e($data['pet_breed'] ?? '') ?>" placeholder="Ex.: Golden Retriever"></label>
                        </div>
                    </section>

                    <section class="panel">
                        <div class="panel-heading"><span class="step">03</span><div><h2>Itens da venda</h2><p>Selecione os produtos e servicos contratados.</p></div></div>
                        <div class="product-list">
                            <?php foreach ($products as $product => $price): ?>
                                <label class="product-row"><input type="checkbox" name="products[]" value="<?= e($product) ?>" <?= in_array($product, array_column($items, 'name'), true) ? 'checked' : '' ?>><span class="checkmark"></span><span class="product-name"><?= e($product) ?></span><span class="quantity-wrap"><small>Qtd.</small><input type="number" name="quantities[<?= e($product) ?>]" min="1" value="<?= (int) (($quantities[$product] ?? 1)) ?>"></span><strong><?= money($price) ?></strong></label>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <aside class="summary panel">
                    <p class="eyebrow">resumo da venda</p><h2>Quase pronto.</h2><p class="summary-copy">Confira a forma de pagamento para emitir os documentos.</p>
                    <label class="field">Forma de pagamento *<select name="payment_method" required><option value="">Selecione</option><?php foreach (['Pix', 'Cartao de credito', 'Cartao de debito', 'Dinheiro'] as $method): ?><option <?= ($data['payment_method'] ?? '') === $method ? 'selected' : '' ?>><?= $method ?></option><?php endforeach; ?></select></label>
                    <div class="summary-total"><span>Total estimado</span><strong><?= money($total) ?></strong></div>
                    <?php if ($errors): ?><div class="errors"><?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?></div><?php endif; ?>
                    <button class="primary-button" type="submit">Finalizar venda <span>→</span></button>
                    <p class="privacy-note">Seus dados sao usados apenas para emitir os documentos desta venda.</p>
                </aside>
            </div>
        </form>

        <?php if ($submitted && !$errors): ?>
            <section class="documents" id="documentos">
                <div class="documents-heading"><div><p class="eyebrow">venda concluida · <?= e($documentNumber) ?></p><h2>Documentos da venda</h2></div><button class="outline-button" onclick="window.print()">Imprimir documentos <span>↗</span></button></div>
                <div class="document-grid">
                    <article class="document"><div class="document-top"><div class="brand small"><span class="brand-mark">P</span><span>Patas <em>&</em> Companhia</span></div><span class="document-label">NF-e simplificada</span></div><div class="document-title"><div><p>Nota fiscal de venda</p><h3><?= e($documentNumber) ?></h3></div><span class="paid">PAGO</span></div><div class="document-meta"><span><small>Emissao</small><?= date('d/m/Y H:i') ?></span><span><small>Pagamento</small><?= e($data['payment_method']) ?></span></div><div class="document-person"><small>CLIENTE / TUTOR</small><strong><?= e($data['customer_name']) ?></strong><span><?= e($data['customer_document']) ?> · <?= e($data['customer_email'] ?: 'E-mail nao informado') ?></span></div><div class="document-person"><small>ANIMAL</small><strong><?= e($data['pet_name']) ?></strong><span><?= e($data['pet_species']) ?><?= $data['pet_breed'] ? ' · ' . e($data['pet_breed']) : '' ?></span></div><table><thead><tr><th>Descricao</th><th>Qtd.</th><th>Valor</th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['name']) ?></td><td><?= $item['quantity'] ?></td><td><?= money($item['subtotal']) ?></td></tr><?php endforeach; ?></tbody></table><div class="doc-total"><span>Total da venda</span><strong><?= money($total) ?></strong></div></article>
                    <article class="document receipt"><div class="document-top"><div class="brand small"><span class="brand-mark">P</span><span>Patas <em>&</em> Companhia</span></div><span class="document-label">Comprovante</span></div><div class="receipt-icon">✓</div><p class="receipt-kicker">Recibo de pagamento</p><h3>Obrigado, <?= e(explode(' ', $data['customer_name'])[0]) ?>.</h3><p class="receipt-copy">Recebemos o pagamento referente aos produtos e servicos para <strong><?= e($data['pet_name']) ?></strong>.</p><div class="receipt-amount"><small>Valor recebido</small><strong><?= money($total) ?></strong></div><div class="receipt-lines"><span>Documento <strong><?= e($documentNumber) ?></strong></span><span>Forma de pagamento <strong><?= e($data['payment_method']) ?></strong></span><span>Data <strong><?= date('d/m/Y') ?></strong></span></div><div class="signature">Patas & Companhia <small>Atendimento que deixa o rabo abanando.</small></div></article>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <footer><span>Patas & Companhia</span><span>Av. das Flores, 128 · Sao Paulo, SP</span><span>Documento gerado em <?= date('d/m/Y') ?></span></footer>
</body>
</html>

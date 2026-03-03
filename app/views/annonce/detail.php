<?php if (!isset($annonce)): ?>
    <p>Annonce introuvable.</p>
<?php else: ?>
    <article>
        <h2><?= htmlspecialchars($annonce['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p><strong>Adresse :</strong>
            <?= htmlspecialchars($annonce['adresse'], ENT_QUOTES, 'UTF-8') ?>,
            <?= htmlspecialchars($annonce['ville'], ENT_QUOTES, 'UTF-8') ?>
            (<?= htmlspecialchars($annonce['code_postal'], ENT_QUOTES, 'UTF-8') ?>)
        </p>
        <p><strong>Surface :</strong> <?= htmlspecialchars($annonce['surface'], ENT_QUOTES, 'UTF-8') ?> m²</p>
        <p><strong>Loyer :</strong> <?= htmlspecialchars($annonce['loyer'], ENT_QUOTES, 'UTF-8') ?> €</p>
        <?php if (!empty($annonce['charges'])): ?>
            <p><strong>Charges :</strong> <?= htmlspecialchars($annonce['charges'], ENT_QUOTES, 'UTF-8') ?> €</p>
        <?php endif; ?>
        <p><strong>Description :</strong></p>
        <p><?= nl2br(htmlspecialchars($annonce['description'], ENT_QUOTES, 'UTF-8')) ?></p>
    </article>
<?php endif; ?>


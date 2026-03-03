<section>
    <h2>Liste des annonces</h2>

    <?php if (empty($annonces)): ?>
        <p>Aucune annonce pour le moment.</p>
    <?php else: ?>
        <div class="annonces">
            <?php foreach ($annonces as $annonce): ?>
                <article class="annonce">
                    <h3><?= htmlspecialchars($annonce['titre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>
                        <?= nl2br(htmlspecialchars(substr($annonce['description'], 0, 150), ENT_QUOTES, 'UTF-8')) ?>...
                    </p>
                    <p>
                        <strong>Loyer :</strong>
                        <?= htmlspecialchars($annonce['loyer'], ENT_QUOTES, 'UTF-8') ?> €
                        <?php if (!empty($annonce['charges'])): ?>
                            + <?= htmlspecialchars($annonce['charges'], ENT_QUOTES, 'UTF-8') ?> € charges
                        <?php endif; ?>
                    </p>
                    <p>
                        <a class="btn" href="<?= BASE_URL ?>index.php?controller=annonce&action=detail&id=<?= (int) $annonce['id_annonce'] ?>">
                            Voir le détail
                        </a>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>


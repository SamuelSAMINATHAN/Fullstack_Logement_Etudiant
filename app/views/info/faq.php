<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <h2 class="text-center mb-5">Foire Aux Questions (FAQ)</h2>
        
        <div class="accordion shadow-sm" id="faqAccordion">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $index !== 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                                <strong><?php echo Security::escape($faq['question']); ?></strong>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo Security::escape($faq['reponse']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Aucune question n'est disponible pour le moment.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <p>Vous n'avez pas trouvé la réponse à votre question ?</p>
            <a href="<?php echo URLROOT; ?>/page/contact" class="btn btn-outline-primary">Contactez-nous</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>

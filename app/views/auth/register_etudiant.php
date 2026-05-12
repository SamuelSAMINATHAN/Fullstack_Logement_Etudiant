<script src="https://cdn.tailwindcss.com"></script>
<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Inscription Étudiant
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Créez votre compte pour accéder aux annonces de logement
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php if (isset($_SESSION['flash'])): ?>
                <?php if ($_SESSION['flash']['type'] === 'error'): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <?= $_SESSION['flash']['message'] ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form class="space-y-6" action="<?php echo URLROOT; ?>/auth/registerHandler" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="role" value="etudiant">

                <!-- Informations personnelles -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informations personnelles</h3>

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="nom" name="nom" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Votre nom">
                        </div>
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="prenom" name="prenom" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Votre prénom">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="votre.email@exemple.com">
                        </div>
                    </div>
                </div>

                <!-- Informations étudiant -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informations étudiant</h3>

                    <div>
                        <label for="dateNaissance" class="block text-sm font-medium text-gray-700">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="dateNaissance" name="dateNaissance" type="date" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   max="<?php echo date('Y-m-d', strtotime('-16 years')); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="localisation" class="block text-sm font-medium text-gray-700">
                            Localisation souhaitée <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="localisation" name="localisation" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Ex: Paris, Lyon, Marseille...">
                        </div>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Mot de passe</h3>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Min. 8 caractères">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="password_confirm" name="password_confirm" type="password" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Répétez le mot de passe">
                        </div>
                    </div>
                </div>

                <!-- CGU -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <input id="cgu" name="cgu" type="checkbox" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="cgu" class="ml-2 block text-sm text-gray-900">
                            J'accepte les <a href="<?php echo URLROOT; ?>/page/cgu" class="text-blue-600 hover:text-blue-500">conditions générales d'utilisation</a> <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        S'inscrire
                    </button>
                </div>

                <div class="text-sm text-center">
                    <span class="text-gray-600">Pas étudiant ?</span>
                    <a href="<?php echo URLROOT; ?>/auth/register/bailleur" class="font-medium text-green-600 hover:text-green-500">
                        S'inscrire comme bailleur
                    </a>
                </div>

                <div class="text-sm text-center">
                    <span class="text-gray-600">Vous avez déjà un compte ?</span>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="font-medium text-blue-600 hover:text-blue-500">
                        Se connecter
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = this.value;

    if (password !== passwordConfirm) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const passwordConfirm = document.getElementById('password_confirm').value;

    if (this.value !== passwordConfirm && passwordConfirm !== '') {
        document.getElementById('password_confirm').setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        document.getElementById('password_confirm').setCustomValidity('');
    }
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
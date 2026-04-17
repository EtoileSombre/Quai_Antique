<!-- Hero Section -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Administration</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">
            Modération des <span class="text-or">Avis</span>
        </h1>
        <div class="separator"></div>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin-nav.php'; ?>

<section class="max-w-5xl mx-auto px-6 pb-20">
    <?php $flash_success = \App\Core\Session::get('flash_success'); ?>
    <?php $flash_error = \App\Core\Session::get('flash_error'); ?>

    <?php if ($flash_success): ?>
        <?php \App\Core\Session::delete('flash_success'); ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-green-600"></i>
            <?= htmlspecialchars($flash_success) ?>
        </div>
    <?php endif; ?>

    <?php if ($flash_error): ?>
        <?php \App\Core\Session::delete('flash_error'); ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-3">
            <i class="bi bi-exclamation-circle-fill text-red-600"></i>
            <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($avis)): ?>
        <p class="text-center text-gris py-12">Aucun avis à modérer.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($avis as $a): ?>
                <div class="card p-6 <?= empty($a['approved']) ? 'border-l-4 border-l-amber-400' : 'border-l-4 border-l-green-400' ?>">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-semibold text-or-dark"><?= htmlspecialchars($a['user_name']) ?></span>
                                <span class="text-or">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $a['rating'] ? '-fill' : '' ?> text-sm"></i>
                                    <?php endfor; ?>
                                </span>
                                <span class="text-gris text-xs"><?= htmlspecialchars($a['created_at']) ?></span>
                                <?php if (!empty($a['approved'])): ?>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Approuvé</span>
                                <?php else: ?>
                                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">En attente</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-gris text-sm leading-relaxed"><?= nl2br(htmlspecialchars($a['comment'])) ?></p>
                        </div>

                        <div class="flex gap-2 shrink-0">
                            <?php if (empty($a['approved'])): ?>
                                <form action="/admin/avis/approuver" method="POST" class="inline">
                                    <?= \App\Core\Csrf::field() ?>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($a['id']) ?>">
                                    <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 transition text-sm" title="Approuver">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="/admin/avis/rejeter" method="POST" class="inline">
                                    <?= \App\Core\Csrf::field() ?>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($a['id']) ?>">
                                    <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg hover:bg-amber-100 transition text-sm" title="Masquer">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form action="/admin/avis/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer cet avis ?')">
                                <?= \App\Core\Csrf::field() ?>
                                <input type="hidden" name="id" value="<?= htmlspecialchars($a['id']) ?>">
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition text-sm" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

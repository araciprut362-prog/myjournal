<?php
$pageTitle = 'Buka Kunci Jurnal — PIN Keamanan';
$user = Auth::user();
require __DIR__ . '/../layout/header.php';
?>

<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-10 px-4 sm:px-6">
    <div class="max-w-sm w-full text-center space-y-6">
        
        <!-- Sanctuary Icon & Avatar -->
        <div class="space-y-3">
            <div class="relative mx-auto w-20 h-20 rounded-3xl bg-amber-100 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900/60 flex items-center justify-center text-amber-700 dark:text-amber-400 shadow-md">
                <i data-lucide="shield-check" class="w-10 h-10"></i>
                <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-amber-600 text-white flex items-center justify-center text-xs shadow">
                    <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            
            <div>
                <h2 class="font-serif text-2xl font-bold text-stone-900 dark:text-stone-100">
                    Jurnal Terkunci
                </h2>
                <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                    Masuk sebagai <span class="font-semibold text-stone-800 dark:text-stone-200"><?= htmlspecialchars(Auth::name()) ?></span>
                </p>
                <p class="text-xs text-stone-400 dark:text-stone-500 mt-0.5">
                    Masukkan PIN Keamanan untuk membuka catatanmu
                </p>
            </div>
        </div>

        <!-- PIN Card -->
        <div class="bg-white dark:bg-stone-900 border border-stone-200/90 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
            <form id="pin-form" action="index.php?page=pin-verify" method="POST" class="space-y-5">
                
                <div>
                    <input id="pin-input" name="pin_code" type="password" maxlength="6" inputmode="numeric" autofocus
                           class="w-full text-center text-2xl font-mono tracking-[0.5em] py-3 px-4 bg-stone-50 dark:bg-stone-850 border border-stone-300 dark:border-stone-700 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-stone-900 dark:text-stone-100 placeholder-stone-300"
                           placeholder="••••">
                </div>

                <!-- Onscreen Interactive Numpad -->
                <div class="grid grid-cols-3 gap-2 max-w-[240px] mx-auto select-none">
                    <?php for ($n = 1; $n <= 9; $n++): ?>
                        <button type="button" onclick="appendPin('<?= $n ?>')"
                                class="h-12 rounded-xl bg-stone-100 hover:bg-stone-200 active:bg-amber-100 dark:bg-stone-850 dark:hover:bg-stone-800 dark:active:bg-amber-950/50 text-stone-800 dark:text-stone-200 font-semibold text-lg transition flex items-center justify-center">
                            <?= $n ?>
                        </button>
                    <?php endfor; ?>
                    <button type="button" onclick="clearPin()"
                            class="h-12 rounded-xl bg-stone-100 hover:bg-rose-50 active:bg-rose-100 dark:bg-stone-850 dark:hover:bg-rose-950/40 text-stone-500 hover:text-rose-600 dark:text-stone-400 font-medium text-xs transition flex items-center justify-center uppercase tracking-wider">
                        Hapus
                    </button>
                    <button type="button" onclick="appendPin('0')"
                            class="h-12 rounded-xl bg-stone-100 hover:bg-stone-200 active:bg-amber-100 dark:bg-stone-850 dark:hover:bg-stone-800 dark:active:bg-amber-950/50 text-stone-800 dark:text-stone-200 font-semibold text-lg transition flex items-center justify-center">
                        0
                    </button>
                    <button type="button" onclick="backspacePin()"
                            class="h-12 rounded-xl bg-stone-100 hover:bg-stone-200 active:bg-amber-100 dark:bg-stone-850 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-400 transition flex items-center justify-center">
                        <i data-lucide="delete" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 bg-stone-900 hover:bg-stone-800 dark:bg-amber-600 dark:hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-stone-900/10 dark:shadow-amber-900/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="unlock" class="w-4 h-4"></i>
                        <span>Buka Kunci Jurnal</span>
                    </button>
                </div>
            </form>

            <div class="mt-4 pt-4 border-t border-stone-200 dark:border-stone-800 text-center">
                <span class="text-xs text-stone-400 dark:text-stone-500">
                    PIN bawaan uji coba adalah <strong class="text-stone-600 dark:text-stone-300 font-mono">1234</strong>
                </span>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3 text-xs text-stone-500 dark:text-stone-400">
            <a href="index.php?page=logout" class="hover:text-rose-600 dark:hover:text-rose-400 transition flex items-center gap-1">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                <span>Masuk dengan akun lain</span>
            </a>
        </div>

    </div>
</div>

<script>
    const pinInput = document.getElementById('pin-input');

    function appendPin(num) {
        if (pinInput.value.length < 6) {
            pinInput.value += num;
        }
    }

    function backspacePin() {
        pinInput.value = pinInput.value.slice(0, -1);
    }

    function clearPin() {
        pinInput.value = '';
    }
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

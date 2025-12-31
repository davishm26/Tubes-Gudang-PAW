<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Akun Dibatasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                <!-- Header Section -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-200 px-8 py-12">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-14 w-14 rounded-full bg-amber-100 border border-amber-300">
                                <svg class="h-7 w-7 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Header Content -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-slate-900 mb-2">Akses Akun Dibatasi</h1>
                            <p class="text-slate-600 leading-relaxed">
                                Akses akun perusahaan Anda telah dibatasi oleh administrator sistem. Kami mengambil langkah ini sebagai bagian dari protokol keamanan dan tata kelola layanan kami.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="px-8 py-8">

                    <!-- Status Information Card -->
                    <?php if(!empty($suspendReason) || !empty($suspendReasonType)): ?>
                        <div class="mb-8 p-6 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200">
                            <h2 class="text-lg font-semibold text-slate-900 mb-6">Informasi Status Akun</h2>

                            <div class="space-y-5">
                                <!-- Status -->
                                <div class="flex items-start gap-4">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-200 text-slate-700 font-semibold text-sm flex-shrink-0">1</span>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-1">Status Akun</p>
                                        <p class="text-slate-900 font-medium">Dibatasi (Suspended)</p>
                                    </div>
                                </div>

                                <!-- Suspension Category -->
                                <?php if(!empty($suspendReasonType)): ?>
                                    <div class="flex items-start gap-4">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-200 text-slate-700 font-semibold text-sm flex-shrink-0">2</span>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">Kategori Pembatasan</p>
                                            <?php
                                                $reasonTypeLabels = [
                                                    'payment_failed' => ['label' => 'Pembayaran Tertunda', 'icon' => '💳', 'desc' => 'Terdapat masalah pembayaran yang perlu diselesaikan'],
                                                    'policy_violation' => ['label' => 'Pelanggaran Kebijakan', 'icon' => '⚖️', 'desc' => 'Terdapat aktivitas yang melanggar ketentuan layanan'],
                                                    'admin_action' => ['label' => 'Tindakan Administratif', 'icon' => '🔒', 'desc' => 'Dibatasi atas keputusan administratif'],
                                                    'other' => ['label' => 'Alasan Lainnya', 'icon' => '📋', 'desc' => 'Pembatasan untuk alasan tertentu'],
                                                ];
                                                $reasonInfo = $reasonTypeLabels[$suspendReasonType] ?? $reasonTypeLabels['other'];
                                            ?>
                                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-amber-300">
                                                <span class="text-lg"><?php echo e($reasonInfo['icon']); ?></span>
                                                <span class="font-semibold text-amber-900"><?php echo e($reasonInfo['label']); ?></span>
                                            </div>
                                            <p class="text-sm text-slate-600 mt-2"><?php echo e($reasonInfo['desc']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Detailed Explanation -->
                                <?php if(!empty($suspendReason)): ?>
                                    <div class="flex items-start gap-4 pt-2">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-200 text-slate-700 font-semibold text-sm flex-shrink-0">3</span>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">Penjelasan Detail</p>
                                            <p class="text-slate-700 leading-relaxed bg-white p-4 rounded-lg border border-slate-200"><?php echo e($suspendReason); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mb-8 p-6 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200">
                            <p class="text-slate-700 leading-relaxed">
                                Akun perusahaan Anda telah dibatasi oleh administrator sistem. Untuk informasi lebih lengkap mengenai penyebab pembatasan, silakan hubungi tim dukungan kami.
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Action Guidance Section -->
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-6 mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Langkah yang Dapat Anda Lakukan</h3>
                        <div class="space-y-3">
                            <div class="flex gap-3 items-start">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-600 text-white flex-shrink-0 text-sm font-semibold">✓</div>
                                <div>
                                    <p class="font-medium text-slate-900">Hubungi Tim Dukungan Kami</p>
                                    <p class="text-sm text-slate-600 mt-1">Tim administrator siap membantu Anda mengatasi masalah pembatasan akun</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-600 text-white flex-shrink-0 text-sm font-semibold">✓</div>
                                <div>
                                    <p class="font-medium text-slate-900">Tinjau Kebijakan Layanan</p>
                                    <p class="text-sm text-slate-600 mt-1">Pastikan aktivitas Anda sesuai dengan ketentuan layanan yang berlaku</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-600 text-white flex-shrink-0 text-sm font-semibold">✓</div>
                                <div>
                                    <p class="font-medium text-slate-900">Selesaikan Kewajiban Yang Tertunda</p>
                                    <p class="text-sm text-slate-600 mt-1">Jika ada pembayaran atau dokumentasi yang diperlukan, harap segera diselesaikan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Info (jika terkait payment) -->
                    <?php if($company && $suspendReasonType === 'payment_failed'): ?>
                        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6 mb-8">
                            <h4 class="font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Langganan
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700 font-medium">Status Langganan:</span>
                                    <span class="text-blue-900 font-semibold"><?php echo e(ucfirst($company->subscription_status ?? 'N/A')); ?></span>
                                </div>
                                <?php if($company->subscription_price): ?>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 font-medium">Harga Langganan:</span>
                                        <span class="text-blue-900 font-semibold">Rp <?php echo e(number_format($company->subscription_price, 0, ',', '.')); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if($company->subscription_end_date): ?>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700 font-medium">Tanggal Berakhir:</span>
                                        <span class="text-blue-900 font-semibold"><?php echo e(\Carbon\Carbon::parse($company->subscription_end_date)->format('d M Y')); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-4 pt-4 border-t border-blue-200">
                                    <p class="text-blue-800 text-xs">
                                        💡 <strong>Catatan:</strong> Untuk mengaktifkan kembali akun, harap selesaikan pembayaran yang tertunda atau hubungi tim dukungan untuk informasi lebih lanjut.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Request Reactivation Form -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 p-6 mb-8">
                        <h4 class="font-semibold text-green-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ajukan Permintaan Reaktivasi
                        </h4>
                        <p class="text-sm text-green-800 mb-4">Sampaikan kondisi Anda dan tim kami akan segera meninjau permintaan reaktivasi akun Anda.</p>

                        <?php if(session('success')): ?>
                            <div class="mb-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                                <p class="text-sm text-green-800 font-medium">✓ <?php echo e(session('success')); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="mb-4 p-4 bg-red-100 border border-red-300 rounded-lg">
                                <p class="text-sm text-red-800 font-medium">✗ <?php echo e(session('error')); ?></p>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('subscription.reactivation.request')); ?>" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-green-900 mb-1">Email Kontak <span class="text-red-600">*</span></label>
                                <input type="email" id="contact_email" name="contact_email" required
                                    class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="email@perusahaan.com"
                                    value="<?php echo e(old('contact_email', Auth::check() ? Auth::user()->email : '')); ?>">
                                <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-green-900 mb-1">Nomor Telepon (Opsional)</label>
                                <input type="text" id="contact_phone" name="contact_phone"
                                    class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="08123456789"
                                    value="<?php echo e(old('contact_phone')); ?>">
                                <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-green-900 mb-1">Pesan Anda <span class="text-red-600">*</span></label>
                                <textarea id="message" name="message" required rows="4"
                                    class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Jelaskan alasan permintaan reaktivasi dan langkah yang telah Anda lakukan..."><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-xs text-green-700 mt-1">Maksimal 1000 karakter</p>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:from-green-700 hover:to-emerald-700 active:from-green-800 active:to-emerald-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                📨 Kirim Permintaan Reaktivasi
                            </button>
                        </form>
                    </div>

                    <!-- Support Contact Info -->
                    <div class="bg-indigo-50 rounded-xl border border-indigo-200 p-6 mb-8">
                        <h4 class="font-semibold text-indigo-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Kontak Dukungan Pelanggan
                        </h4>
                        <div class="space-y-2 text-sm text-indigo-800">
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <strong>Email:</strong> support@gudangapp.com
                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <strong>Telepon:</strong> 021-1234-5678
                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <strong>Jam Operasional:</strong> Senin - Jumat, 09:00 - 17:00 WIB
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Footer Action -->
                <div class="bg-slate-50 border-t border-slate-200 px-8 py-6 flex justify-center">
                    <a href="<?php echo e(route('subscription.landing')); ?>" class="inline-flex items-center justify-center px-8 py-3 rounded-lg bg-slate-600 text-white font-semibold shadow-md hover:bg-slate-700 active:bg-slate-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        ← Kembali ke Halaman Utama
                    </a>
                </div>
            </div>

            <!-- Bottom Info -->
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600">
                    Jika Anda merasa pembatasan ini adalah kesalahan, silakan hubungi administrator sistem.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/subscription/suspended.blade.php ENDPATH**/ ?>
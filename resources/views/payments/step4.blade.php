<div x-show="step === 4" x-cloak class="space-y-6 text-center py-4">
    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto text-2xl">
        <i class="fa-solid fa-shield-halved"></i>
    </div>

    <div>
        <h2 class="text-xl font-bold text-gray-800">Konfirmasi Akhir Pesanan</h2>
        <p class="text-sm text-gray-500 mt-1">Pastikan seluruh informasi sudah benar sebelum memproses pembayaran.</p>
    </div>

    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 max-w-md mx-auto text-left text-sm space-y-2">
        <div class="flex justify-between">
            <span class="text-gray-600">Total Pembayaran:</span>
            <span class="font-bold text-indigo-600">Rp{{ number_format($total ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Metode Pembayaran:</span>
            <span class="font-bold text-gray-800" x-text="selectedPaymentName || selectedPayment || 'QRIS'"></span>
        </div>
    </div>

    <div class="pt-4 flex justify-between items-center max-w-md mx-auto">
        <button type="button" @click="step = 3" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Cek Kembali
        </button>

        <button type="button" onclick="submitCheckoutForm()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold text-sm transition shadow-lg flex items-center gap-2">
            Bayar Sekarang <i class="fa-solid fa-bolt"></i>
        </button>
    </div>
</div>

<script>
    function submitCheckoutForm() {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Apakah data pengiriman dan metode pembayaran sudah sesuai?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Ya, Bayar Sekarang',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold px-4 py-2',
                cancelButton: 'rounded-xl font-bold px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan Swal Loading
                Swal.fire({
                    title: 'Memproses Pesanan...',
                    text: 'Harap tunggu, kami sedang menghubungkan ke Payment Gateway.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit Form Checkout Utama
                const form = document.getElementById('checkout-form');
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
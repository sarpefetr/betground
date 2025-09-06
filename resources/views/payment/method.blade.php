<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $paymentMethod->name }} - Para Yatır</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .payment-card {
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        .copy-btn {
            transition: all 0.2s ease;
        }
        .copy-btn:hover {
            background: #3b82f6;
            color: white;
        }
        [x-cloak] { 
            display: none !important; 
        }
        .step-content {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="min-h-screen py-12">
    <!-- Payment Method Header -->
    <div class="container mx-auto px-4 mb-8">
        <div class="text-center">
            <div class="payment-card rounded-2xl p-8 relative overflow-hidden border">
                @if($paymentMethod->image)
                    <div class="absolute inset-0 opacity-100">
                        <img src="{{ $paymentMethod->image_url }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-cover object-center rounded-2xl">
                        <div class="absolute inset-0 bg-white bg-opacity-80 rounded-2xl"></div>
                    </div>
                @endif
                
                <div class="relative">
                    <h1 class="text-4xl font-bold mb-4 text-gray-900">{{ $paymentMethod->name }}</h1>
                    <p class="text-xl text-gray-600 mb-4">{{ $paymentMethod->description }}</p>
                    
                    <div class="flex items-center justify-center gap-6 text-sm">
                        <div class="flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-full">
                            <i class="fas fa-clock mr-2"></i>
                            {{ $paymentMethod->processing_time ?? 'Hızlı İşlem' }}
                        </div>
                        <div class="flex items-center text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                            <i class="fas fa-coins mr-2"></i>
                            {{ $paymentMethod->amount_range }}
                        </div>
                        <div class="flex items-center text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                            <i class="fas fa-percentage mr-2"></i>
                            {{ $paymentMethod->commission_display }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Process -->
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="payment-card rounded-2xl p-6 border">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Ödeme Yöntemi Seçin</h2>
                            <div class="text-sm">
                                <span class="text-gray-500">Seçilen:</span>
                                <span class="text-blue-600 font-bold">{{ $paymentMethod->name }}</span>
                            </div>
                        </div>

                        <!-- Selected Method Display -->
                        <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-blue-600 text-3xl mr-4">
                                        @if($paymentMethod->method_code === 'bank_transfer') <i class="fas fa-university"></i>
                                        @elseif($paymentMethod->method_code === 'credit_card') <i class="fas fa-credit-card"></i>
                                        @elseif($paymentMethod->method_code === 'crypto') <i class="fab fa-bitcoin"></i>
                                        @elseif($paymentMethod->method_code === 'ewallet') <i class="fas fa-wallet"></i>
                                        @elseif($paymentMethod->method_code === 'mobile') <i class="fas fa-mobile-alt"></i>
                                        @else <i class="fas fa-credit-card"></i> @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-xl text-gray-900">{{ $paymentMethod->name }}</h3>
                                        <p class="text-gray-600">{{ $paymentMethod->parent->name ?? 'Ödeme Yöntemi' }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if($paymentMethod->processing_time)
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $paymentMethod->processing_time }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div x-data="paymentForm()" x-init="init()">
                            <!-- Step 1: Amount Input -->
                            <div x-show="step === 1" class="step-content">
                                <h3 class="text-xl font-bold mb-4 text-gray-900">1. Yatırım Tutarını Belirleyin</h3>
                                
                                <div class="mb-6">
                                    <label class="block text-sm font-medium mb-2 text-gray-700">Yatırım Tutarı</label>
                                    <div class="relative">
                                        <input type="number" 
                                               x-model="amount"
                                               placeholder="0"
                                               min="{{ $paymentMethod->min_amount }}"
                                               max="{{ $paymentMethod->max_amount }}"
                                               step="0.01"
                                               class="w-full bg-white border-2 border-gray-300 rounded-lg px-4 py-3 text-gray-900 text-xl font-bold text-center input-field transition-all"
                                               required>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 font-bold">₺</div>
                                    </div>
                                    
                                    <!-- Quick amounts -->
                                    <div class="grid grid-cols-4 gap-2 mt-3">
                                        <button type="button" @click="amount = {{ $paymentMethod->min_amount }}" class="bg-gray-100 border border-gray-300 rounded-lg py-2 text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                            ₺{{ number_format($paymentMethod->min_amount, 0) }}
                                        </button>
                                        <button type="button" @click="amount = {{ $paymentMethod->min_amount * 5 }}" class="bg-gray-100 border border-gray-300 rounded-lg py-2 text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                            ₺{{ number_format($paymentMethod->min_amount * 5, 0) }}
                                        </button>
                                        <button type="button" @click="amount = {{ $paymentMethod->min_amount * 10 }}" class="bg-gray-100 border border-gray-300 rounded-lg py-2 text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                            ₺{{ number_format($paymentMethod->min_amount * 10, 0) }}
                                        </button>
                                        <button type="button" @click="amount = {{ $paymentMethod->min_amount * 20 }}" class="bg-gray-100 border border-gray-300 rounded-lg py-2 text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                            ₺{{ number_format($paymentMethod->min_amount * 20, 0) }}
                                        </button>
                                    </div>
                                </div>

                                <button type="button" @click="nextStep()" :disabled="!isAmountValid()" class="w-full btn-primary text-white py-4 rounded-lg font-bold text-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-arrow-right mr-2"></i>Devam Et
                                </button>
                            </div>

                            <!-- Step 2: Payment Details and Confirmation -->
                            <div x-show="step === 2" class="step-content" x-cloak>
                                <h3 class="text-xl font-bold mb-4 text-gray-900">2. Ödeme Bilgileri</h3>
                                
                                <div class="bg-green-50 p-4 rounded-lg mb-6 border border-green-200">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-green-700 mb-2">₺<span x-text="parseFloat(amount).toFixed(2)"></span></div>
                                        <div class="text-sm text-gray-600">Yatırım Tutarı</div>
                                    </div>
                                </div>

                            <!-- Bank Details (if bank transfer) -->
                            @if($paymentMethod->method_code === 'bank_transfer' && $paymentMethod->bank_details)
                                <div class="bg-blue-50 p-6 rounded-lg mb-6 border border-blue-200">
                                    <h4 class="font-bold mb-4 text-center text-blue-900 text-xl">Banka Bilgileri</h4>
                                    <div class="grid grid-cols-1 gap-4">
                                        @if(isset($paymentMethod->bank_details['bank_name']))
                                            <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
                                                <span class="text-gray-600 font-medium">Banka:</span>
                                                <span class="text-blue-900 font-bold">{{ $paymentMethod->bank_details['bank_name'] }}</span>
                                                <button type="button" onclick="copyToClipboard('{{ $paymentMethod->bank_details['bank_name'] }}')" class="copy-btn bg-gray-100 p-2 rounded-lg border border-gray-300 text-gray-600">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if(isset($paymentMethod->bank_details['bank_iban']))
                                            <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
                                                <span class="text-gray-600 font-medium">IBAN:</span>
                                                <span class="text-blue-900 font-mono font-bold text-sm">{{ $paymentMethod->bank_details['bank_iban'] }}</span>
                                                <button type="button" onclick="copyToClipboard('{{ $paymentMethod->bank_details['bank_iban'] }}')" class="copy-btn bg-gray-100 p-2 rounded-lg border border-gray-300 text-gray-600">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if(isset($paymentMethod->bank_details['account_holder']))
                                            <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
                                                <span class="text-gray-600 font-medium">Hesap Sahibi:</span>
                                                <span class="text-blue-900 font-bold">{{ $paymentMethod->bank_details['account_holder'] }}</span>
                                                <button type="button" onclick="copyToClipboard('{{ $paymentMethod->bank_details['account_holder'] }}')" class="copy-btn bg-gray-100 p-2 rounded-lg border border-gray-300 text-gray-600">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                            <span class="text-gray-600 font-medium">Açıklama (Boş bırakın):</span>
                                            <span class="text-orange-600 font-bold">{{ $user->name }}</span>
                                            <button type="button" onclick="copyToClipboard('{{ $user->name }}')" class="copy-btn bg-gray-100 p-2 rounded-lg border border-gray-300 text-gray-600">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                                <!-- User Message -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium mb-2 text-gray-700">Ek Mesaj (Opsiyonel)</label>
                                    <textarea x-model="userMessage" 
                                              rows="3"
                                              class="w-full bg-white border-2 border-gray-300 rounded-lg px-4 py-3 text-gray-900 input-field transition-all"
                                              placeholder="Yatırım işleminiz hakkında ek bilgi veya notlarınız..."></textarea>
                                </div>

                                <!-- Instructions -->
                                @if($paymentMethod->instructions)
                                    <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                                        <h5 class="font-bold text-blue-700 mb-2">
                                            <i class="fas fa-info-circle mr-2"></i>Önemli Bilgiler
                                        </h5>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $paymentMethod->instructions }}</p>
                                    </div>
                                @endif

                                <!-- Navigation Buttons -->
                                <div class="flex gap-4">
                                    <button type="button" @click="prevStep()" class="flex-1 bg-gray-600 text-white py-4 rounded-lg font-bold text-xl hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-arrow-left mr-2"></i>Geri
                                    </button>
                                    <button type="button" @click="submitPayment()" class="flex-1 btn-primary text-white py-4 rounded-lg font-bold text-xl">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        @if($paymentMethod->method_code === 'bank_transfer')
                                            Yatırım Yap
                                        @else
                                            Ödemeyi Başlat
                                        @endif
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Success -->
                            <div x-show="step === 3" class="step-content" x-cloak>
                                <div class="text-center py-8">
                                    <div class="text-6xl text-green-600 mb-4">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">İşleminiz Alındı!</h3>
                                    <p class="text-gray-600 mb-6">
                                        ₺<span x-text="parseFloat(amount).toFixed(2)"></span> tutarındaki yatırım talebiniz başarıyla alındı.
                                        <br>Admin onayından sonra hesabınıza yansıyacaktır.
                                    </p>
                                    <p class="text-sm text-gray-500 mb-6">
                                        Referans No: <span x-text="referenceNumber" class="font-mono font-bold"></span>
                                    </p>
                                    <button type="button" onclick="window.location.href = '/'" class="btn-primary text-white px-8 py-3 rounded-lg font-bold">
                                        <i class="fas fa-home mr-2"></i>Ana Sayfaya Dön
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden form for submission -->
                            <form x-ref="paymentForm" method="POST" action="{{ route('payment.process', $paymentMethod) }}" style="display: none;">
                                @csrf
                                <input type="hidden" name="amount" x-bind:value="amount">
                                <input type="hidden" name="user_message" x-bind:value="userMessage">
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Sidebar -->
                <div class="space-y-6">
                    <!-- Current Balance -->
                    <div class="payment-card rounded-xl p-6 border">
                        <h3 class="text-xl font-bold mb-4 text-gray-900">Mevcut Bakiye</h3>
                        @php
                            $userWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                        @endphp
                        <div class="text-center space-y-3">
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-2xl font-bold text-green-700">₺{{ number_format($userWallet->balance ?? 0, 2) }}</div>
                                <div class="text-sm text-gray-600">Ana Bakiye</div>
                            </div>
                            
                            @if($userWallet && $userWallet->bonus_balance > 0)
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="text-xl font-bold text-blue-700">₺{{ number_format($userWallet->bonus_balance, 2) }}</div>
                                    <div class="text-sm text-gray-600">Bonus Bakiye</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="payment-card rounded-xl p-6 border">
                        <h3 class="text-xl font-bold mb-4 text-gray-900">{{ $paymentMethod->name }} Bilgileri</h3>
                        
                        @if($paymentMethod->image)
                            <div class="w-full h-24 bg-gray-100 rounded-lg overflow-hidden mb-4">
                                <img src="{{ $paymentMethod->image_url }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-cover object-center">
                            </div>
                        @endif
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Min. Tutar:</span>
                                <span class="text-blue-700 font-bold">₺{{ number_format($paymentMethod->min_amount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Maks. Tutar:</span>
                                <span class="text-blue-700 font-bold">₺{{ number_format($paymentMethod->max_amount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Komisyon:</span>
                                <span class="font-bold {{ $paymentMethod->commission_rate > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $paymentMethod->commission_display }}
                                </span>
                            </div>
                            
                            @if($paymentMethod->processing_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">İşlem Süresi:</span>
                                    <span class="text-green-600 font-bold">{{ $paymentMethod->processing_time }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Deposits -->
                    <div class="payment-card rounded-xl p-6 border">
                        <h3 class="text-xl font-bold mb-4 text-gray-900">Son Yatırımlarınız</h3>
                        <div class="space-y-3">
                            @forelse($user->deposits()->latest()->take(3)->get() as $deposit)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ ucfirst($deposit->method) }}</div>
                                        <div class="text-xs text-gray-600">{{ $deposit->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold {{ $deposit->status === 'completed' ? 'text-green-600' : ($deposit->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            ₺{{ number_format($deposit->amount, 2) }}
                                        </div>
                                        <div class="text-xs {{ $deposit->status === 'completed' ? 'text-green-600' : ($deposit->status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ ucfirst($deposit->status) }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-600 text-center py-4">Henüz yatırım yapmadınız</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="payment-card rounded-xl p-6 border text-center">
                        <div class="text-blue-600 text-3xl mb-3">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="font-bold mb-2 text-gray-900">Yardıma mı ihtiyacınız var?</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $paymentMethod->name }} ile ilgili sorularınız için</p>
                        <button class="btn-primary px-4 py-2 rounded-lg font-medium text-white">
                            <i class="fas fa-comments mr-2"></i>Canlı Destek
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function paymentForm() {
    return {
        step: 1,
        amount: '',
        userMessage: '',
        referenceNumber: '',
        minAmount: {{ $paymentMethod->min_amount }},
        maxAmount: {{ $paymentMethod->max_amount }},

        init() {
            // Initialize
        },

        nextStep() {
            if (this.isAmountValid()) {
                this.step = 2;
            }
        },

        prevStep() {
            if (this.step > 1) {
                this.step--;
            }
        },

        isAmountValid() {
            const amt = parseFloat(this.amount);
            return amt >= this.minAmount && amt <= this.maxAmount;
        },

        async submitPayment() {
            try {
                const form = this.$refs.paymentForm;
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    this.referenceNumber = result.reference_number || 'DEP-' + Date.now();
                    this.step = 3;
                    
                    // Auto redirect after 5 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 5000);
                } else {
                    alert(result.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            } catch (error) {
                console.error('Payment submission error:', error);
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    };
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Temporary feedback
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-green-600"></i>';
        setTimeout(() => {
            btn.innerHTML = original;
        }, 1000);
    });
}
</script>
</body>
</html>

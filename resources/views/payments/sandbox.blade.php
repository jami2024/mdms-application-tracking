<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $payment->method }} চেকআউট (স্যান্ডবক্স) · এমডিএমএস</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-4">
            <span class="inline-block text-xs px-3 py-1 rounded-none bg-amber-100 text-amber-700">স্যান্ডবক্স মোড — কোনো প্রকৃত পেমেন্ট গেটওয়ে কনফিগার করা নেই</span>
        </div>
        <div class="bg-white rounded-none shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">{{ $payment->method }} চেকআউট</p>
            <p class="text-2xl font-semibold text-slate-800 mt-1 mb-1">৳ {{ number_format($payment->amount, 2) }}</p>
            <p class="text-xs text-slate-400 font-mono mb-4">Ref: {{ $payment->reference }}</p>
            <p class="text-sm text-slate-600 mb-4">{{ $payment->description }}</p>

            <form method="POST" action="{{ route('payments.callback.success', $payment) }}">
                @csrf
                <button class="w-full px-4 py-2 rounded-none bg-emerald-600 text-white text-sm hover:bg-emerald-700 mb-2">সফল পেমেন্ট সিমুলেট করুন</button>
            </form>
            <form method="POST" action="{{ route('payments.callback.fail', $payment) }}">
                @csrf
                <button class="w-full px-4 py-2 rounded-none border border-red-200 text-red-600 text-sm hover:bg-red-50">ব্যর্থ পেমেন্ট সিমুলেট করুন</button>
            </form>
        </div>
        <p class="text-center text-xs text-slate-400 mt-4">
            Switch <code>PAYMENT_GATEWAY_DRIVER=sslcommerz</code> in .env to use the real gateway once you have credentials.
        </p>
    </div>
</body>
</html>

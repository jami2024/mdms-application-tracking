@extends('layouts.admin')
@section('title', 'ড্যাশবোর্ড')
@section('content')
<div class="space-y-6">
    <p class="text-xs text-slate-400">হোম / ড্যাশবোর্ড</p>

    <div>
        <h2 class="text-2xl font-bold text-slate-800">স্বাগতম, {{ $user->name }}</h2>
        <p class="text-sm text-slate-500 mt-1">পোর্টাল জুড়ে সাম্প্রতিক কার্যক্রমের সারসংক্ষেপ।</p>
    </div>

    @if(Auth::user()->designation_id != env('FRONT_DESK_DESIGNATION'))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-amber-400 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">অপেক্ষমাণ আবেদন</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['pending_applications'] }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-emerald-500 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">মোট রাজস্ব</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">৳ {{ number_format($stats['total_revenue']) }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-blue-500 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">অপেক্ষমাণ পেমেন্ট</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['pending_payments'] }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-slate-800 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">সক্রিয় সার্টিফিকেট</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['active_certificates'] }}</p>
            </div>
        </div>

    @else

        <!-- -------------------------------- FRONT DESK DASHBOARD STATISTIC CART ----------------------------- -->

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-amber-400 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">অপেক্ষমাণ আবেদন</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['active_certificates'] }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-blue-500 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">সাবমিট সম্পন্ন আবেদন</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ number_format($stats['pending_applications']) }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-emerald-500 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">অনুমোদিত আবেদন</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['pending_applications'] }}</p>
            </div>
            <div class="bg-white rounded-none border border-slate-200 border-t-4 border-t-red-600 p-5 shadow-sm">
                <p class="text-[16px] font-semibold text-slate-400 uppercase tracking-wide">বাতিলকৃত আবেদন</p>
                <p class="text-2xl font-bold text-slate-800 mt-1.5">{{ $stats['pending_payments'] }}</p>
            </div>
        </div>
    @endif

    @if(Auth::user()->designation_id!=env('FRONT_DESK_DESIGNATION'))
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white rounded-none border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-800 mb-3">রাজস্ব — গত ৬ মাস</p>
                <canvas id="revenueChart" height="90"></canvas>
            </div>
            <div class="bg-white rounded-none border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-800 mb-3">স্ট্যাটাস অনুযায়ী আবেদন</p>
                <canvas id="statusChart" height="180"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-none border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-800 mb-3">সাম্প্রতিক আবেদন</p>
                <div class="space-y-1">
                    @forelse($recentApplications as $app)
                        <a href="{{ route('applications.show', $app) }}" class="flex items-center justify-between text-sm py-2.5 border-b border-slate-50 last:border-0 hover:bg-slate-50 -mx-2 px-2 rounded-none">
                            <div>
                                <p class="font-medium text-slate-700">{{ $app->application_no }}</p>
                                <p class="text-xs text-slate-400">{{ $app->applicant->name }} · {{ $app->currentStep->step_name ?? '—' }}</p>
                            </div>
                            @php $statusBn = ['draft'=>'খসড়া','submitted'=>'জমাকৃত','in_review'=>'পর্যালোচনাধীন','returned'=>'ফেরত','approved'=>'অনুমোদিত','rejected'=>'প্রত্যাখ্যাত']; @endphp
                            <span class="text-xs px-2.5 py-1 rounded-none bg-slate-100 text-slate-500">{{ $statusBn[$app->status] ?? $app->status }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400 py-4">এখনো কোনো আবেদন নেই।</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-none border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-800 mb-3">বিজ্ঞপ্তি</p>
                <div class="space-y-1">
                    @forelse($notifications as $n)
                        <div class="text-sm py-2.5 border-b border-slate-50 last:border-0">
                            <p class="text-slate-700">{{ $n->data['message'] }}</p>
                            <p class="text-xs text-slate-400">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-4">সব বিজ্ঞপ্তি দেখা হয়ে গেছে।</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueChart->keys()) !!},
            datasets: [{ label: 'রাজস্ব (টাকা)', data: {!! json_encode($revenueChart->values()) !!}, backgroundColor: '#059669', borderRadius: 8 }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($applicationsByStatus->keys()) !!},
            datasets: [{ data: {!! json_encode($applicationsByStatus->values()) !!}, backgroundColor: ['#059669', '#dc2626', '#d97706', '#3b82f6', '#94a3b8', '#0b1220'] }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } } }
    });
</script>
@endsection

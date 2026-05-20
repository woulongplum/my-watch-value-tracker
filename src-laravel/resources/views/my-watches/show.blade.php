<!DOCTYPE html>
<html lang="ja" class="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $watch->model_name }} - コレクション詳細</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #0f172a;
      color: #f8fafc;
    }

    .glass-card {
      background: rgba(30, 41, 59, 0.7);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
  </style>
</head>

<body class="p-6">
  <div class="max-w-6xl mx-auto space-y-6">

    {{-- ナビゲーションエリア --}}
    <div class="flex justify-between items-center">
      <a href="{{ route('my-watches.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-amber-500 transition-colors group">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        コレクション一覧に戻る
      </a>

      {{-- 編集ページへの導線 --}}
      <a href="{{ route('my-watches.edit', $watch->id) }}" class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg border border-gray-700 transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        この時計の情報を編集する
      </a>
    </div>

    {{-- メインコンテンツエリア --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

      {{-- 左側：画像セクション（自分でアップロードした画像） --}}
      <div class="glass-card rounded-2xl p-6 flex items-center justify-center bg-gray-900/50 min-h-[400px] relative overflow-hidden">
        @if($watch->image_path)
        <img src="{{ asset('storage/' . $watch->image_path) }}" class="max-h-[380px] w-auto object-contain rounded-xl" alt="{{ $watch->model_name }}">
        @else
        <div class="w-full h-full flex flex-col items-center justify-center text-gray-600 space-y-2">
          <svg class="w-12 h-12 stroke-current opacity-40" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          <span class="font-mono text-xs">No Image Registered</span>
        </div>
        @endif
        
      </div>

      {{-- 右側：資産ステータス詳細 --}}
      <div class="space-y-6">

        {{-- 基本情報 --}}
        <div class="glass-card rounded-2xl p-6 space-y-4">
          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-amber-500 mb-1">{{ $watch->brand->name }}</p>
            <h1 class="text-2xl font-bold leading-tight text-gray-100">{{ $watch->model_name }}</h1>
            <p class="text-xs font-mono text-gray-500 mt-1">型番:{{ $watch->reference_number }}</p>
          </div>

          <div class="border-t border-gray-700/60 pt-4 grid grid-cols-2 gap-4">
            <div>
              <span class="text-[11px] text-gray-400 block mb-0.5">シリアルナンバー</span>
              <span class="text-sm font-mono font-bold text-gray-200">{{ $watch->serial_number ?? '未登録' }}</span>
            </div>
            <div>
              <span class="text-[11px] text-gray-400 block mb-0.5">購入日</span>
              <span class="text-sm font-mono font-bold text-gray-200">{{ $watch->purchase_date }}</span>
            </div>
          </div>
        </div>

        {{-- 資産価値・損益メーター（ここが主役！） --}}
        <div class="glass-card rounded-2xl p-6 space-y-4">
          <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 border-b border-gray-800 pb-2">Asset Valuation</h3>

          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-400">購入金額</span>
              <span class="text-lg font-mono font-semibold text-gray-300">¥{{ number_format($watch->purchase_price) }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-400">現在の市場平均価格</span>
              <span class="text-lg font-mono font-bold text-amber-400">¥{{ number_format($watch->market_average) }}</span>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-gray-700/60 mt-2">
              <span class="text-sm font-bold text-gray-200">現在の評価損益</span>
              <div class="text-right">
                <span class="{{ ($watch->profit_loss ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }} text-2xl font-black font-mono">
                  {{ ($watch->profit_loss ?? 0) >= 0 ? '+' : '' }}¥{{ number_format($watch->profit_loss ?? 0) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- 管理メモ・備考 --}}
        <div class="glass-card rounded-2xl p-6 space-y-2">
          <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400">保管メモ・コンディション</h3>
          <p class="text-sm text-gray-300 leading-relaxed font-sans bg-gray-900/40 p-3 rounded-xl border border-gray-800/60 min-h-[60px]">
            {{ $watch->note ?? 'メモはありません。' }}
          </p>
        </div>

      </div>
    </div>

  </div>
</body>

</html>

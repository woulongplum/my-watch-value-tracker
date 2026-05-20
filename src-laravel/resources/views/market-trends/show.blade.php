<!DOCTYPE html>
<html lang="ja" class="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $trend->model_name }} - Market Trend</title>
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

    {{-- ナビゲーション・戻るボタン --}}
    <div class="flex justify-between items-center">
      <a href="{{ route('my-watches.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-amber-500 transition-colors group">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        コレクション一覧に戻る
      </a>

      {{-- 🌟 3. ハッキング対策・デバッグ用ID（一般画面からは見えないようBladeコメントアウト化） --}}
      {{-- デバッグ時にソースコード上（F12）からも完全に隠したい場合は、このBladeコメント形式が安全です --}}
      {{-- <span class="text-xs font-mono text-gray-600">MARKET DATA ID: {{ $trend->id }}</span> --}}
    </div>

    {{-- メインコンテンツエリア --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

      {{-- 左側：画像セクション --}}
      <div class="glass-card rounded-2xl p-6 flex items-center justify-center bg-white min-h-[400px] relative group overflow-hidden">
        @if($trend->image_url)
        <img src="{{ $trend->image_url }}" class="max-h-[380px] w-auto object-contain group-hover:scale-105 transition-transform duration-500" alt="{{ $trend->item_name }}">
        @else
        <div class="text-gray-400 font-mono">No Image</div>
        @endif

      </div>

      {{-- 右側：時計ステータス詳細 --}}
      <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 space-y-4">
          <div>
            {{-- 🌟 1. 商品コンディション（状態）バッジの追加 --}}
            <div class="flex items-center gap-2 mb-2">
              <p class="text-xs font-bold uppercase tracking-widest text-amber-500">商品名</p>
              @if(strtoupper($trend->item_condition) === 'NEW')
              <span class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-green-500/20 text-green-400 border border-green-500/30 uppercase tracking-wider">新品 (NEW)</span>
              @elseif(strtoupper($trend->item_condition) === 'USED')
              <span class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase tracking-wider">中古 (USED)</span>
              @else
              <span class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-gray-500/20 text-gray-400 border border-gray-500/30 uppercase tracking-wider">{{ $trend->item_condition }}</span>
              @endif
            </div>
            <h1 class="text-2xl font-bold leading-tight text-gray-100">{{ $trend->model_name }}</h1>
          </div>

          <div class="border-t border-gray-700/60 pt-4 space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-400">リファレンス番号 (REF)</span>
              <span class="text-sm font-mono font-bold bg-gray-800 px-3 py-1 rounded border border-gray-700/50 text-amber-400">{{ $trend->ref_number ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between items-center pt-2">
              <span class="text-sm text-gray-400">現在の市場価格</span>
              <div class="text-right">
                <span class="text-2xl font-extrabold font-mono text-white">¥{{ number_format($trend->price) }}</span>
                <span class="text-[10px] text-gray-400 block font-mono">(税込み)</span>
              </div>
            </div>
          </div>
        </div>

        {{-- アクション・外部リンクカード --}}
        <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-amber-500/10 to-transparent border-amber-500/20 space-y-4">
          <h3 class="text-sm font-bold text-gray-300">市場データについて</h3>
          <p class="text-xs text-gray-400 leading-relaxed">
            このデータは、楽天市場の公開情報から自動取得したリアルタイムの価格情報です。独自のデータ処理により、最新の市場相場を分かりやすくお届けしています。
          </p>

          {{-- 🌟 2. 楽天市場へのリンク（既存のロジックをそのまま活かしています） --}}
          @if($trend->item_url)
          <a href="{{ $trend->item_url }}" target="_blank" rel="noopener noreferrer" class="w-full bg-amber-500 hover:bg-amber-600 text-black font-extrabold py-3 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 group text-sm">
            実際の楽天市場の商品ページを見る
            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
          </a>
          @endif
        </div>

      </div>
    </div>

  </div>
</body>

</html>

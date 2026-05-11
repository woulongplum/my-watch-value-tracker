<!DOCTYPE html>
<html lang="ja" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChronoValue Tracker</title>
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

    <div class="flex flex-col lg:flex-row gap-6 max-w-[1600px] mx-auto items-start">

        <div class="flex-1 w-full space-y-8">
            <header class="mb-8 flex items-end gap-5">
                <h2 class="text-3xl font-bold text-amber-500 tracking-tight leading-none">ChronoValue Tracker</h2>

                <div class="flex items-center gap-3 mb-[2px]"> <span class="h-[1.5px] w-10 bg-amber-500/60"></span>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] leading-none">
                        My Watch Collection
                    </p>
                </div>
            </header>

            <div class="glass-card rounded-2xl p-6 h-64 flex items-center justify-center border-dashed border-2 border-gray-700">
                <p class="text-gray-500 font-mono">過去6ヶ月の資産推移 - [グラフ機能 実装予定]</p>
            </div>

            <section>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">My Collection</h3>
                    <span class="bg-teal-900 text-teal-300 text-[10px] px-3 py-1 rounded-full border border-teal-700">Phase 2 Active: Market Comparison</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($myWatches as $watch)
                    <div class="glass-card rounded-xl overflow-hidden group hover:border-amber-500/50 transition-all">
                        <div class="h-40 bg-gray-800 relative overflow-hidden">
                            @if($watch->image_path)
                            <img src="{{ asset('storage/' . $watch->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600 text-xs">No Image</div>
                            @endif

                        </div>

                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">{{ $watch->brand->name }}</p>
                                <h4 class="font-bold text-sm truncate">{{ $watch->model_name }}</h4>
                                <p class="text-[10px] font-mono text-gray-500">REF: {{ $watch->reference_number }}</p>
                            </div>

                            <div class="text-[11px] space-y-1 border-t border-gray-700 pt-3 text-gray-400 font-medium">
                                <div class="flex justify-between">
                                    <span>購入価格</span>
                                    <span class="text-white font-mono">¥{{ number_format($watch->purchase_price) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>市場平均価格</span>
                                    <span class="text-white font-bold font-mono">¥{{ number_format($watch->market_average) }}</span>
                                </div>

                                <div class="flex justify-between items-center pt-1 border-t border-gray-800 mt-1">
                                    <span>評価損益</span>
                                    <span class="{{ ($watch->profit_loss ?? 0) >= 0 ? 'text-green-400' : 'text-red-400' }} font-bold text-sm font-mono"> {{ ($watch->profit_loss ?? 0) >= 0 ? '+' : '' }}¥{{ number_format($watch->profit_loss ?? 0) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-2 border-t border-gray-800">
                                <button class="text-gray-500 hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <div class="flex gap-3">
                                    <button class="text-gray-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </button>
                                    <button class="text-gray-500 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <section class="pb-10">
                <h3 class="text-xl font-bold mb-4">Market Trends (Go API Showcase)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                    <div class="glass-card rounded-xl overflow-hidden group hover:border-amber-500/50 transition-all">
                        <div class="h-40 bg-gray-800 relative overflow-hidden">
                            <img src="https://placehold.jp/24/1e293b/94a3b8/400x300.png?text=API+Fetching..."
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-50">

                            <span class="absolute top-2 left-2 bg-amber-500 text-black text-[9px] font-extrabold px-2 py-0.5 rounded uppercase">Trending Now</span>
                        </div>

                        <div class="p-4 space-y-2">
                            <div>
                                <p class="text-[10px] text-amber-500 font-mono">Source: Go API Scraper</p>
                                <h4 class="font-bold text-sm truncate">Patek Philippe Nautilus</h4>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-800 pt-2">
                                <span class="text-[10px] text-gray-500">Market Price</span>
                                <span class="text-white font-bold text-sm">¥12,000,000</span>
                            </div>

                        </div>
                    </div>

                </div>
            </section>
        </div>

        <aside class="w-full lg:w-[380px] shrink-0 lg:sticky lg:top-6">
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-1">Watch Management</h2>
                <p class="text-[10px] text-gray-400 mb-6 font-mono uppercase tracking-widest">Add New Watch</p>

                <form action="{{ route('my-watches.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="border-2 border-dashed border-gray-700 rounded-xl p-8 flex flex-col items-center justify-center text-gray-500 hover:border-amber-500 transition-all cursor-pointer relative bg-gray-800/30 group">
                        <svg class="w-8 h-8 mb-2 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <p class="text-[10px] text-center">Drag & Drop Watch Image<br><span class="text-gray-600">(Sony α7C photo check)</span></p>
                        <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 block mb-1">ブランド</label>
                            <select name="brand_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white">
                                <option value="01KQ7M1K3021FQRAX6RR5W6BWJ">Rolex</option>
                                <option value="01KQ7M1K3021FQRAX6RR5W6BWK">Omega</option>
                                <option value="01KQ7M1K3021FQRAX6RR5W6BWL">Tudor</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 block mb-1">モデル名</label>
                            <input type="text" name="model_name" placeholder="例：サブマリーナ デイト" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 block mb-1">型番（リファレンス番号）</label>
                            <input type="text" name="reference_number" placeholder="例：126610LN" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] uppercase font-bold text-gray-500 block">シリアルナンバー</label>
                                <span class="text-[9px] text-gray-600 font-bold bg-gray-700/50 px-1.5 py-0.5 rounded">任意</span>
                            </div>
                            <input type="text" name="serial_number" placeholder="個体番号（未入力でも可）" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 block mb-1">購入金額 (円)</label>
                            <input type="number" name="purchase_price" placeholder="1200000" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white font-mono">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 block mb-1">購入日</label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white font-mono">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] uppercase font-bold text-gray-500 block">備考・メモ</label>
                                <span class="text-[9px] text-gray-600 font-bold bg-gray-700/50 px-1.5 py-0.5 rounded">任意</span>
                            </div>
                            <textarea name="note" rows="3" placeholder="コンディションや付属品のエピソードなど（未入力でも可）" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-sm focus:ring-1 focus:ring-amber-500 outline-none text-white"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-black font-extrabold py-4 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex flex-col items-center leading-none mt-6">
                        <span class="text-sm">コレクションに追加する</span>
                        <span class="text-[9px] mt-1 opacity-70 font-medium">Phase 3 次回：グラフ機能実装</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

</body>

</html>

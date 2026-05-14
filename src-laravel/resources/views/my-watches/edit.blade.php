<!DOCTYPE html>
<html lang="ja" class="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Edit Watch</title>
</head>

<body class="p-6 bg-[#0f172a] text-white">
  <div class="max-w-2xl mx-auto glass-card p-8 rounded-2xl bg-gray-800/50">
    <h2 class="text-2xl font-bold mb-6 text-amber-500">時計情報の編集</h2>
    <form action="{{ route('my-watches.update', $watch->id) }}" method="post" enctype="multipart/form-data" class="space-y-4">
      @csrf
      @method('PATCH')

      <!-- ブランド選択 -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">ブランド</label>
        <select name="brand_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
          @foreach($brands as $brand)
          <option value="{{ $brand->id }}" {{ old('brand_id', $watch->brand_id) == $brand->id ? 'selected' : '' }}>
            {{ $brand->name }}
          </option>
          @endforeach
        </select>
      </div>

      <!-- モデル名 -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">モデル名</label>
        <input type="text" name="model_name" value="{{ old('model_name', $watch->model_name) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
      </div>

      <!-- リファレンス番号 -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">リファレンス番号</label>
        <input type="text" name="reference_number" value="{{ old('reference_number', $watch->reference_number) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
      </div>

      <!-- シリアル番号 -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">シリアル番号</label>
        <input type="text" name="serial_number" value="{{ old('serial_number', $watch->serial_number) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <!-- 購入価格 -->
        <div>
          <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">購入価格 (¥)</label>
          <input type="number" name="purchase_price" value="{{ old('purchase_price', $watch->purchase_price) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
        </div>
        <!-- 購入日 -->
        <div>
          <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">購入日</label>
          <input type="date" name="purchase_date" value="{{ old('purchase_date', $watch->purchase_date) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">
        </div>
      </div>

      <!-- 備考 -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">備考</label>
        <textarea name="note" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5 text-white">{{ old('note', $watch->note) }}</textarea>
      </div>

      <!-- 画像（任意） -->
      <div>
        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">画像を変更する場合</label>
        <input type="file" name="image" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2 text-sm text-gray-400 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-500 file:text-black hover:file:bg-amber-600">
        @if($watch->image_path)
        <p class="mt-2 text-xs text-gray-500 italic">※現在設定されている画像があります</p>
        @endif
      </div>

      <!-- ボタン -->
      <div class="flex gap-4 pt-4">
        <button type="submit" class="flex-1 bg-amber-500 text-black font-bold py-3 rounded-xl hover:bg-amber-600 transition-all">
          更新を保存する
        </button>
        <a href="{{ route('my-watches.index') }}" class="flex-1 bg-gray-700 text-white font-bold py-3 rounded-xl text-center hover:bg-gray-600 transition-all">
          キャンセル
        </a>
      </div>
    </form>
  </div>
</body>

</html>

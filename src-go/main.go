package main

import (
	"fmt"
	"log"
	"sync"
	"time"

	"my-watch-value-tracker/pkg/database"
	"my-watch-value-tracker/pkg/models"
	"my-watch-value-tracker/pkg/repository"
	"my-watch-value-tracker/pkg/service"

	"github.com/joho/godotenv"
)

// Job は並列処理の最小単位（1件の商品データ）を定義します
type Job struct {
	BrandID    string
	ItemDetail models.RakutenItem
}

// worker は並列で動作する作業員です。チャネルから仕事を受け取り、加工と保存を行います
func worker(jobs <-chan Job, watchSvc *service.WatchService, priceRepo *repository.MarketPriceRepository, wg *sync.WaitGroup) {
	// 関数終了時にWaitGroupのカウンターを減らし、メイン処理に完了を伝えます
	defer wg.Done()

	for job := range jobs {
		// 商品データから型番抽出・コンディション判定を行い、保存用モデルに変換
		marketPrice := watchSvc.CalculateMarketPrice(job.ItemDetail, job.BrandID)
		
		// 該当なし（型番不明や除外ワードなど）の場合はスキップ
		if marketPrice == nil {
			continue
		}

		// データベースへ保存
		if err := priceRepo.Create(marketPrice); err != nil {
			log.Printf("❌ [%s] 保存失敗: %v\n", job.BrandID, err)
		}
	}
}

func main() {
	// 1. 環境設定とデータベースの初期化
	if err := godotenv.Load(); err != nil {
		log.Println("ℹ️ .envファイルが見つかりません。環境変数を使用します。")
	}

	db, err := database.InitDB()
	if err != nil {
		log.Fatalf("❌ データベース接続失敗: %v", err)
	}

	// 2. 依存関係の初期化（各サービスとリポジトリ）
	brandRepo := repository.NewBrandRepository(db)
	priceRepo := repository.NewMarketPriceRepository(db)
	rakutenSvc := service.NewRakutenService()
	watchSvc := service.NewWatchService()

	// 3. 処理対象ブランドの取得
	brands, err := brandRepo.FetchAll()
	if err != nil {
		log.Fatalf("❌ ブランド一覧の取得失敗: %v", err)
	}

	// --- 4. 並列処理（Worker Pool）のセットアップ ---
	var wg sync.WaitGroup
	jobs := make(chan Job, 100) // 仕事を溜めるバッファ付きチャネル
	workerCount := 3            // 並列実行数

	// 指定した人数分のWorkerを起動
	for w := 1; w <= workerCount; w++ {
		wg.Add(1)
		go worker(jobs, watchSvc, priceRepo, &wg)
	}

	// 5. ブランドごとにデータを取得し、ジョブを投入
	fmt.Println("🚀 市場価格の取得プロセスを開始します...")

	for _, brand := range brands {
		fmt.Printf("\n🔎 【%s】を取得中...\n", brand.Name)

		rakutenResponse, err := rakutenSvc.FetchItems(brand.Name)
		if err != nil {
			log.Printf("⚠️ %s の取得エラー: %v", brand.Name, err)
			continue
		}

		// 取得した商品を一件ずつジョブとして投入
		if len(rakutenResponse.Items) > 0 {
			for _, packageData := range rakutenResponse.Items {
				jobs <- Job{
					BrandID:    brand.ID,
					ItemDetail: packageData.Item,
				}
			}
			fmt.Printf("📥 %s: %d件を処理キューに追加しました。\n", brand.Name, len(rakutenResponse.Items))
		}

		// APIへの負荷を考慮し、ブランドごとにインターバルを設ける
		time.Sleep(1 * time.Second)
	}

	// --- 6. 終了処理 ---
	
	// 全てのジョブ投入が終わったことをWorkerに通知（チャネルを閉じる）
	close(jobs)

	fmt.Println("\n⏳ 進行中のすべての保存処理を待機しています...")
	
	// 全Workerの処理が完了するまでブロック
	wg.Wait()

	fmt.Println("\n✨ 全てのブランド処理が正常に完了しました。")
}

package main

import (
	"fmt"
	"log"

	"my-watch-value-tracker/pkg/database"
	"my-watch-value-tracker/pkg/models"
	"my-watch-value-tracker/pkg/repository"
	"my-watch-value-tracker/pkg/service"
	"my-watch-value-tracker/pkg/utils"

	"regexp"
	"strings"
	"time"

	"github.com/joho/godotenv"
)

// --- メイン処理 ---

func main() {
	// 1. 環境設定の読み込み
	godotenv.Load()

	// 2. データベース接続
	fmt.Println("🔍 データベースに接続しています...")

	db, err := database.InitDB()
	if err != nil {
		log.Fatalf("❌ データベース接続失敗: %v", err)
	}

	// 3. 処理対象のブランド一覧をDBから取得
	brandRepo := repository.NewBrandRepository(db)
	priceRepo := repository.NewMarketPriceRepository(db)
	rakutenSvc := service.NewRakutenService()

	brands,err := brandRepo.FetchAll()
	if err != nil {
    log.Fatalf("❌ ブランド一覧の取得失敗: %v", err)
}

	// 4. 楽天APIの共通設定
	

	re := regexp.MustCompile(`([0-9]{5,6}[A-Z]*|[0-9]{3}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{3}|SO[0-9]{2}[A-Z][0-9]{3}[-][0-9]{3}|[0-9]{4,5}[/][0-9])`)
	// 5. 各ブランドごとにループ処理を実行
	for _, brand := range brands {
		fmt.Printf("\n🚀 【%s】の価格取得を開始します...\n", brand.Name)

		
		

		rakutenResponse, err := rakutenSvc.FetchItems(brand.Name)
		if err != nil {
			log.Printf("⚠️ %s のデータ取得失敗: %v", brand.Name, err)
			continue
		}

		// データ保存処理
		if len(rakutenResponse.Items) > 0 {
			successCount := 0

			// リストの中から「1つ分の商品パッケージ」を取り出す
			for _, packageData := range  rakutenResponse.Items {
				
				// パッケージの中から「時計データ本体」を取り出す
				watch := packageData.Item

				excludeKeywords := []string{"ドライバー", "工具", "ベルト", "バネ棒", "ブレスレット", "バンド", "コマ"}
				isExclude := false

				for _, k := range excludeKeywords {
					if strings.Contains(watch.ItemName,k) {
						isExclude = true
						break
					}
				}
				if isExclude {
					continue
				}

				ref := re.FindString(watch.ItemName)

				// フィルタリング: リファレンス番号がなく、価格が安すぎるものは除外
				if ref == ""  {
					continue
				}

				// コンディション判定
				condition := "USED"
				if strings.Contains(watch.ItemName, "新品") || strings.Contains(watch.ItemName, "未使用") {
					condition = "NEW"
				}

				// 画像URL取得
				imageUrl := ""
				if len(watch.MediumImageUrls) > 0 {
					imageUrl = watch.MediumImageUrls[0].ImageUrl
				}

				// 構造体作成
				marketPrice := models.MarketPrice{
					ID:            utils.GenerateULID(),
					BrandID:       brand.ID, // DBから取得したブランドIDを紐付け
					RefNumber:     ref,
					Price:         watch.ItemPrice,
					ModelName:     watch.ItemName,
					ItemURL:       watch.ItemURL,
					ImageURL:      imageUrl,
					Source:        "rakuten",
					ItemCondition: condition,
				}

				// DBへ保存
				if err := priceRepo.Create(&marketPrice); err != nil {
					log.Printf("❌ 保存失敗: %v\n", err)
					continue
				}
				successCount++
			}
			fmt.Printf("✅ %s: %d件の保存に成功しました。\n", brand.Name, successCount)
		} else {
			fmt.Printf("ℹ️ %s: 該当する商品は見つかりませんでした。\n", brand.Name)
		}

		// APIへの負荷軽減のため、1ブランドごとに待機
		time.Sleep(1 * time.Second)
	}

	fmt.Println("\n✨ 全ブランドの処理が完了しました。")
}

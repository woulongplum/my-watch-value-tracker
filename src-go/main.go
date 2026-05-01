package main

import (
	"encoding/json"
	"fmt"
	"log"
	"my-watch-value-tracker/pkg/utils"
	"net/http"
	"net/url"
	"os"
	"regexp"
	"strings"
	"time"

	"github.com/joho/godotenv"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

// --- 構造体定義 ---

type Brand struct {
	ID        string    `gorm:"primaryKey;size:26"`
	Name      string    `gorm:"not null;unique"`
	OHPeriod  int       `gorm:"column:oh_period;not null"`
	CreatedAt time.Time `gorm:"autoCreateTime"`
	UpdatedAt time.Time `gorm:"autoUpdateTime"`
}

type MarketPrice struct {
	ID            string    `gorm:"primaryKey;size:26"`
	BrandID       string    `gorm:"column:brand_id;size:26"`
	RefNumber     string    `gorm:"column:ref_number"`
	Price         int       `gorm:"column:price"`
	ModelName     string    `gorm:"column:model_name"`
	ItemURL       string    `gorm:"column:item_url"`
	ImageURL      string    `gorm:"column:image_url"`
	Source        string    `gorm:"column:source"`
	ItemCondition string    `gorm:"column:item_condition"`
	CreatedAt     time.Time
	UpdatedAt     time.Time
}

type RakutenItem struct {
	ItemName        string `json:"itemName"`
	ItemPrice       int    `json:"itemPrice"`
	ItemURL         string `json:"itemUrl"`
	MediumImageUrls []struct {
		ImageUrl string `json:"imageUrl"`
	} `json:"mediumImageUrls"`
}

type RakutenResponse struct {
	Items []struct {
		Item RakutenItem `json:"Item"`
	} `json:"Items"`
}

// --- メイン処理 ---

func main() {
	// 1. 環境設定の読み込み
	godotenv.Load()

	// 2. データベース接続
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		os.Getenv("DB_USER"), os.Getenv("DB_PASSWORD"),
		os.Getenv("DB_HOST"), os.Getenv("DB_PORT"), os.Getenv("DB_NAME"))

	fmt.Println("📡 データベースに接続中...")
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatalf("❌ 接続失敗しました: %v", err)
	}

	// 3. 処理対象のブランド一覧をDBから取得
	var brands []Brand
	if err := db.Find(&brands).Error; err != nil {
		log.Fatalf("❌ ブランド一覧の取得失敗: %v", err)
	}

	// 4. 楽天APIの共通設定
	appID := os.Getenv("RAKUTEN_APP_ID")
	accessKey := os.Getenv("RAKUTEN_ACCESS_KEY")
	affiliateID := os.Getenv("RAKUTEN_AFFILIATE_ID")

	re := regexp.MustCompile(`([0-9]{5,6}[A-Z]*|[0-9]{3}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{3}|SO[0-9]{2}[A-Z][0-9]{3}[-][0-9]{3}|[0-9]{4,5}[/][0-9])`)
	// 5. 各ブランドごとにループ処理を実行
	for _, brand := range brands {
		fmt.Printf("\n🚀 【%s】の価格取得を開始します...\n", brand.Name)

		safeKeyword := url.QueryEscape(brand.Name)
		apiURL := fmt.Sprintf(
			"https://openapi.rakuten.co.jp/ichibams/api/IchibaItem/Search/20260401?applicationId=%s&accessKey=%s&affiliateId=%s&keyword=%s&genreId=558929",
			appID, accessKey, affiliateID, safeKeyword,
		)

		// APIリクエスト
		resp, err := http.Get(apiURL)
		if err != nil {
			log.Printf("⚠️ %s のリクエストに失敗しました: %v", brand.Name, err)
			continue
		}

		var rakutenRes RakutenResponse
		if err := json.NewDecoder(resp.Body).Decode(&rakutenRes); err != nil {
			resp.Body.Close()
			log.Printf("⚠️ %s のJSON解析失敗: %v", brand.Name, err)
			continue
		}
		resp.Body.Close()

		// データ保存処理
		if len(rakutenRes.Items) > 0 {
			successCount := 0
			for _, itemWrapper := range rakutenRes.Items {
				item := itemWrapper.Item

				excludeKeywords := []string{"ドライバー", "工具", "ベルト", "バネ棒", "ブレスレット", "バンド", "コマ"}
				isExclude := false

				for _, k := range excludeKeywords {
					if strings.Contains(item.ItemName,k) {
						isExclude = true
						break
					}
				}
				if isExclude {
					continue
				}

				ref := re.FindString(item.ItemName)

				// フィルタリング: リファレンス番号がなく、価格が安すぎるものは除外
				if ref == ""  {
					continue
				}

				// コンディション判定
				condition := "USED"
				if strings.Contains(item.ItemName, "新品") || strings.Contains(item.ItemName, "未使用") {
					condition = "NEW"
				}

				// 画像URL取得
				imageUrl := ""
				if len(item.MediumImageUrls) > 0 {
					imageUrl = item.MediumImageUrls[0].ImageUrl
				}

				// 構造体作成
				marketPrice := MarketPrice{
					ID:            utils.GenerateULID(),
					BrandID:       brand.ID, // DBから取得したブランドIDを紐付け
					RefNumber:     ref,
					Price:         item.ItemPrice,
					ModelName:     item.ItemName,
					ItemURL:       item.ItemURL,
					ImageURL:      imageUrl,
					Source:        "rakuten",
					ItemCondition: condition,
				}

				// DBへ保存
				if err := db.Table("market_prices").Create(&marketPrice).Error; err != nil {
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

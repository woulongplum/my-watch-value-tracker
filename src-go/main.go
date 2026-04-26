package main

import (
	"encoding/json"
	"fmt"
	"log"
	"my-watch-value-tracker/pkg/utils"
	"net/http"
	"net/url"
	"os"
	"time"

	
	"github.com/joho/godotenv"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

type Brand struct {
	ID   string `gorm:"primaryKey;size:26"`
	Name string `gorm:"not null;unique"`
	OHPeriod int `gorm:"column:oh_period;not null"`
	CreatedAt time.Time `gorm:"autoCreateTime"` 
	UpdatedAt time.Time `gorm:"autoUpdateTime"`
}

type RakutenResponse struct {
	Items []struct {
		ItemName  string `json:"itemName"`
		ItemPrice int    `json:"itemPrice"`
		ItemURL   string `json:"itemUrl"`
	} `json:"Items"`
}


func main() {

	// 1. .env 読み込み
	godotenv.Load()

	// 2. データベース接続設定 (保存の前に接続が必要！)
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		os.Getenv("DB_USER"), os.Getenv("DB_PASSWORD"),
		os.Getenv("DB_HOST"), os.Getenv("DB_PORT"), os.Getenv("DB_NAME"))

	fmt.Println("データベースに接続中...")
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatalf("❌ 接続失敗しました: %v", err)
	}

	// 3. 楽天API設定
	appID := os.Getenv("RAKUTEN_APP_ID")
	accessKey := os.Getenv("RAKUTEN_ACCESS_KEY")
	affiliateID := os.Getenv("RAKUTEN_AFFILIATE_ID")

	keyword := "ロレックス"
	safeKeyword := url.QueryEscape(keyword)
	apiURL := fmt.Sprintf(
		"https://openapi.rakuten.co.jp/ichibams/api/IchibaItem/Search/20260401?applicationId=%s&accessKey=%s&affiliateId=%s&keyword=%s&formatVersion=2",
		appID, accessKey, affiliateID, safeKeyword,
	)

	// 4. APIリクエスト送信
	fmt.Println("楽天APIにリクエスト送信中...")
	resp, err := http.Get(apiURL)
	if err != nil {
		log.Fatal("APIリクエストに失敗しました。")
	}
	defer resp.Body.Close()

	var rakutenRes RakutenResponse
	if err := json.NewDecoder(resp.Body).Decode(&rakutenRes); err != nil {
		log.Fatalf("JSONの解析失敗: %v", err)
	}

	// 5. ループで全件保存
	if len(rakutenRes.Items) > 0 {
		fmt.Printf("%d件の商品が見つかりました。保存を開始します...\n", len(rakutenRes.Items))

		for i, item := range rakutenRes.Items {
			// ループの中で毎回新しいULIDを発行
			newID := utils.GenerateULID()

			brand := Brand{
				ID:       newID,
				Name:     item.ItemName,
				OHPeriod: 5,
			}

			result := db.Create(&brand)
			if result.Error != nil {
				fmt.Printf("[%d] ⚠️ スキップ: %s\n", i+1, item.ItemName)
			} else {
				fmt.Printf("[%d] ✅ 保存成功: %s\n", i+1, brand.Name)
			}
		}
	} else {
		fmt.Println("商品が見つかりませんでした。")
	}

	fmt.Println("✅ 全ての処理が完了しました。")
}

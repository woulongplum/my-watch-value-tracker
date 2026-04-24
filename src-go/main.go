package main

import (
	"fmt"
	"log"
	"my-watch-value-tracker/pkg/utils"
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


func main() {
	err := godotenv.Load()
	if err != nil {
		log.Println(".env ファイルが見つかりません。環境変数から直接読み込みます。")
	}

	// 2. 変数を取得
	user := os.Getenv("DB_USER")
	pass := os.Getenv("DB_PASSWORD")
	host := os.Getenv("DB_HOST")
	port := os.Getenv("DB_PORT")
	dbName := os.Getenv("DB_NAME")

	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local",
	user, pass, host, port, dbName)

	fmt.Println("データベースに接続中...")

	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})

	if err != nil {
    		fmt.Printf("❌ 接続失敗しました: %v\n", err)
    		return
  }

	NewID := utils.GenerateULID()

	brand := Brand {
		ID: NewID,
		Name: "Rolex",
	}

	fmt.Println("データを保存中...")
	result := db.Create(&brand)

	if result.Error != nil {
		// すでに 'Rolex' がある場合はエラーになります
		fmt.Printf("❌ 保存失敗: %v\n", result.Error)
	} else {
		fmt.Println("✅ 成功！'Rolex' を保存しました。")
		fmt.Printf("生成されたULID: %s\n", brand.ID)
	}

	sqlDB, _ := db.DB()
	err = sqlDB.Ping()
	if err != nil {
		fmt.Printf("❌ 疎通確認失敗: %v\n", err)
		return
	}

	fmt.Println("✅ 接続成功GoからMySQLにアクセスできました。")
}

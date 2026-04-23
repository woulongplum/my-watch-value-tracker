package main

import (
	"fmt"
	"log"
	"os"
	"gorm.io/driver/mysql"
	"github.com/joho/godotenv"
	"gorm.io/gorm"
)


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

	sqlDB, _ := db.DB()
	err = sqlDB.Ping()
	if err != nil {
		fmt.Printf("❌ 疎通確認失敗: %v\n", err)
		return
	}

	fmt.Println("✅ 接続成功GoからMySQLにアクセスできました。")
}

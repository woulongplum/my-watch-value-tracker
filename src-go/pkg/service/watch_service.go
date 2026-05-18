package service

import (
	"my-watch-value-tracker/pkg/models"
	"my-watch-value-tracker/pkg/utils"
	"regexp"
	"strings"
)

type WatchService struct {
	refRegex        *regexp.Regexp
	excludeKeywords []string
}

// NewWatchService: WatchServiceの初期化（正規表現や除外ワードをセット）
func NewWatchService() *WatchService {
	return &WatchService{
		// main.goにあった正規表現をここに引っ越し
		refRegex: regexp.MustCompile(`([0-9]{5,6}[A-Z]*|[0-9]{3}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{3}|SO[0-9]{2}[A-Z][0-9]{3}[-][0-9]{3}|[0-9]{4,5}[/][0-9])`),
		// 除外したいキーワードリスト
		excludeKeywords: []string{"ドライバー", "工具", "ベルト", "バネ棒", "ブレスレット", "バンド", "コマ"},
	}
}

// CalculateMarketPrice: 楽天の生データからDB保存用の構造体を作成する
// 条件に合わない場合は nil を返す
func (s *WatchService) CalculateMarketPrice(itemDetail models.RakutenItem, brandID string) *models.MarketPrice {
	
	// 1. 除外キーワードのチェック
	for _, k := range s.excludeKeywords {
		if strings.Contains(itemDetail.ItemName, k) {
			return nil
		}
	}

	// 2. 型番（リファレンス）の抽出
	ref := s.refRegex.FindString(itemDetail.ItemName)
	if ref == "" {
		return nil
	}

	// 3. コンディション判定
	condition := "USED"
	if strings.Contains(itemDetail.ItemName, "新品") || strings.Contains(itemDetail.ItemName, "未使用") {
		condition = "NEW"
	}

	// 4. 画像URLの取得
	imageUrl := ""
	if len(itemDetail.MediumImageUrls) > 0 {
		imageUrl = itemDetail.MediumImageUrls[0].ImageUrl

		if strings.Contains(imageUrl,"?_ex=") {
			imageUrl = strings.Split(imageUrl,"?_ex=")[0]
		}
	}

	// 5. DB保存用のモデルに変換して返す
	return &models.MarketPrice{
		ID:            utils.GenerateULID(),
		BrandID:       brandID,
		RefNumber:     ref,
		Price:         itemDetail.ItemPrice,
		ModelName:     itemDetail.ItemName,
		ItemURL:       itemDetail.ItemURL,
		ImageURL:      imageUrl,
		Source:        "rakuten",
		ItemCondition: condition,
	}
}

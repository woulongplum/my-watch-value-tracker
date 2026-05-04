package service

import (
	"my-watch-value-tracker/pkg/models"

	"fmt"
	"encoding/json"
	"net/http"
	"net/url"
	"os"
)


type RakutenService struct {
	appID       string
	accessKey   string
	affiliateID string
}


func NewRakutenService() *RakutenService {
	return  &RakutenService{
		appID: os.Getenv("RAKUTEN_APP_ID"),
		accessKey: os.Getenv("RAKUTEN_ACCESS_KEY"),
		affiliateID: os.Getenv("RAKUTEN_AFFILIATE_ID"),
	}
}

func (s *RakutenService) FetchItems(keyword string) (*models.RakutenResponse,error) {
	safeKeyword := url.QueryEscape(keyword)
	apiURL := fmt.Sprintf(
			"https://openapi.rakuten.co.jp/ichibams/api/IchibaItem/Search/20260401?applicationId=%s&accessKey=%s&affiliateId=%s&keyword=%s&genreId=558929",
			s.appID, s.accessKey, s.affiliateID, safeKeyword,
	)
	
	resp, err := http.Get(apiURL)
		if err != nil {
			return  nil,err
		}

		defer resp.Body.Close()

		var result models.RakutenResponse
		if err := json.NewDecoder(resp.Body).Decode(&result); err != nil {
			return  nil, err
		}
		
	return &result, nil
}

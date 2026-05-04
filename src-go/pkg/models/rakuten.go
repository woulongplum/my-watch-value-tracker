package models


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

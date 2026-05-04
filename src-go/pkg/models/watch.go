package models

import "time"

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

package repository

import (
	"my-watch-value-tracker/pkg/models"

	"gorm.io/gorm"
)



type MarketPriceRepository struct {
	db *gorm.DB
}

func NewMarketPriceRepository(db *gorm.DB) *MarketPriceRepository {
	return &MarketPriceRepository{db: db}
}

func (r *MarketPriceRepository) Create(price *models.MarketPrice) error {
	return  r.db.Table("market_prices").Create(price).Error
}


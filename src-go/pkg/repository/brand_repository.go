package repository

import (
	"my-watch-value-tracker/pkg/models"

	"gorm.io/gorm"
)


type BrandRepository struct {
	db *gorm.DB
}

func NewBrandRepository(db *gorm.DB) *BrandRepository {
	return  &BrandRepository{db:db}
}

func (r *BrandRepository) FetchAll() ([]models.Brand,error) {
	var brands []models.Brand
	err := r.db.Find(&brands).Error
	return brands, err
}

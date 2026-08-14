# Bugfix Requirements Document

## Introduction

این سند رفع باگ در سیستم نظرات محصولات (Product Review System) فروشگاه Velora را شرح می‌دهد. باگ اصلی در فرآیند تایید نظرات توسط ادمین رخ می‌دهد که منجر به سه مشکل می‌شود:

1. **نمایش نام کاربر:** به جای نام واقعی کاربر، عبارت "کاربر ناشناس" نمایش داده می‌شود
2. **تعداد نظرات:** بعد از تایید نظر، تعداد نظرات محصول (`rating_count`) به‌روزرسانی نمی‌شود و صفر باقی می‌ماند
3. **امتیاز محصول:** میانگین امتیاز ستاره‌ها (`rating_avg`) برای محصول به‌روزرسانی نمی‌شود

این مشکلات در صفحه جزئیات محصول (product single page) و کارت محصول (product card) مشاهده می‌شوند.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN a review is approved by admin THEN the system displays "کاربر ناشناس" instead of the reviewer's actual name (full_name or username)

1.2 WHEN a review is approved by admin THEN the system does not update the product's `rating_count` field and it remains 0

1.3 WHEN a review is approved by admin THEN the system does not update the product's `rating_avg` field and no star rating is displayed

1.4 WHEN the ProductModel::getReviews() method fetches reviews THEN the system returns only review table data without joining the users table to retrieve author names

1.5 WHEN AdminController::approveReview() or ReviewModel::approve() is called THEN the system only updates the `is_approved` flag without triggering product rating statistics update

### Expected Behavior (Correct)

2.1 WHEN a review is approved by admin THEN the system SHALL display the reviewer's actual name from the users table (full_name as primary, username as fallback)

2.2 WHEN a review is approved by admin THEN the system SHALL update the product's `rating_count` field to reflect the correct number of approved reviews

2.3 WHEN a review is approved by admin THEN the system SHALL calculate and update the product's `rating_avg` field based on all approved reviews

2.4 WHEN the ProductModel::getReviews() method fetches reviews THEN the system SHALL JOIN with the users table and return author information including username and full_name

2.5 WHEN AdminController::approveReview() or ReviewModel::approve() is called THEN the system SHALL trigger the ProductModel::updateProductRating() method to recalculate product statistics

2.6 WHEN ProductModel::updateProductRating() is called THEN the system SHALL calculate the average rating (AVG) and count (COUNT) from all approved reviews and update the products table

### Unchanged Behavior (Regression Prevention)

3.1 WHEN a review is submitted by a user THEN the system SHALL CONTINUE TO set is_approved to 0 (pending approval)

3.2 WHEN a user submits a review THEN the system SHALL CONTINUE TO prevent duplicate reviews from the same user for the same product

3.3 WHEN reviews with is_approved = 0 exist THEN the system SHALL CONTINUE TO exclude them from public display on product pages

3.4 WHEN a review is deleted THEN the system SHALL CONTINUE TO remove it from the reviews table

3.5 WHEN a review's approval status is changed from approved to unapproved THEN the system SHALL CONTINUE TO mark is_approved as 0

3.6 WHEN reviews are fetched in the admin panel THEN the system SHALL CONTINUE TO display them with user information correctly joined

3.7 WHEN rating validation occurs THEN the system SHALL CONTINUE TO enforce ratings between 1 and 5

3.8 WHEN a review is created or edited THEN the system SHALL CONTINUE TO sanitize user input using db_escape()

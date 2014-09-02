<?php

class ReviewsWidget extends QModule {

    protected $version = '1.0';

    public function index() {
        $reviews = $this->engine->modules['sitereviews']->getReviews(0, 2);
        $rating = $this->engine->modules['sitereviews']->getAverageRating();
        $count = $this->engine->modules['sitereviews']->getReviewCount();
        foreach ($reviews as $id => $review) {
            $reviews[$id]['post'] = mb_substr($review['post'], 0, 150, 'UTF-8') . '...';
        }
        $this->data['reviews']   = $reviews;
        $this->data['rating']    = $rating;
        $this->data['count']     = $count;
        $this->template          = TEMPLATE . 'reviews_widget.tpl';
    }
}
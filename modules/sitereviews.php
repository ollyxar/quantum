<?php

class SiteReviews extends QModule {

    public function addLanguage($lang) {
        $this->params['title_' . $lang]              = $this->params['title_' . DEF_LANG];
        $this->params['kw_' . $lang]                 = $this->params['kw_' . DEF_LANG];
        $this->params['descr_' . $lang]              = $this->params['descr_' . DEF_LANG];
        $this->params['leave_review_btn_' . $lang]   = $this->params['leave_review_btn_' . DEF_LANG];
        $this->params['form_caption_' . $lang]       = $this->params['form_caption_' . DEF_LANG];
        $this->params['name_placeholder_' . $lang]   = $this->params['name_placeholder_' . DEF_LANG];
        $this->params['email_placeholder_' . $lang]  = $this->params['email_placeholder_' . DEF_LANG];
        $this->params['review_placeholder_' . $lang] = $this->params['review_placeholder_' . DEF_LANG];
        $this->params['post_btn_' . $lang]           = $this->params['post_btn_' . DEF_LANG];
        $this->params['cancel_btn_' . $lang]         = $this->params['cancel_btn_' . DEF_LANG];
        $this->params['error_name_' . $lang]         = $this->params['error_name_' . DEF_LANG];
        $this->params['error_text_' . $lang]         = $this->params['error_text_' . DEF_LANG];
        $q = $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='sitereviews'");
        if ($q) return true; else return false;
    }

    public function getReviews($start, $limit) {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "site_reviews WHERE enabled = 1 ORDER BY id DESC LIMIT " .
            $start . ", " . $limit);
        return $q->rows;
    }

    public function getAverageRating() {
        $rating = 0;
        $q = $this->engine->db->query("SELECT AVG( rating ) as rating FROM `" . DB_PREF . "site_reviews` WHERE enabled=1");
        if (!empty($q->row)) {
            $rating = round((float)$q->row['rating'], 1);
        }
        return $rating;
    }

    public function getReviewCount() {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "site_reviews WHERE enabled=1");
        $rc = 0;
        if (!empty($q->row)) {
            $rc = (int)$q->row['count'];
        }
        return $rc;
    }

    public function add() {
        if (isset($_POST['review_name']) && !empty($_POST['review_name'])) {
            $q = $this->engine->db->query("INSERT INTO " . DB_PREF . "site_reviews (name, email, post) VALUES ('" . $this->engine->db->escape(htmlspecialchars($_POST['review_name'], ENT_QUOTES, 'UTF-8')) . "',  '" . $this->engine->db->escape($_POST['review_email']) . "', '" . $this->engine->db->escape(htmlspecialchars($_POST['review-post'], ENT_QUOTES, 'UTF-8')) . "')");
            if ($q) {
                $data = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>Your review was sent successfully and will publish after moderation.</div>';
                die(json_encode($data));
            } else {
                $data = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>Error occurred while sending data. Please try later.</div>';
                die(json_encode($data));
            }
        }
    }

    public function index() {
        if (!$this->engine->url->is_category) {
            $this->engine->ERROR_404 = false;
            $this->engine->document->setTitle($this->params['title_' . $_SESSION['lang']]);
            $this->engine->document->setKeywords($this->params['kw_' . $_SESSION['lang']]);
            $this->engine->document->setDescription($this->params['descr_' . $_SESSION['lang']]);
            $this->data['leave_review_btn']     = $this->params['leave_review_btn_' . $_SESSION['lang']];
            $this->data['form_caption']         = $this->params['form_caption_' . $_SESSION['lang']];
            $this->data['name_placeholder']     = $this->params['name_placeholder_' . $_SESSION['lang']];
            $this->data['email_placeholder']    = $this->params['email_placeholder_' . $_SESSION['lang']];
            $this->data['review_placeholder']   = $this->params['review_placeholder_' . $_SESSION['lang']];
            $this->data['post_btn']             = $this->params['post_btn_' . $_SESSION['lang']];
            $this->data['cancel_btn']           = $this->params['cancel_btn_' . $_SESSION['lang']];
            $this->data['error_name']           = $this->params['error_name_' . $_SESSION['lang']];
            $this->data['error_text']           = $this->params['error_text_' . $_SESSION['lang']];
            $reviews = $this->getReviews(0, 50);
            foreach ($reviews as $review) {
                $this->data['reviews'][] = array (
                    'name'      => $review['name'],
                    'post'      => $review['post'],
                    'rating'    => $review['rating'],
                    'photo'     => resizeImage($review['photo'], 150, 150)
                );
            }
            $this->template = TEMPLATE . 'template/modules/site_reviews.tpl';
        }
    }
}
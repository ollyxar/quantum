<?php
namespace A;

class SiteReviews extends \AUnit {

    private function updateReviews() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['fp'] as $id) {
                switch ($_POST['action']) {
                    case 'activate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "site_reviews SET `enabled`='1' WHERE id=" . $id);
                        break;
                    case 'deactivate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "site_reviews SET `enabled`='0' WHERE id=" . $id);
                        break;
                    case 'remove':
                        $this->engine->db->query("DELETE FROM " . DB_PREF . "site_reviews WHERE id=" . $id);
                        break;
                    default:
                        break;
                }
            }
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateReview($id) {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $this->engine->db->query("UPDATE " . DB_PREF . "site_reviews SET `name`='" . $this->engine->db->escape($_POST['name']) . "', `email`='" . $this->engine->db->escape($_POST['email']) . "', `post`='" . $this->engine->db->escape($_POST['post']) . "', `photo`='" . $this->engine->db->escape($_POST['photo']) . "', `enabled`='" . (int)$_POST['enabled'] . "' WHERE id=" . (int)$id);
            $_SESSION['msg'] = 'success';
            if ($_POST['action'] == 'save') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=sitereviews');
            }
            if ($_POST['action'] == 'apply') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . "index.php?page=sitereviews&view=tiny&id=" . (int)$id);
            }
        } else {
            $_SESSION['msg'] = 'denied';
            $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=sitereviews');
        }
    }

    private function addReview() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $this->engine->db->query("INSERT INTO " . DB_PREF . "site_reviews (`name`, `email`, `post`, `photo`, `enabled`) VALUES ('" . $this->engine->db->escape($_POST["name"]) . "', '" . $this->engine->db->escape($_POST["email"]) . "', '" . $this->engine->db->escape($_POST["post"]) . "', '" . $this->engine->db->escape($_POST["photo"]) . "', 1);");
            $_SESSION['msg'] = 'success';
            if ($_POST['action'] == 'save') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=sitereviews');
            }
            if ($_POST['action'] == 'apply') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host. ADM_PATH . "index.php?page=sitereviews&view=tiny&id=" . $this->engine->db->getLastId());
            }
        } else {
            $_SESSION['msg'] = 'denied';
            $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . "index.php?page=sitereviews");
        }
    }

    private function getReview($id) {
        $review = $this->engine->db->query("SELECT * FROM " . DB_PREF . "site_reviews WHERE id=" . (int)$id)->row;
        resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 150, 130, false);
        if (!empty($review)) {
            $review['photo']      = ($review['photo'] <> '') ? $review['photo'] : ROOT_DIR . 'upload/images/no-image.jpg';
            $review['thumb']      = resizeImage($review['photo'], 150, 130, false);
        };
        return $review;
    }

    private function getReviewCount($search_query, $limit) {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "site_reviews " . $search_query);
        $all_vals = 0;
        if ($q->num_rows > 0) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        }
        return ceil($all_vals / $limit) > 1 ? ceil($all_vals / $limit) : 1;
    }

    private function getReviews($search_query, $start, $limit) {
        $reviews = $this->engine->db->query("SELECT * FROM " . DB_PREF . "site_reviews " . $search_query . " LIMIT $start, $limit")->rows;
        resizeImage('/upload/images/no-image.jpg', 50, 50, false);
        for ($i = 0; $i < count($reviews); $i++) {
            $reviews[$i]['photo'] = resizeImage($reviews[$i]['photo'], 50, 50, false);
        }
        return $reviews;
    }

    private function updateSettings() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $params_arr = array('page' => $this->engine->db->escape($_POST['page']));

            foreach ($this->engine->languages as $lang) {
                $params_arr['title_' . $lang['name']] = $_POST['title_' . $lang['name']];
                $params_arr['descr_' . $lang['name']] = $_POST['descr_' . $lang['name']];
                $params_arr['kw_' . $lang['name']] = $_POST['kw_' . $lang['name']];
                $params_arr['leave_review_btn_' . $lang['name']] = $_POST['leave_review_btn_' . $lang['name']];
                $params_arr['form_caption_' . $lang['name']] = $_POST['form_caption_' . $lang['name']];
                $params_arr['name_placeholder_' . $lang['name']] = $_POST['name_placeholder_' . $lang['name']];
                $params_arr['email_placeholder_' . $lang['name']] = $_POST['email_placeholder_' . $lang['name']];
                $params_arr['review_placeholder_' . $lang['name']] = $_POST['review_placeholder_' . $lang['name']];
                $params_arr['post_btn_' . $lang['name']] = $_POST['post_btn_' . $lang['name']];
                $params_arr['cancel_btn_' . $lang['name']] = $_POST['cancel_btn_' . $lang['name']];
                $params_arr['error_name_' . $lang['name']] = $_POST['error_name_' . $lang['name']];
                $params_arr['error_text_' . $lang['name']] = $_POST['error_text_' . $lang['name']];
            }

            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($params_arr)) . "' WHERE name='sitereviews'");

            if ($_POST['alias'] != '') {
                $row = $this->engine->db->query("SELECT id FROM " . DB_PREF . "url_alias WHERE query = 'route=reviews'")->row;
                if (!empty($row)) {
                    $this->engine->db->query("UPDATE " . DB_PREF . "url_alias SET `keyword` = '" . $_POST['alias'] . "' WHERE id = " . (int)$row['id']);
                } else {
                    $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=reviews', '" . $this->engine->db->escape($_POST['alias']) . "', '0')");
                }
                $this->engine->cache->delete('aliases');
            }

            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function index() {
        if ($_SESSION['access'] > $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['action']) && $_POST['action'] == 'save_site_reviews') {
            $this->updateSettings();
        }

        if (isset($_POST['fp'])) {
            $this->updateReviews();
        }

        if (isset($_POST['sitereview']) && isset($_POST['action']) && (int)$_GET['id'] > 0) {
            $this->updateReview((int)$_GET['id']);
        }

        if (isset($_POST['sitereview']) && $_GET['id'] == 'new') {
            $this->addReview();
        }

        if (!isset($_GET["page_n"]) || (int)$_GET['page_n'] < 1) {
            $_GET["page_n"] = 1;
        }

        if (!isset($_GET["per_page"]) || (int)$_GET['per_page'] < 1) {
            $_GET['per_page'] = 10;
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'denied') {
            $this->data['text_message'] = $this->language['access_denied'];
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
        }

        if (isset($_GET['view']) && $_GET['view'] == 'tiny') {
            $this->data['review'] = $this->getReview($_GET['id']);
            $this->engine->document->addHeaderString('<script type="text/javascript" src="template/js/qfinder/qfinder.js"></script>');
            if ($_GET['id'] == 'new') {
                $review = array(
                    'name'      => '',
                    'email'     => '',
                    'post'      => '',
                    'photo'     => '/upload/images/no-image.jpg',
                    'thumb'     =>  resizeImage('/upload/images/no-image.jpg', 150, 130, false),
                    'enabled'   => '0'
                );
                $this->data['review'] = $review;
            }

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['modules'],
                'link'      => 'index.php?page=modules'
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['site_reviews'],
                'link'      => 'index.php?page=sitereviews'
            );
            $this->data['breadcrumb_cur'] = $this->language['post'];

            $this->template = 'template/site_review.tpl';
        } else {
            $start = ((int)$_GET["page_n"] - 1) * (int)$_GET["per_page"];
            $limit = (int)$_GET["per_page"];
            $search = isset($_GET["search"]) ? $_GET["search"] : '';
            $search_query = "WHERE (`name` LIKE '%" . $search . "%' OR `email` LIKE '%" . $search . "%' OR `post` LIKE '%" . $search . "%')";
            if ($search == '') $search_query = '';

            $this->data['reviews'] = $this->getReviews($search_query, $start, $limit);
            $this->data['search'] = $search;
            $this->data['page_count'] = $this->getReviewCount($search_query, $limit);
            $this->data['settings'] = $this->params;
            $this->data['alias'] = '';
            $alias = $this->engine->db->query("SELECT keyword FROM " . DB_PREF . "url_alias WHERE query='route=reviews'")->row;
            if (!empty($alias)) {
                $this->data['alias'] = $alias['keyword'];
            }

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['modules'],
                'link'      => 'index.php?page=modules'
            );
            $this->data['breadcrumb_cur'] = $this->language['site_reviews'];

            $this->template = 'template/site_reviews.tpl';
        }
    }
}
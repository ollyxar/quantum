<?php

class Materials extends QModule {

    protected $version = '1.4';
    private $mode = 0;

    public function addLanguage($lang) {
        $this->params['details_' . $lang] = $this->params['details_' . DEF_LANG];
        $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='materials'");

        $this->engine->db->query("ALTER TABLE `" . DB_PREF . "materials` ADD `caption_" . $lang . "` VARCHAR( 255 ) NOT NULL, ADD `description_" . $lang . "` VARCHAR( 255 ) NOT NULL, ADD `title_" . $lang . "` TEXT NOT NULL, ADD `descr_" . $lang . "` TEXT NOT NULL, ADD `kw_" . $lang . "` TEXT NOT NULL, ADD `text_" . $lang . "` TEXT NOT NULL;");
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption, title_" . DEF_LANG . " as title, text_" . DEF_LANG . " as text, kw_" . DEF_LANG . " as kw, descr_" . DEF_LANG . " as descr, description_" . DEF_LANG . " as description FROM " . DB_PREF . "materials");
        foreach($q->rows as $f) {
            $this->engine->db->query("UPDATE " . DB_PREF . "materials SET `caption_" . $lang . "`='" . $f['caption'] . "', `title_" . $lang . "`='" . $f['title'] . "', `text_" . $lang . "`='" . $f['text'] . "', `kw_" . $lang . "`='" . $f['kw'] . "', `descr_" . $lang . "`='" . $f['descr'] . "', `description_" . $lang . "`='" . $f['description'] . "' WHERE id=" . $f['id']);
        }
        if ($q) return true; else return false;
    }

    public function removeLanguage($lang) {
        unset($this->params['details_'.$lang]);
        $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" .
            $this->engine->db->escape(serialize($this->params)) . "' WHERE name='materials'");

        $q = $this->engine->db->query("ALTER TABLE `" . DB_PREF . "materials` DROP `caption_" . $lang . "`, DROP `description_" . $lang . "`, DROP `text_" . $lang . "`, DROP `title_" . $lang . "`, DROP `descr_" . $lang . "`, DROP `kw_" . $lang . "`;");
        if ($q) return true; else return false;
    }

    private function fillCategory($category_id) {
        $limit = $this->params['count_per_page'];
        $start = ($this->engine->url->page_n - 1) * $limit;
        $q = $this->engine->db->query("SELECT id, parent_id, caption_" . $_SESSION['lang'] . " as caption, description_" . $_SESSION['lang'] . " as description, text_" . $_SESSION['lang'] . " as `text`, title_" . $_SESSION['lang'] . " as title, kw_" . $_SESSION['lang'] . " as kw, descr_" . $_SESSION['lang'] . " as descr FROM " . DB_PREF . "materials WHERE id='" . (int)$category_id . "' AND is_category=1 AND enabled=1 ORDER BY date_added DESC");
        if (empty($q->row)) {
            $this->engine->ERROR_404 = true;
            return false;
        } else {
            $category = $q->row;
            $breadcrumbs = array();
            $this->buildBreadcrumbs($category['id'], $breadcrumbs);
            $this->data['breadcrumbs'][0] = array(
                'caption' => '<i class="glyphicon glyphicon-home"></i>',
                'link'    => $this->engine->url->link('route=home')
            );

            foreach ($breadcrumbs as $breadcrumb) {
                $this->data['breadcrumbs'][] = array(
                    'caption' => $breadcrumb['caption_' . $_SESSION['lang']],
                    'link'    => $this->engine->url->link('route=materials&material_id='. $breadcrumb['id'])
                );
            }
            $this->data['category'] = array(
                'caption'       => $category['caption'],
                'description'   => $category['description'],
                'text'          => $category['text'],
            );
            if ($category['title'] != '') {
                $this->engine->document->setTitle($category['title']);
            }
            if ($category['kw'] != '') {
                $this->engine->document->setKeywords($category['kw']);
            }
            if ($category['descr'] != '') {
                $this->engine->document->setDescription($category['descr']);
            }

            $categories = $this->engine->db->query("SELECT id, caption_" . $_SESSION['lang'] . " as caption, description_" . $_SESSION['lang'] . " as description, text_" . $_SESSION['lang'] . " as text, preview FROM " . DB_PREF . "materials WHERE parent_id=" . (int)$category_id . " AND enabled=1 AND is_category = 1 ORDER BY  date_added DESC")->rows;

            $this->data['categories'] = array();
            foreach ($categories as $category) {
                $this->data['categories'][$category['id']] = array(
                    'link'          => $this->engine->url->link('route=materials&material_id=' . $category['id']),
                    'caption'       => $category['caption'],
                    'preview'       => resizeImage($category['preview'], 100, 100),
                    'description'   => $category['description'],
                    'text'          => $category['text']
                );
            }

            $materials  = $this->engine->db->query("SELECT id, caption_" . $_SESSION['lang'] . " as caption, description_" . $_SESSION['lang'] . " as description, preview, date_added FROM " . DB_PREF . "materials WHERE parent_id=" . (int)$category_id . " AND enabled = 1 AND is_category = 0 ORDER BY date_added DESC LIMIT ". $start . ", " . $limit)->rows;

            $this->data['materials'] = array();
            foreach ($materials as $material) {
                $this->data['materials'][$material['id']] = array(
                    'link'          => $this->engine->url->link('route=materials&material_id=' . $material['id']),
                    'caption'       => $material['caption'],
                    'preview'       => resizeImage($material['preview'], 150, 150),
                    'date_added'    => $material['date_added'],
                    'description'   => $material['description']
                );
            }
            $this->data['details_caption']  = $this->params['details_' . $_SESSION['lang']];
            $page_count = $this->getPageCount((int)$category_id, (int)$this->params['count_per_page']);
            $this->data['pagination'] = printPagination($page_count, $this->engine->uri);
            $this->mode = 1;
            $this->engine->ERROR_404 = false;
            return true;
        }
    }

    private function fillMaterial($material_id) {
        if (CLEAN_URL && ($this->engine->url->is_category)) {
            $this->engine->ERROR_404 = true;
            return false;
        }
        $material = $this->engine->db->query("SELECT parent_id, caption_" . $_SESSION['lang'] . " as caption, text_" . $_SESSION['lang'] . " as `text`, title_" . $_SESSION['lang'] . " as title, kw_" . $_SESSION['lang'] . " as kw, descr_" . $_SESSION['lang'] . " as descr, date_added FROM " . DB_PREF . "materials WHERE id=" . (int)$material_id)->row;
        if (empty($material)) {
            $this->engine->ERROR_404 = true;
            return false;
        } else {
            $breadcrumbs = array();
            $this->buildBreadcrumbs($material['parent_id'], $breadcrumbs);
            $this->data['breadcrumbs'][0] = array(
                'caption' => '<i class="glyphicon glyphicon-home"></i>',
                'link'    => $this->engine->url->link('route=home')
            );

            foreach ($breadcrumbs as $breadcrumb) {
                $this->data['breadcrumbs'][] = array(
                    'caption' => $breadcrumb['caption_' . $_SESSION['lang']],
                    'link'    => $this->engine->url->link('route=materials&material_id='. $breadcrumb['id'])
                );
            }
            $this->data['breadcrumbs'][] = array(
                'caption' => $material['caption'],
                'link'    => $this->engine->url->link('route=materials&material_id='. $material_id)
            );
            $this->data['material']  = $material;
            if ($material['title'] != '') {
                $this->engine->document->setTitle($material['title']);
            }
            if ($material['kw'] != '') {
                $this->engine->document->setKeywords($material['kw']);
            }
            if ($material['descr'] != '') {
                $this->engine->document->setDescription($material['descr']);
            }
            $this->engine->ERROR_404 = false;
            $this->mode = 2;
            return true;
        }
    }

    private function getPageCount($category_id, $limit = 4) {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "materials WHERE parent_id=" . (int)$category_id . " AND enabled = 1 AND is_category = 0");
        if (!empty($q->rows)) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        } else {
            $all_vals = 0;
        }
        return ceil($all_vals / $limit);
    }

    public function getCategories() {
        $l_query = '';
        foreach ($this->engine->languages as $lang) {
            $l_query .= 'caption_' . $lang['name'] . ', ';
        }
        $l_query = substr($l_query, 0 , -2);
        $q = $this->engine->db->query("SELECT id, parent_id, " . $l_query . " FROM " . DB_PREF . "materials WHERE enabled = 1 AND is_category = 1");
        return $q->rows;
    }

    private function buildBreadcrumbs($category_id, &$arr) {
        $breadcrumbs = $this->engine->cache->get('materials_breadcrumbs');
        if (!$breadcrumbs) {
            $breadcrumbs = $this->getCategories();
            $this->engine->cache->set('materials_breadcrumbs', $breadcrumbs);
        }
        foreach ($breadcrumbs as $breadcrumb) {
            if ((int)$breadcrumb['id'] == $category_id) {
                array_unshift($arr, $breadcrumb);
                $this->buildBreadcrumbs($breadcrumb['parent_id'], $arr);
                break;
            }
        }
    }

    public function index() {
        if (!isset($_GET['material_id'])) {
            $this->engine->ERROR_404 = true;
            return false;
        }
        if (!$this->fillCategory($_GET['material_id'])) {
            $this->fillMaterial($_GET['material_id']);
        }
        if ($this->mode == 1) {
            $this->template = TEMPLATE . 'material_list.tpl';
        } elseif ($this->mode == 2) {
            $this->template = TEMPLATE . 'material.tpl';
        }
    }
}
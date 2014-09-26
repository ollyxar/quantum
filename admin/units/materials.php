<?php
namespace A;

class Materials extends \AUnit {

    private $lst = array();

    private function saveMaterials() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['pages'] as $id => $data) {
                $query = '';
                foreach ($this->engine->languages as $lang) {
                    $query .= '`caption_' . $lang['name'] . "`='" . $this->engine->db->escape($data['caption_' .
                        $lang['name']]) . "', ";
                }
                $query = substr($query, 0, -2);
                $this->engine->db->query("UPDATE " . DB_PREF . "materials SET " . $query . " WHERE id=" . (int)$id);
            }
            $params_arr = array('count_per_page' => $_POST['count_per_page']);
            foreach ($this->engine->languages as $lang) {
                $params_arr['details_' . $lang['name']] = $_POST['details_' . $lang['name']];
            }
            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" .
                $this->engine->db->escape(serialize($params_arr)) .
                "' WHERE name='materials'");
            $this->engine->cache->delete('materials_breadcrumbs');
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function duplicateMaterial($id) {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $new_id = $this->engine->db->query("SELECT id FROM " . DB_PREF . "materials ORDER BY id DESC LIMIT 0, 1")->row['id'];
            $new_id++;
            $this->engine->db->query("CREATE TEMPORARY TABLE tmp SELECT * FROM " . DB_PREF . "materials WHERE id = " . (int)$id);
            $this->engine->db->query("UPDATE tmp SET id=" . $new_id . " WHERE id = " . (int)$id);
            $this->engine->db->query("INSERT INTO " . DB_PREF . "materials SELECT * FROM tmp WHERE id = " . $new_id . ";");
            $this->engine->cache->delete('materials_breadcrumbs');
            $this->engine->cache->delete('materials_categories');
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateMaterials() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['fp'] as $id) {
                switch ($_POST['action']) {
                    case 'activate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "materials SET `enabled`='1' WHERE id=" . (int)$id);
                        break;
                    case 'deactivate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "materials SET `enabled`='0' WHERE id=" . (int)$id);
                        break;
                    case 'remove':
                        $this->engine->db->query("DELETE FROM " . DB_PREF . "materials WHERE id=" . (int)$id);
                        break;
                    case 'duplicate':
                        $this->duplicateMaterial((int)$id);
                        break;
                    default:
                        break;
                }

            }
            $this->engine->cache->delete('materials_breadcrumbs');
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateMaterial($id) {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $query = '';
            foreach ($this->engine->languages as $lang) {
                $query .= '`caption_' . $lang['name'] . "`='" . $this->engine->db->escape($_POST['caption_' .
                    $lang['name']]) . "', `title_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['title_' .
                    $lang['name']]) . "', `kw_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['kw_' .
                    $lang['name']]) . "', `descr_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['descr_' .
                    $lang['name']]) . "', `text_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['text_' .
                    $lang['name']]) . "', `description_" . $lang['name'] . "`='" .
                    $this->engine->db->escape($_POST['description_' . $lang['name']]) . "', ";
            }
            $query = substr($query, 0, -2);
            $this->engine->db->query("UPDATE " . DB_PREF . "materials SET `preview`='" .
                $this->engine->db->escape($_POST['preview']) . "', `is_category`='" .
                $this->engine->db->escape($_POST['type']) . "', `parent_id`='" .
                $this->engine->db->escape($_POST['parent']) . "', " . $query . " WHERE id=" . (int)$id);

            if ($_POST['alias'] != '') {
                $row = $this->engine->db->query("SELECT id FROM " . DB_PREF . "url_alias WHERE query = 'route=materials&material_id=" . (int)$id . "'")->row;
                if (!empty($row)) {
                    $this->engine->db->query("UPDATE " . DB_PREF . "url_alias SET `keyword` = '" . $_POST['alias'] . "' WHERE id = " . (int)$row['id']);
                } else {
                    $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=materials&material_id=" . (int)$id . "', '" . $this->engine->db->escape($_POST['alias']) . "', '1')");
                }
            }

            $this->engine->cache->delete('materials_breadcrumbs');
            $this->engine->cache->delete('materials_categories');
            $this->engine->cache->delete('aliases');
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function preRemove($id) {
        $q = $this->engine->db->query("SELECT id FROM " . DB_PREF . "materials WHERE parent_id=" . (int)$id);
        foreach ($q->rows as $f) {
            $this->lst[] = $f['id'];
            $this->preRemove($f['id']);
        }
    }

    private function removeCategory($id) {
        if ($this->access['rw'] >= $_SESSION['access']) {
                $this->preRemove($id);
                foreach ($this->lst as $sub_id) {
                    $this->engine->db->query("DELETE FROM " . DB_PREF . "materials WHERE id=" . (int)$sub_id);
                }
                $this->engine->db->query("DELETE FROM " . DB_PREF . "materials WHERE id=" . (int)$id);
                $this->lst = array();
            $this->engine->cache->delete('materials_breadcrumbs');
            $this->engine->cache->delete('materials_categories');
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function addMaterial() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $query_keys = '';
            $query_vals = '';
            foreach ($this->engine->languages as $lang) {
                $query_keys .= "`caption_" . $lang['name'] . "`, `text_" . $lang['name'] . "`, `title_" . $lang['name'] .
                    "`, `kw_" . $lang['name'] . "`, `descr_" . $lang['name'] . "`, `description_" . $lang['name'] . "`, ";
            }
            $query_keys = substr($query_keys, 0, -2);
            foreach ($this->engine->languages as $lang) {
                $query_vals .= "'" . $this->engine->db->escape($_POST["caption_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["text_" . $lang['name']]) . "', '" . $this->engine->db->escape($_POST["title_" .
                    $lang['name']]) . "', '" . $this->engine->db->escape($_POST["kw_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["descr_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["description_" . $lang['name']]) . "', ";
            }
            $query_vals = substr($query_vals, 0, -2);
            $this->engine->db->query("INSERT INTO " . DB_PREF . "materials (`preview`, `is_category`, " .
                "`parent_id`, " . $query_keys . ") VALUES ('" .
                $this->engine->db->escape($_POST["preview"]) . "', '" . $this->engine->db->escape($_POST["type"]) .
                "', '" . $this->engine->db->escape($_POST["parent"]) . "', " . $query_vals . ");");

            $id = $this->engine->db->getLastId();
            if ($_POST['alias'] != '') {
                $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=materials&material_id=" . (int)$id . "', '" . $this->engine->db->escape($_POST['alias']) . "', '1')");
                $this->engine->cache->delete('aliases');
            }

            $this->engine->cache->delete('materials_breadcrumbs');
            $this->engine->cache->delete('materials_categories');
            $_SESSION['msg'] = 'success';
            $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . "index.php?page=materials&view=tiny&id=" . (int)$id);
        } else {
            $_SESSION['msg'] = 'denied';
            $this->engine->url->redirect($this->engine->url->full);
        }
    }

    private function buildTree($parent_id = 0) {
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF . "materials WHERE is_category=1 AND parent_id=" . (int)$parent_id . " ORDER BY date_added DESC");
        $arr = array();
        foreach ($q->rows as $f) {
            $arr[$f['id']]['caption'] = $f['caption'];
            $arr[$f['id']]['subs'] = array();
            $q2 = $this->engine->db->query("SELECT id FROM " . DB_PREF . "materials WHERE is_category=1" .
                " AND parent_id=" . (int)$f['id']);
            if ($q2->rows) {
                $arr[$f['id']]['subs'] = $this->buildTree($f['id']);
            }
        }
        return $arr;
    }

    private function getPageCount($search_query, $parent, $limit) {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "materials WHERE is_category=0 AND parent_id=" . (int)$parent . $search_query);
        $all_vals = 0;
        if ($q->num_rows > 0) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        }
        return ceil($all_vals / $limit) > 1 ? ceil($all_vals / $limit) : 1;
    }

    private function getMaterials($parent_id, $query, $search_query, $start, $limit) {
        return $this->engine->db->query("SELECT id, enabled, " . $query . " FROM " . DB_PREF . "materials WHERE is_category=0 AND parent_id=" . (int)$parent_id . " " . $search_query . " ORDER BY date_added DESC LIMIT " . (int)$start . ", " . (int)$limit)->rows;
    }

    private function getMaterial() {
        $material = $this->engine->db->query("SELECT m.*, ua.keyword as alias FROM " . DB_PREF . "materials m LEFT JOIN " . DB_PREF . "url_alias ua ON ua.query = CONCAT ('route=materials&material_id=', m.id) WHERE m.id=" . (int)$_GET['id'])->row;
        $material['preview'] = ($material['preview'] <> '') ? $material['preview'] : ROOT_DIR . 'upload/images/no-image.jpg';
        resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 150, 130, false);
        $material['thumb'] = resizeImage($material['preview'], 150, 130, false);
        return $material;
    }

    private function getParentCategories() {
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF .
            "materials WHERE is_category=1");
        return $q->rows;
    }

    public function index() {
        if ($_SESSION['access'] > $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['action']) && $_POST['action'] == 'save') {
            $this->saveMaterials();
        }

        if (isset($_POST['fp'])) {
            $this->updateMaterials();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'remove_cat') {
            $this->removeCategory($_GET['category']);
        }

        if (isset($_POST['parent']) && (int)$_GET['id'] > 0) {
            $this->updateMaterial((int)$_GET['id']);
        }

        if (isset($_POST['parent']) && $_GET['id'] == 'new') {
            $this->addMaterial();
        }

        if (!isset($_GET["page_n"]) || (int)$_GET['page_n'] < 1) {
            $_GET["page_n"] = 1;
        }

        if (!isset($_GET["per_page"]) || (int)$_GET['per_page'] < 1) {
            $_GET['per_page'] = 10;
        }

        $parent_id = isset($_GET['parent']) ? (int)$_GET['parent'] : 0;

        $this->data['categories'] = array('0' => array('caption' => 'root', 'subs' => $this->buildTree()));
        $this->data['parent_id'] = $parent_id;

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
            $this->data['categories'] = $this->getParentCategories();

            if ($_GET['id'] == 'new') {
                $material = array(
                    'alias'         => '',
                    'is_category'   => 0,
                    'preview'       => '/upload/images/no-image.jpg',
                    'thumb'         => resizeImage('/upload/images/no-image.jpg', 150, 130, false),
                    'parent_id'     => $parent_id
                );
                foreach ($this->engine->languages as $lang) {
                    $material['caption_' . $lang['name']] = '';
                    $material['title_' . $lang['name']] = '';
                    $material['kw_' . $lang['name']] = '';
                    $material['descr_' . $lang['name']] = '';
                    $material['description_' . $lang['name']] = '';
                    $material['text_' . $lang['name']] = '';
                }
                $this->data['material'] = $material;
            } else {
                $this->data['material'] = $this->getMaterial();
            }
            $this->engine->document->addHeaderString('<script type="text/javascript" src="template/js/qfinder/qfinder.js"></script>');
            $this->engine->document->addHeaderString('<script src="template/js/ckeditor/ckeditor.js"></script>');

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['modules'],
                'link'      => 'index.php?page=modules'
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['materials'],
                'link'      => 'index.php?page=materials'
            );
            $this->data['breadcrumb_cur'] = $this->language['material'];

            $this->template = 'template/material.tpl';
        } else {
            $start = ((int)$_GET["page_n"] - 1) * (int)$_GET["per_page"];
            $limit = (int)$_GET["per_page"];
            $search = isset($_GET["search"]) ? $_GET["search"] : '';
            $query = '';
            $search_query = '';
            $must_or = false;
            foreach ($this->engine->languages as $lang) {
                $query .= 'caption_' . $lang['name'] . ', ';
                if ($must_or) $search_query .= 'OR ';
                $search_query .= "`caption_" . $lang['name'] . "` LIKE '%" . $search . "%' ";
                $must_or = true;
            }
            $query = substr($query, 0, -2);
            $search_query = substr($search_query, 0, -1);
            $search_query = ' AND (' . $search_query . ')';
            if ($search == '') $search_query = '';

            $this->data['settings'] = $this->params;
            $this->data['materials'] = $this->getMaterials($parent_id, $query, $search_query, $start, $limit);
            $this->data['categories'] = $this->buildTree();
            $this->data['search'] = $search;
            $this->data['page_count'] = $this->getPageCount($search_query, $parent_id, $limit);
            $this->engine->document->addHeaderString('<script src="template/js/jquery.cookie.js"></script>');
            $this->engine->document->addHeaderString('<script src="template/js/jquery.treeview.js"></script>');
            $this->engine->document->addHeaderString('<link href="template/css/jquery.treeview.css" rel="stylesheet" media="screen">');

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['modules'],
                'link'      => 'index.php?page=modules'
            );
            $this->data['breadcrumb_cur'] = $this->language['materials'];

            $this->template = 'template/materials.tpl';
        }
    }
}
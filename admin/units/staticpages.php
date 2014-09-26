<?php
namespace A;

class StaticPages extends \AUnit {

    private function savePages() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['pages'] as $id => $data) {
                $query = '';
                foreach ($this->engine->languages as $lang) {
                    $query .= '`caption_' . $lang['name'] . "`='" . $this->engine->db->escape($data['caption_' . $lang['name']]) . "', ";
                }
                $query = substr($query, 0, -2);
                $this->engine->db->query("UPDATE " . DB_PREF . "static_pages SET " . $query . " WHERE id=" . (int)$id);
            }
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updatePages() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['fp'] as $id) {
                switch ($_POST['action']) {
                    case 'activate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "static_pages SET `enabled`='1' WHERE id=" . (int)$id);
                        break;
                    case 'deactivate':
                        $this->engine->db->query("UPDATE " . DB_PREF . "static_pages SET `enabled`='0' WHERE id=" . (int)$id);
                        break;
                    case 'remove':
                        $this->engine->db->query("DELETE FROM " . DB_PREF . "static_pages WHERE id=" . (int)$id);
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

    private function updatePage($id) {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $query = '';
            foreach ($this->engine->languages as $lang) {
                $query .= '`caption_' . $lang['name'] . "`='" . $this->engine->db->escape($_POST['caption_' .
                    $lang['name']]) . "', `title_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['title_' .
                    $lang['name']]) . "', `kw_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['kw_' .
                    $lang['name']]) . "', `descr_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['descr_' .
                    $lang['name']]) . "', `text_" . $lang['name'] . "`='" . $this->engine->db->escape($_POST['text_' .
                    $lang['name']]) . "', ";
            }
            $query = substr($query, 0, -2);

            $this->engine->db->query("UPDATE " . DB_PREF . "static_pages SET " . $query . " WHERE id=" . (int)$id);

            if ($_POST['alias'] != '') {
                $row = $this->engine->db->query("SELECT id FROM " . DB_PREF . "url_alias WHERE query = 'route=pages&page_id=" . (int)$id . "'")->row;
                if (!empty($row)) {
                    $this->engine->db->query("UPDATE " . DB_PREF . "url_alias SET `keyword` = '" . $_POST['alias'] . "' WHERE id = " . (int)$row['id']);
                } else {
                    $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=pages&page_id=" . (int)$id . "', '" . $this->engine->db->escape($_POST['alias']) . "', '0')");
                }
                $this->engine->cache->delete('aliases');
            }

            $_SESSION['msg'] = 'success';
            if ($_POST['action'] == 'save') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=staticpages');
            }
            if ($_POST['action'] == 'apply') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . "index.php?page=staticpages&view=tiny&id=" . (int)$id);
            }
        } else {
            $_SESSION['msg'] = 'denied';
            $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=staticpages');
        }
    }

    private function addPage() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $query_keys = '';
            $query_vals = '';
            foreach ($this->engine->languages as $lang) {
                $query_keys .= "`caption_" . $lang['name'] . "`, `text_" . $lang['name'] . "`, `title_" .
                    $lang['name'] . "`, `kw_" . $lang['name'] . "`, `descr_" . $lang['name'] . "`, ";
            }
            $query_keys = substr($query_keys, 0, -2);
            foreach ($this->engine->languages as $lang) {
                $query_vals .= "'" . $this->engine->db->escape($_POST["caption_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["text_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["title_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["kw_" . $lang['name']]) . "', '" .
                    $this->engine->db->escape($_POST["descr_" . $lang['name']]) . "', ";
            }
            $query_vals = substr($query_vals, 0, -2);
            $this->engine->db->query("INSERT INTO " . DB_PREF . "static_pages (" . $query_keys . ", `enabled`) VALUES (" . $query_vals . ", 1);");
            $id = $this->engine->db->getLastId();
            if ($_POST['alias'] != '') {
                $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=pages&page_id=" . (int)$id . "', '" . $this->engine->db->escape($_POST['alias']) . "', '0')");
                $this->engine->cache->delete('aliases');
            }
            $_SESSION['msg'] = 'success';
            if ($_POST['action'] == 'save') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . 'index.php?page=staticpages');
            }
            if ($_POST['action'] == 'apply') {
                $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . "index.php?page=staticpages&view=tiny&id=" . (int)$id);
            }
        } else {
            $_SESSION['msg'] = 'denied';
            $this->engine->url->redirect(PRTCL . "://" . $this->engine->host. ADM_PATH . "index.php?page=staticpages");
        }
    }

    private function getPage() {
        $q = $this->engine->db->query("SELECT sp.*, ua.keyword as alias FROM " . DB_PREF . "static_pages sp LEFT JOIN " . DB_PREF . "url_alias ua ON ua.query = CONCAT ('route=pages&page_id=', sp.id) WHERE sp.id=" . (int)$_GET['id']);
        return $q->row;
    }

    private function getPageCount($search_query, $limit) {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "static_pages " . $search_query);
        $all_vals = 0;
        if ($q->num_rows > 0) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        }
        return ceil($all_vals / $limit) > 1 ? ceil($all_vals / $limit) : 1;
    }

    private function getPages($query, $search_query, $start, $limit) {
        $q = $this->engine->db->query("SELECT id, enabled, " . $query . " FROM " . DB_PREF .
            "static_pages " . $search_query . " LIMIT $start, $limit");
        return $q->rows;
    }

    public function index() {
        if ($_SESSION['access'] > $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['pages']) && $_POST['action'] == 'save') {
            $this->savePages();
        }

        if (isset($_POST['fp'])) {
            $this->updatePages();
        }

        if (isset($_POST['action']) && (int)$_GET['id'] > 0) {
            $this->updatePage((int)$_GET['id']);
        }

        if (isset($_POST['action']) && $_GET['id'] == 'new') {
            $this->addPage();
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
            $this->data['static_page'] = $this->getPage();
            if ($_GET['id'] == 'new') {
                $static_page = array('alias' => '');
                foreach ($this->engine->languages as $lang) {
                    $static_page['caption_' . $lang['name']] = '';
                    $static_page['title_' . $lang['name']] = '';
                    $static_page['kw_' . $lang['name']] = '';
                    $static_page['descr_' . $lang['name']] = '';
                    $static_page['text_' . $lang['name']] = '';
                }
                $this->data['static_page'] = $static_page;
            }
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
                'caption'   => $this->language['static_pages'],
                'link'      => 'index.php?page=staticpages'
            );
            $this->data['breadcrumb_cur'] = $this->language['page'];

            $this->template = 'template/static_page.tpl';
        } else {
            $start = ((int)$_GET["page_n"] - 1) * (int)$_GET["per_page"];
            $limit = (int)$_GET["per_page"];
            $query = '';
            $search = isset($_GET["search"]) ? $_GET["search"] : '';
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
            $search_query = 'WHERE (' . $search_query . ')';
            if ($search == '') $search_query = '';

            $this->data['pages'] = $this->getPages($query, $search_query, $start, $limit);
            $this->data['search'] = $search;
            $this->data['page_count'] = $this->getPageCount($search_query, $limit);
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
            $this->data['breadcrumb_cur'] = $this->language['static_pages'];

            $this->template = 'template/static_pages.tpl';
        }
    }
}
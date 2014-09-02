<?php
namespace A;

class Users extends \AUnit {

    private function updateUsers() {
        foreach ($_POST['fp'] as $id) {
            switch ($_POST['action']) {
                case 'activate':
                    $this->engine->db->query("UPDATE " . DB_PREF . "users SET `enabled`=1 WHERE id=" . (int)$id);
                    break;
                case 'deactivate':
                    $this->engine->db->query("UPDATE " . DB_PREF . "users SET `enabled`=0 WHERE id=" . (int)$id);
                    break;
                case 'remove':
                    $this->engine->db->query("DELETE FROM " . DB_PREF . "users WHERE id=" . (int)$id);
                    break;
                default:
                    break;
            }
        }
        $_SESSION['msg'] = 'success';
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function getPageCount($search, $search_query, $limit) {
        if ($search == '') {
            $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "users");
        } else {
            $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "users WHERE " . $search_query);
        }

        $all_vals = 0;
        if ($q->num_rows > 0) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        }
        return ceil($all_vals / $limit) > 1 ? ceil($all_vals / $limit) : 1;
    }

    private function getUsers($search, $search_query, $start, $limit) {
        $users = array();
        if ($search == '') {
            $q = $this->engine->db->query("SELECT " . DB_PREF . "users.*, " . DB_PREF .
                "user_group.description as ug FROM " . DB_PREF . "users, " . DB_PREF . "user_group WHERE " . DB_PREF .
                "user_group.id = " . DB_PREF . "users.user_group LIMIT " . (int)$start . ", " . (int)$limit);
        } else {
            $q = $this->engine->db->query("SELECT " . DB_PREF . "users.*, " . DB_PREF .
                "user_group.description as ug FROM " . DB_PREF . "users, " . DB_PREF . "user_group WHERE " . DB_PREF .
                "user_group.id = " . DB_PREF . "users.user_group AND " . $search_query . " LIMIT " . (int)$start .
                ", " . (int)$limit);
        }
        resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 50, 50, false);
        foreach ($q->rows as $user) {
            $users[$user['id']]['photo'] = resizeImage($user['photo'], 50, 50, false);
            $users[$user['id']]['name'] = $user['name'];
            $users[$user['id']]['description'] = $user['description'];
            $users[$user['id']]['email'] = $user['email'];
            $users[$user['id']]['joined'] = date("Y-M-d D h:i:s", $user['joined']);
            $users[$user['id']]['enabled'] = (bool)$user['enabled'];
            $users[$user['id']]['user_group'] = $user['ug'];
        }
        return $users;
    }

    private function updateUser() {
        $query = '';
        if (isset($_POST['chp']) && isset($_POST['pass'])) {
            $query = ", `password`='" . md5(md5($_POST['pass'])) . "'";
        }
        $this->engine->db->query("UPDATE " . DB_PREF . "users SET `name`='" .
            $this->engine->db->escape($_POST['uname']) . "', `photo`='" . $this->engine->db->escape($_POST['photo']) .
            "', `email`='" . $this->engine->db->escape($_POST['email']) . "', `user_group`='" .
            $this->engine->db->escape($_POST['group']) . "', `description`='" .
            $this->engine->db->escape($_POST['description']) . "', `birth`='" .
            $this->engine->db->escape(strtotime($_POST['birth'])) . "'" . $query . " WHERE id=" . (int)$_GET['id']);
        $_SESSION['msg'] = 'success';
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function addUser() {
        $this->engine->db->query("INSERT INTO " . DB_PREF . "users (`name`, `photo`, `email`, `user_group`, " .
            "`description`, `birth`, `joined`, `password`) VALUES ('" . $this->engine->db->escape($_POST["uname"]) .
            "', '" . $this->engine->db->escape($_POST["photo"]) . "', '" . $this->engine->db->escape($_POST["email"]) .
            "', '" . $this->engine->db->escape($_POST["group"]) . "', '" . $this->engine->db->escape($_POST["description"]) .
            "', '" . $this->engine->db->escape(strtotime($_POST["birth"])) . "', '" . strtotime('now') . "', '" .
            $this->engine->db->escape($_POST["pass"]) . "');");
        $_SESSION['msg'] = 'success';
        $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . ADM_PATH . "index.php?page=users&view=tiny&id=" .
            $this->engine->db->getLastId());
    }

    private function getUserGroups() {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "user_group");
        $user_groups = array();
        foreach ($q->rows as $f) {
            $user_groups[$f['id']] = $f['description'];
        }
        return $user_groups;
    }

    private function getUser() {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "users WHERE id=" . (int)$_GET['id']);
        $user = $q->row;
        resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 150, 130, false);
        if (!empty($user)) {
            $user['photo']      = ($user['photo'] <> '') ? $user['photo'] : ROOT_DIR . 'upload/images/no-image.jpg';
            $user['thumb']      = resizeImage($user['photo'], 150, 130, false);
            $user['birth']      = date("d-m-Y", $user['birth']);
            $user['joined']     = date("Y-M-d D h:i:s", $user['joined']);
            $user['last_login'] = ($user['last_login'] > 1000) ? date("Y-M-d D h:i:s", $user['last_login']) : $this->language['never'];
            $user['adm_last_login'] = ($user['adm_last_login'] > 1000) ? date("Y-M-d D h:i:s", $user['adm_last_login']) : $this->language['never'];
        } else {
            $user = array(
                'name'           => '',
                'email'          => '',
                'user_group'     => 5,
                'description'    => '',
                'photo'          => ROOT_DIR . 'upload/images/no-image.jpg',
                'thumb'          => resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 150, 130, false),
                'birth'          => date("d-m-Y"),
                'joined'         => date("Y-M-d D h:i:s"),
                'last_login'     => $this->language['never'],
                'adm_last_login' => $this->language['never']
            );

        }
        return $user;
    }

    public function index() {
        if ($_SESSION['access'] > 2) {
            die('Access denied');
        }

        if (isset($_POST['fp'])) {
            $this->updateUsers();
        }

        if (isset($_POST['uname']) && (int)$_GET['id'] > 0) {
            $this->updateUser();
        }

        if (isset($_POST['uname']) && $_GET['id'] == 'new') {
            $this->addUser();
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

        if (isset($_GET['view']) && $_GET['view'] == 'tiny') {
            $this->data['user_groups'] = $this->getUserGroups();
            $this->data['user'] = $this->getUser();
            $this->engine->document->addHeaderString('<script type="text/javascript" src="template/js/qfinder/qfinder.js"></script>');

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['users'],
                'link'      => 'index.php?page=users'
            );
            $this->data['breadcrumb_cur'] = $this->language['user'];

            $this->template = 'template/user.tpl';
        } else {
            $start = ((int)$_GET["page_n"] - 1) * (int)$_GET["per_page"];
            $limit = (int)$_GET["per_page"];
            $search = isset($_GET["search"]) ? $_GET["search"] : '';
            $search_query = "(" . DB_PREF . "users.`name` LIKE '%" . $search . "%' OR " . DB_PREF .
                "users.`email` LIKE '%" . $search . "%' OR " . DB_PREF . "users.`description` LIKE '%" .
                $search . "%')";
            $this->data['users'] = $this->getUsers($search, $search_query, $start, $limit);
            $this->data['search'] = $search;
            $this->data['page_count'] = $this->getPageCount($search, $search_query, $limit);
            $this->engine->document->addHeaderString('<link href="template/css/bootstrap-toggle-buttons.css" rel="stylesheet" media="screen">');
            $this->engine->document->addHeaderString('<script src="template/js/jquery.toggle.buttons.js"></script>');

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumb_cur'] = $this->language['users'];

            $this->template = 'template/users.tpl';
        }
    }
}
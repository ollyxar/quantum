<?php
namespace A;

class Tpl_Manager extends \AUnit {

    private $templates_path;
    private $tpl_file = false;

    private function saveTemplate() {
        if (file_put_contents($this->tpl_file, $_POST['code']) === false) {
            $_SESSION['msg'] = 'denied';
        } else {
            $_SESSION['msg'] = 'success';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function index() {
        if ($_SESSION['access'] > 2) {
            die('Access denied');
        }
        $this->templates_path = dirname(dirname(dirname(__FILE__))) . ROOT_DIR . 'template';
        $this->tpl_file = isset($_GET['file']) ? $this->templates_path . $_GET['file'] : false;

        if (isset($_POST['action']) && $_POST['action'] == 'save' && ($this->tpl_file != false)) {
            $this->saveTemplate();
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'denied') {
            $this->data['text_message'] = $this->language['perm_denied'] . ' ' . $this->language['cur_perm'] . getPermission($this->tpl_file);
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
        }
        $this->engine->document->addHeaderString('<link href="template/css/codemirror.css" rel="stylesheet" media="screen">');
        $this->engine->document->addHeaderString('<script src="template/js/codemirror.js"></script>');
        if ($this->tpl_file != false) {
            $ext = strtolower(pathinfo($this->tpl_file, PATHINFO_EXTENSION));
            if (in_array($ext, array('png', 'jpg', 'jpeg', 'gif'))) {
                $this->tpl_file = false;
            }
            switch ($ext) {
                case 'css':
                    $this->engine->document->addHeaderString('<script src="template/js/css.js"></script>');
                    break;
                case 'xml':
                    $this->engine->document->addHeaderString('<script src="template/js/xml.js"></script>');
                    break;
                case 'js':
                    $this->engine->document->addHeaderString('<script src="template/js/javascript.js"></script>');
                    break;
                case 'tpl':
                    $this->engine->document->addHeaderString('<script src="template/js/matchbrackets.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/htmlmixed.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/xml.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/javascript.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/css.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/clike.js"></script>');
                    $this->engine->document->addHeaderString('<script src="template/js/php.js"></script>');
                    break;
                default:
                    break;
            }
        }
        $this->engine->document->addHeaderString('<script src="template/js/jquery.cookie.js"></script>');
        $this->engine->document->addHeaderString('<script src="template/js/jquery.treeview.js"></script>');
        $this->engine->document->addHeaderString('<link href="template/css/jquery.treeview.css" rel="stylesheet" media="screen">');
        $this->data['templates_path'] = $this->templates_path;
        $this->data['tpl_file'] = $this->tpl_file;
        $this->data['ext'] = isset($ext) ? $ext : '';

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumb_cur'] = $this->language['template_manager'];

        $this->template = 'template/tpl_manager.tpl';
    }
}
<?php
namespace A;

class Slider extends \AUnit {

    private function updateSlider() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $params_arr = array('slides' => $_POST['slides']);
            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" .
                $this->engine->db->escape(serialize($params_arr)) . "' WHERE name='slider'");
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function index() {
        if ($_SESSION['access'] >= $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['slides']) && $_POST['action'] == 'save') {
            $this->updateSlider();
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

        $this->engine->document->addHeaderString('<script type="text/javascript" src="template/js/qfinder/qfinder.js"></script>');

        $slides = isset($this->params['slides']) ? $this->params['slides'] : array();

        resizeImage(ROOT_DIR . 'upload/images/no-image.jpg', 90, 80, false);
        foreach ($slides as $id => $slide) {
            $this->data['slides'][$id] = array(
                'src'   => $slide['src'],
                'thumb' => resizeImage($slide['src'], 90, 80, false),
                'link'  => $slide['link']
            );
        }

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['modules'],
            'link'      => 'index.php?page=modules'
        );
        $this->data['breadcrumb_cur'] = $this->language['slider'];

        $this->template = 'template/slider.tpl';
    }
}

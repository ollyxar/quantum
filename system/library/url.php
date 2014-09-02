<?php

class QUrl {
    private $engine;
    public $path = array();
    public $page_n = 1;
    public $is_category = false;
    public $full = '';

    public function __construct(QOllyxar &$engine) {
        $this->engine = $engine;
        $this->full = PRTCL . '://' . $this->engine->host . $this->engine->uri;
        if (CLEAN_URL) {
            $path = substr($this->engine->uri, strlen(ROOT_DIR));
            if (strpos($path, '?') > 0) {
                $path = substr($path, 0, strpos($path, '?'));
            }
            if (SEO_MULTILANG) {
                $path = substr($path, strlen($_SESSION['lang'] . '/'));
            }
            // detecting directory
            if (substr($path, -1) == '/') {
                $this->is_category = true;
            }
            // fill array of parts from url path
            $this->path = explode('/', $path);
            if (count($this->path) > 1 && end($this->path) == '') {
                array_pop($this->path);
            }
            $this->path = array_reverse($this->path);
            $this->fillGET();
        }
        $this->page_n = isset($_GET['page_n']) ? $_GET['page_n'] : 1;
        $_GET['page_n'] = $this->page_n;
    }

    private function fillGET() {
        $keyword = (substr($this->path[0], - strlen(PAGE_SUFFIX)) == PAGE_SUFFIX) ? substr($this->path[0], 0, - strlen(PAGE_SUFFIX)): $this->path[0];
        if ($keyword != '') {
            $q = $this->engine->db->query("SELECT query FROM " . DB_PREF . "url_alias WHERE keyword = '" . $this->engine->db->escape($keyword) . "'");
            if (empty($q->row)) return false;
            $query = $q->row['query'];
            $_GET['_route_'] = $query;
            $records = explode('&', $query);
            foreach ($records as $record) {
                $_GET[substr($record, 0, strpos($record, '='))] = substr($record, strpos($record, '=') + 1);
            }
        }
    }

    public function redirect($url, $status_code = 301) {
        header("Location: " . $url, true, $status_code);
        exit();
    }

    public function correctUrl($route) {
        // SEO PRO
        $built = $this->engine->url->link($route);
        if (CLEAN_URL &&
            strpos($built, '?') === false &&
            strpos($this->full, '?') === false &&
            $this->full != $built &&
            $_GET['_route_'] != ''
        ) {
            $this->engine->url->redirect($built);
        }
    }

    public function getAliases() {
        $aliases = $this->engine->cache->get('aliases');
        if (!$aliases) {
            $records = $this->engine->db->query("SELECT * FROM " . DB_PREF . "url_alias")->rows;
            $aliases = array();
            foreach ($records as $record) {
                $aliases[$record['query']] = array(
                    'id' => $record['id'],
                    'keyword' => $record['keyword'],
                    'is_dir' => (bool)$record['is_directory']
                );
            }
            $this->engine->cache->set('aliases', $aliases);
        }
        return $aliases;
    }

    private function buildBreadcrumbs($category_id, &$arr) {
        $breadcrumbs = $this->engine->cache->get('materials_categories');
        if (!$breadcrumbs) {
            $breadcrumbs = $this->engine->db->query("SELECT id, parent_id FROM " . DB_PREF . "materials WHERE enabled = 1 AND is_category = 1")->rows;
            $this->engine->cache->set('materials_categories', $breadcrumbs);
        }
        foreach ($breadcrumbs as $breadcrumb) {
            if ((int)$breadcrumb['id'] == $category_id) {
                array_unshift($arr, $breadcrumb);
                $this->buildBreadcrumbs($breadcrumb['parent_id'], $arr);
                break;
            }
        }
    }

    private function getKeyword($query) {
        $aliases = $this->getAliases();
        return isset($aliases[$query]) ? $aliases[$query]['keyword'] : '';
    }

    private function getType($query) {
        $aliases = $this->getAliases();
        return isset($aliases[$query]) ? (bool)$aliases[$query]['is_dir'] : false;
    }

    public function link($query) {
        if (CLEAN_URL) {
            $keyword = $this->getKeyword($query);
            $is_dir = $this->getType($query);

            if ($keyword == '') {
                $keyword = '?' . $query;
            } elseif (strpos($query, 'route=materials&material_id=') === 0) {
                // materials
                $id = (int)substr($query, strlen('route=materials&material_id='));
                $ids = array();
                $keyword = '';
                $this->buildBreadcrumbs($id, $ids);
                $material = false;
                if (empty($ids)) {
                    // then it's material - not category
                    $material = $id;
                    $id = (int)$this->engine->db->query("SELECT parent_id FROM " . DB_PREF . "materials WHERE id = " . $id)->row['parent_id'];
                    $this->buildBreadcrumbs($id, $ids);
                }
                foreach ($ids as $id) {
                    $word = $this->getKeyword('route=materials&material_id=' . $id['id']);
                    if ($word == '') {
                        $keyword = '';
                        break;
                    } else {
                        $keyword .= $word . '/';
                    }
                }
                if ($material && $keyword != '') {
                    $word = $this->getKeyword('route=materials&material_id=' . $material);
                    if ($word == '') {
                        $keyword = '';
                    } else {
                        $keyword .= $word . PAGE_SUFFIX;
                    }
                }
            } elseif ($is_dir) {
                $keyword = $keyword . '/';
            } else {
                $keyword = $keyword . PAGE_SUFFIX;
            }

            $keyword = ($query == 'route=home') ? '' : $keyword;
            if (strpos($query, 'route=') === 0) {
                if (SEO_MULTILANG) {
                    $link = PRTCL . '://' . $this->engine->host . ROOT_DIR . $_SESSION['lang'] . '/' . $keyword;
                } else {
                    $link = PRTCL . '://' . $this->engine->host . ROOT_DIR . $keyword;
                }
            } else {
                $link = $query;
            }
        } else {
            if (strpos($query, 'route=') === 0) {
                if (SEO_MULTILANG) {
                    if ($query == 'route=home') {
                        $link = PRTCL . '://' . $this->engine->host . ROOT_DIR . '?lang=' . $_SESSION['lang'];
                    } else {
                        $link = PRTCL . '://' . $this->engine->host . ROOT_DIR . '?' . $query . '&lang=' . $_SESSION['lang'];
                    }
                } else {
                    if ($query == 'route=home') {
                        $link = PRTCL . '://' . $this->engine->host . ROOT_DIR;
                    } else {
                        $link = PRTCL . '://' . $this->engine->host . ROOT_DIR . '?' . $query;
                    }
                }
            } else {
                $link = $query;
            }
        }
        return $link;
    }
} 
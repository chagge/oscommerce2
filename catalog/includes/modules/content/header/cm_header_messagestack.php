<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  use OSC\OM\Registry;

  class cm_header_messagestack {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_header_messagestack() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_HEADER_MESSAGESTACK_TITLE;
      $this->description = MODULE_CONTENT_HEADER_MESSAGESTACK_DESCRIPTION;

      if ( defined('MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_HEADER_MESSAGESTACK_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $messageStack;
      
      if ($messageStack->size('header') > 0) {

        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/messagestack.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
        
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS');
    }

    function install() {
      $OSCOM_Db = Registry::get('Db');

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Enable Message Stack Notifications Module',
        'configuration_key' => 'MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Should the Message Stack Notifications be shown in the header when needed?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'tep_cfg_select_option(array(\'True\', \'False\'), ',
        'date_added' => 'now()'
      ]);

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Sort Order',
        'configuration_key' => 'MODULE_CONTENT_HEADER_MESSAGESTACK_SORT_ORDER',
        'configuration_value' => '0',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'configuration_group_id' => '6',
        'sort_order' => '0',
        'date_added' => 'now()'
      ]);
    }

    function remove() {
      return Registry::get('Db')->query('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")')->rowCount();
    }

    function keys() {
      return array('MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS', 'MODULE_CONTENT_HEADER_MESSAGESTACK_SORT_ORDER');
    }
  }


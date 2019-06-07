<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Base;

class Menus{

        public function get_menus() {

            // $rest_url = trailingslashit( get_rest_url() . self::get_plugin_namespace() . '/menus/' );
            $wp_menus = wp_get_nav_menus();

            $i = 0;
            $rest_menus = array();
            foreach ( $wp_menus as $wp_menu ) {

                $menu = (array) $wp_menu;

                $rest_menus[ $i ]                = $menu;
                $rest_menus[ $i ]['ID']          = $menu['term_id'];
                $rest_menus[ $i ]['name']        = $menu['name'];
                $rest_menus[ $i ]['slug']        = $menu['slug'];
                $rest_menus[ $i ]['description'] = $menu['description'];

                // $rest_menus[ $i ]['meta']['links']['collection'] = $rest_url;
                // $rest_menus[ $i ]['meta']['links']['self']       = $rest_url . $menu['term_id'];

                $i ++;
            }

            // return apply_filters( 'rest_menus_format_menus', $rest_menus );

            return $rest_menus;
        }


       
        public function get_menu($id, $language_filter = NULL) {
            
            // $rest_url       = get_rest_url() . self::get_api_namespace() . '/menus/';
            $wp_menu_object = $id ? wp_get_nav_menu_object( $id ) : array();
            $wp_menu_items  = $id ? wp_get_nav_menu_items( $id ) : array();

            $rest_menu = array();


            if ( $wp_menu_object ) {

                $menu = (array) $wp_menu_object;
                $rest_menu['ID']          = abs( $menu['term_id'] );
                $rest_menu['name']        = $menu['name'];
                $rest_menu['slug']        = $menu['slug'];
                $rest_menu['description'] = $menu['description'];

                $rest_menu_items = array();
                foreach ( $wp_menu_items as $item_object ) {
	                $rest_menu_items[] = $this->format_menu_item( $item_object );
                }

                

                $rest_menu_items = $this->nested_menu_items($rest_menu_items, 0, $language_filter);

                $rest_menu['items']                       = $rest_menu_items;
                // $rest_menu['meta']['links']['collection'] = $rest_url;
                // $rest_menu['meta']['links']['self']       = $rest_url . $id;

            }

            // return apply_filters( 'rest_menus_format_menu', $rest_menu );

            return $rest_menu;
        }


        /**
         * Handle nested menu items.
         *
         * Given a flat array of menu items, split them into parent/child items
         * and recurse over them to return children nested in their parent.
         *
         * @since  1.2.0
         * @param  $menu_items
         * @param  $parent
         * @return array
         */
        private function nested_menu_items( &$menu_items, $parent = null, $language_filter = NULL, $contatenated_parent_id = NULL ) {

            $parents = array();
            $children = array();

            // Separate menu_items into parents & children.
            array_map( function( $i ) use ( $parent, &$children, &$parents ){
                if ( $i['id'] != $parent && $i['parent'] == $parent ) {
                    $parents[] = $i;
                } else {
                    $children[] = $i;
                }
            }, $menu_items );

            $result_parents = array();

            foreach ( $parents as &$parent ) {

                $current_name = NULL;
                $current_id = $parent['id'];
                $current_name = "$contatenated_parent_id$current_id";
                $option_id = "menu_$language_filter" . "_$current_name";
                $option = get_option($option_id);

                if ( $language_filter == NULL || ($language_filter != NULL && $option)){
                    if ( $this->has_children( $children, $parent['id'] ) ) {
                        $parent['children'] = $this->nested_menu_items( $children, $parent['id'], $language_filter, $current_name . '_' );
                    }
                    $result_parents[] = $parent;
                }
                
            }

            return  $result_parents;
        }


        /**
         * Check if a collection of menu items contains an item that is the parent id of 'id'.
         *
         * @since  1.2.0
         * @param  array $items
         * @param  int $id
         * @return array
         */
        private function has_children( $items, $id ) {
            return array_filter( $items, function( $i ) use ( $id ) {
                return $i['parent'] == $id;
            } );
        }


        /**
         * Returns all child nav_menu_items under a specific parent.
         *
         * @since   1.2.0
         * @param int   $parent_id      The parent nav_menu_item ID
         * @param array $nav_menu_items Navigation menu items
         * @param bool  $depth          Gives all children or direct children only
         * @return array	returns filtered array of nav_menu_items
         */
        public function get_nav_menu_item_children( $parent_id, $nav_menu_items, $depth = true ) {

            $nav_menu_item_list = array();

            foreach ( (array) $nav_menu_items as $nav_menu_item ) {

                if ( $nav_menu_item->menu_item_parent == $parent_id ) {

                    $nav_menu_item_list[] = $this->format_menu_item( $nav_menu_item, true, $nav_menu_items );

                    if ( $depth ) {
                        if ( $children = $this->get_nav_menu_item_children( $nav_menu_item->ID, $nav_menu_items ) ) {
                            $nav_menu_item_list = array_merge( $nav_menu_item_list, $children );
                        }
                    }

                }
            }

            return $nav_menu_item_list;
        }


        /**
         * Format a menu item for REST API consumption.
         *
         * @since  1.2.0
         * @param  object|array $menu_item  The menu item
         * @param  bool         $children   Get menu item children (default false)
         * @param  array        $menu       The menu the item belongs to (used when $children is set to true)
         * @return array	a formatted menu item for REST
         */
        public function format_menu_item( $menu_item, $children = false, $menu = array() ) {

            $item = (array) $menu_item;

            $menu_item = array(
                'id'          => abs( $item['ID'] ),
                'order'       => (int) $item['menu_order'],
                'parent'      => abs( $item['menu_item_parent'] ),
                'title'       => $item['title'],
                'url'         => $item['url'],
                'attr'        => $item['attr_title'],
                'target'      => $item['target'],
                'classes'     => implode( ' ', $item['classes'] ),
                'xfn'         => $item['xfn'],
                'description' => $item['description'],
                'object_id'   => abs( $item['object_id'] ),
                'object'      => $item['object'],
                'object_slug' => get_post( $item['object_id'] )->post_name,
                'type'        => $item['type'],
                'type_label'  => $item['type_label'],
            );

            if ($item['type_label'] == "Page"){
                $menu_item["page_id"] = (int)$menu_item["object_id"];
            }

            if ( $children === true && ! empty( $menu ) ) {
	            $menu_item['children'] = $this->get_nav_menu_item_children( $item['ID'], $menu );
            }

            // return apply_filters( 'rest_menus_format_menu_item', $menu_item );

            return $menu_item;
        }


}
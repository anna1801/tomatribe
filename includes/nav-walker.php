<?php

/*
 * Header menu walker.
 *
 * Outputs the desktop header markup:
 *   depth 0 -> <a class="tv-nav-link">, or .tv-nav-dropdown + .tv-mega-menu when it has children
 *   depth 1 -> .tv-mega-column with an <a class="tv-mega-title">
 *   depth 2 -> plain <a> inside the column
 *
 * Pass 'tv_part' => 'left' | 'right' to wp_nav_menu() to print only the first or
 * second half of the top-level items (the logo sits between the two halves).
 */
class Tomatribe_Header_Nav_Walker extends Walker_Nav_Menu {

  public function walk($elements, $max_depth, ...$args) {
    $part = isset($args[0]->tv_part) ? $args[0]->tv_part : '';
    if ($part === 'left' || $part === 'right') {
      $elements = $this->filter_part($elements, $part);
    }
    return parent::walk($elements, $max_depth, ...$args);
  }

  /* Keep the top-level items (and their descendants) that belong to the given half */
  private function filter_part($elements, $part) {
    $parents = array();
    $top_ids = array();
    foreach ($elements as $item) {
      $parents[$item->ID] = (int) $item->menu_item_parent;
      if ((int) $item->menu_item_parent === 0) {
        $top_ids[] = $item->ID;
      }
    }

    $split = (int) ceil(count($top_ids) / 2);
    $keep = $part === 'left' ? array_slice($top_ids, 0, $split) : array_slice($top_ids, $split);

    return array_filter($elements, function ($item) use ($parents, $keep) {
      $id = $item->ID;
      while (!empty($parents[$id])) {
        $id = $parents[$id];
      }
      return in_array($id, $keep);
    });
  }

  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= '<div class="tv-mega-menu">';
    }
  }

  public function end_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= '</div>';
    }
  }

  public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
    $item = $data_object;
    $classes = (array) $item->classes;
    $has_children = in_array('menu-item-has-children', $classes);
    $is_active = (bool) array_intersect(array('current-menu-item', 'current-menu-parent', 'current-menu-ancestor'), $classes);

    // Custom classes added in Appearance > Menus (enable "CSS Classes" in Screen Options)
    $custom_classes = array_filter($classes, function ($class) {
      return $class && strpos($class, 'menu-item') !== 0 && strpos($class, 'current') !== 0 && strpos($class, 'page_item') !== 0 && strpos($class, 'page-item') !== 0;
    });

    $link_classes = $custom_classes;
    if ($depth === 0) {
      $link_classes[] = 'tv-nav-link';
    } elseif ($depth === 1) {
      $link_classes[] = 'tv-mega-title';
    }
    if ($is_active) {
      $link_classes[] = 'active';
    }

    $atts = array(
      'href'   => !empty($item->url) ? $item->url : '#',
      'class'  => implode(' ', $link_classes),
      'target' => $item->target,
      'rel'    => $item->target === '_blank' ? 'noopener' : $item->xfn,
      'title'  => $item->attr_title,
    );

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if ($value !== '' && $value !== null) {
        $value = $attr === 'href' ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $title = apply_filters('nav_menu_item_title', $item->title, $item, $args, $depth);
    if ($depth === 0 && $has_children) {
      $title .= ' <i class="fa fa-angle-down"></i>';
    }

    if ($depth === 0 && $has_children) {
      $output .= '<div class="tv-nav-dropdown">';
    } elseif ($depth === 1) {
      $output .= '<div class="tv-mega-column">';
    }

    $output .= '<a' . $attributes . '>' . $title . '</a>';
  }

  public function end_el(&$output, $data_object, $depth = 0, $args = null) {
    $has_children = in_array('menu-item-has-children', (array) $data_object->classes);
    if (($depth === 0 && $has_children) || $depth === 1) {
      $output .= '</div>';
    }
  }
}

/*
 * Mobile off-canvas menu walker.
 *
 * Outputs the mobile navigation markup:
 *   depth 0 -> plain <a>, or .tv-mobile-category (title + toggle + .tv-mobile-submenu) when it has children
 *   depth 1 -> <a class="tv-mobile-main-category"> inside the submenu
 *   depth 2 -> plain <a> inside the submenu
 */
class Tomatribe_Mobile_Nav_Walker extends Walker_Nav_Menu {

  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= '<div class="tv-mobile-submenu">';
    }
  }

  public function end_lvl(&$output, $depth = 0, $args = null) {
    if ($depth === 0) {
      $output .= '</div>';
    }
  }

  public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
    $item = $data_object;
    $classes = (array) $item->classes;
    $has_children = in_array('menu-item-has-children', $classes);
    $is_active = (bool) array_intersect(array('current-menu-item', 'current-menu-parent', 'current-menu-ancestor'), $classes);

    $link_classes = array();
    if ($depth === 1) {
      $link_classes[] = 'tv-mobile-main-category';
    }
    if ($is_active && $depth === 0 && !$has_children) {
      $link_classes[] = 'active';
    }

    $atts = array(
      'href'   => !empty($item->url) ? $item->url : '#',
      'class'  => implode(' ', $link_classes),
      'target' => $item->target,
      'rel'    => $item->target === '_blank' ? 'noopener' : $item->xfn,
      'title'  => $item->attr_title,
    );

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if ($value !== '' && $value !== null) {
        $value = $attr === 'href' ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $title = apply_filters('nav_menu_item_title', $item->title, $item, $args, $depth);
    $link = '<a' . $attributes . '>' . $title . '</a>';

    if ($depth === 0 && $has_children) {
      $output .= '<div class="tv-mobile-category"><div class="tv-mobile-category-title">' . $link
        . '<button type="button" aria-label="Toggle submenu"><i class="fa fa-angle-down"></i></button></div>';
    } else {
      $output .= $link;
    }
  }

  public function end_el(&$output, $data_object, $depth = 0, $args = null) {
    if ($depth === 0 && in_array('menu-item-has-children', (array) $data_object->classes)) {
      $output .= '</div>';
    }
  }
}

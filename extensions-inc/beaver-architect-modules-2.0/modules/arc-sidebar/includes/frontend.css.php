<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 14/3/18
 * Time: 5:44 PM
 */
?>
/* Pizazz Adaptive Sidebar CSS */
.arc-adaptive-sidebar {
  display: flex;
  justify-content: space-between;
}

.arc-adaptive-sidebar.orientation-vertical {
  flex-direction: column;
}

.arc-adaptive-sidebar.orientation-horizontal {
  flex-direction: row;
}

.arc-adaptive-sidebar.orientation-vertical > .widget,
.arc-adaptive-sidebar.orientation-vertical > .fl-widget {
  margin-bottom: 20px;
}

.arc-adaptive-sidebar.orientation-horizontal > .widget,
.arc-adaptive-sidebar.orientation-horizontal > .fl-widget {
  margin-right: 3%;
  flex-grow: 1;
}
<?php
  /**
   * Project pizazzwp-architect.
   * File: exceptions.php
   * User: chrishoward
   * Date: 10/03/2016
   * Time: 4:00 PM
   */

  $whoops = new \Whoops\Run;
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
  $whoops->register();
<?php

use yii\helpers\Url;

?>
<div class="site-menubar site-menubar-light">
  <div class="site-menubar-body">
    <div>
      <div>
        <ul class="site-menu" data-plugin="menu">
          <li class="site-menu-category">General</li>
          <li class="dropdown site-menu-item has-sub">
            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
              <i class="site-menu-icon pe-users" aria-hidden="true"></i>
              <span class="site-menu-title">Usuarios</span>
              <span class="site-menu-arrow"></span>
            </a>
            <div class="dropdown-menu">
              <div class="site-menu-scroll-wrap is-list">
                <div>
                  <div>
                    <ul class="site-menu-sub site-menu-normal-list">
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/usuarios">
                          <span class="site-menu-title">Listado de usuarios</span>
                        </a>
                      </li>
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/admin/resultados-por-empleados">
                          <span class="site-menu-title">Agregar usuario</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          
        </ul>
      </div>
    </div>
  </div>
</div>
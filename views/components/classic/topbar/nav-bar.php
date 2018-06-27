<?php
use yii\helpers\Url;
use app\modules\ModUsuarios\models\EntUsuarios;

?>
<nav class="site-navbar ryg-header navbar navbar-default navbar-fixed-top navbar-mega navbar-inverse"
role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
    data-toggle="menubar">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-bar"></span>
    </button>
    <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
    data-toggle="collapse">
      <i class="icon wb-more-horizontal" aria-hidden="true"></i>
    </button>
    <a class="navbar-brand navbar-brand-center" href="<?=Url::base()?>">
      <img class="navbar-brand-logo navbar-brand-logo-normal" src="<?=Url::base()?>/webAssets/images/overhaul.png"
      title="Remark">
      <img class="navbar-brand-logo navbar-brand-logo-special" src="<?=Url::base()?>/webAssets/images/overhaul.png"
      title="Remark">
      
    </a>
    
  </div>
  <div class="navbar-container container-fluid">
    <!-- Navbar Collapse -->
    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
      <!-- Navbar Toolbar -->
      <ul class="nav navbar-toolbar">
        <li class="nav-item hidden-float" id="toggleMenubar">
          <a class="nav-link" data-toggle="menubar" href="#" role="button">
            <i class="icon hamburger hamburger-arrow-left">
                <span class="sr-only">Toggle menubar</span>
                <span class="hamburger-bar"></span>
              </i>
          </a>
        </li>
        <a class="navbar-brand navbar-brand-center navbar-logo-center" href="<?=Url::base()?>">
          <img class="navbar-brand-logo navbar-brand-logo-normal" src="<?=Url::base()?>/webAssets/images/overhaul.png"
          title="Remark">
        </a>
      </ul>
      <!-- End Navbar Toolbar -->
      <!-- Navbar Toolbar Right -->
      <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
        
        <li class="nav-item dropdown">
          <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
          data-animation="scale-up" role="button">
            <span class="user-name"><?=EntUsuarios::getUsuarioLogueado()->nombreCompleto?></span>
            <span class="avatar avatar-online">
              <img src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt="...">
              <i></i>
            </span>
          </a>
          <div class="dropdown-menu" role="menu">
            <a class="dropdown-item  no-pjax" href="<?=Url::base()?>/usuarios/cambiar-pass" role="menuitem"><i class="icon wb-more-horizontal" aria-hidden="true"></i> Cambiar contraseña</a>
            <a class="dropdown-item  no-pjax" href="<?=Url::base()?>/site/logout" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Logout</a>
          </div>
        </li>
      </ul>
      <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->
  </div>
</nav>